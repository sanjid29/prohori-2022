<?php

namespace App\Mainframe\Modules\Users\Traits;

use App\Country;
use App\Group;
use App\InAppNotification;
use App\Mainframe\Notifications\Auth\ResetPassword;
use App\Mainframe\Notifications\Auth\VerifyEmail;
use App\User;
use Carbon\Carbon;
use InvalidArgumentException;
use Str;

/** @mixin User $this */
trait UserTrait
{
    protected $allowedPermissionsValues = [-1, 0, 1];
    /*
    |--------------------------------------------------------------------------
    | Query scopes + Dynamic scopes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    /**
     * Accessor for profile_pic
     *
     * @return null|string
     */
    public function getProfilePicAttribute()
    {
        $upload = $this->uploads()->remember(timer('very-short'))
            ->where('type', 'profile-pic')->first();

        if ($upload) {
            return $upload->url;
        }

        return asset('mainframe/images/user.png');

    }

    public function getGroupAttribute()
    {
        return $this->groups()->first();
    }

    /**
     * User type attribute
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        if ($this->isA('admin')) {
            return 'admin';
        }

        return '-';
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */
    // public function setFirstNameAttribute($value) { $this->attributes['first_name'] = strtolower($value); }
    /**
     * Mutator for taking permissions.
     *
     * @param  array  $permissions
     * @return string
     */
    public function setPermissionsAttribute(array $permissions)
    {
        // Merge permissions
        $permissions = array_merge($this->permissions, $permissions);

        // Loop through and adjust permissions as needed
        foreach ($permissions as $permission => &$value) {
            // Lets make sure there is a valid permission value
            if (!in_array($value = (int) $value, $this->allowedPermissionsValues)) {
                throw new InvalidArgumentException("Invalid value [$value] for permission [$permission] given.");
            }

            // If the value is 0, delete it
            if ($value === 0) {
                unset($permissions[$permission]);
            }
        }

        $this->attributes['permissions'] = (!empty($permissions)) ? json_encode($permissions) : '';
    }

    /**
     * Mutator for giving permissions.
     *
     * @param  mixed  $permissions
     * @return array  $_permissions
     */
    public function getPermissionsAttribute($permissions)
    {
        if (!$permissions) {
            return [];
        }

        if (is_array($permissions)) {
            return $permissions;
        }

        if (!$_permissions = json_decode($permissions, true)) {
            throw new InvalidArgumentException("Cannot JSON decode permissions [$permissions].");
        }

        return $_permissions;
    }
    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    // public function getUrlAttribute(){return asset($this->path); }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_group');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function inAppNotifications()
    {
        return $this->hasMany(InAppNotification::class, 'element_id')->where('module_id', $this->module()->id);
        // return $this->morphMany(InAppNotification::class, 'notifiable'); // Note: Do not use morphMany
    }

    public function operatingGroups()
    {
        return $this->spreadModels('groups');
    }
    /*
    |--------------------------------------------------------------------------
    | Helper functions
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Autofill and functions to calculated field updates
    |--------------------------------------------------------------------------
    */
    /**
     * Fill data and set calculated data in fields for saving the module
     * This can depend of supporting fillFunct, setFunct,calculateFunct
     * return $this
     */
    public function populate()
    {
        $this->is_test = $this->is_test ?? 0;
        $this->is_active = $this->is_active ?? 1;

        $this->setName()
            ->setCountryName()
            ->setApiTokenGeneratedAt();

        return $this;
    }

    public function setName()
    {
        $this->name = $this->resolveName();

        return $this;
    }

    public function resolveName()
    {
        $name = $this->name_initial." ".$this->first_name." ".$this->last_name;
        if (!strlen(trim($name))) {
            $name = trim($this->full_name);
        }

        // $this->full_name = $this->full_name ?? $name;

        return $name;
    }

    public function setCountryName()
    {
        if ($this->country()->exists()) {
            $this->country_name = $this->country->name;
        }

        return $this;
    }

    public function setApiTokenGeneratedAt()
    {
        if ($this->api_token != $this->getOriginal('api_token')) {
            $this->api_token_generated_at = Carbon::now();
        }

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Non-static helper functions
    |--------------------------------------------------------------------------
    */
    /**
     * Construct address
     *
     * @return string
     */
    public function address()
    {
        $str = '';

        $fields = [
            'address1',
            'address2',
            'city',
            'county',
            'country_name',
            'zip_code',
        ];

        foreach ($fields as $field) {
            if (strlen($this->$field)) {
                $str .= $this->$field.', ';
            }
        }

        return trim($str, ', ');
    }

    /**
     * Handle a set of actions that result from a successful login.
     * i.e. update login timestamp. authToken if it is expired.
     */
    public function hasLoggedIn()
    {
        $this->updateLoginTimestamps();

        if ($this->authTokenHasExpired() || !$this->auth_token) {
            $this->updateAuthToken();
        }

        return $this;
    }

    /**
     * Check if auth_token has expired.
     *
     * @return bool
     */
    public function authTokenHasExpired()
    {
        return $this->last_login_at->addMinutes(config('session.lifetime')) < now();
    }

    /**
     * Update login timestamps
     *
     * @return $this
     */
    public function updateLoginTimestamps()
    {

        $this->last_login_at = now();
        $updates = ['last_login_at' => now()];

        if (!$this->first_login_at) {
            $this->first_login_at = now();
            $updates['first_login_at'] = now();
        }

        User::where('id', $this->id)->update($updates);

        return $this;

    }

    /**
     * Generates auth_token (bearer token) for this user.
     *
     * @return bool|string
     */
    public function updateAuthToken()
    {
        User::where('id', $this->id)->update(['auth_token' => $this->createAuthToken()]);

        return $this;
    }

    /**
     * Create auth_token
     *
     * @return false|string
     */
    public function createAuthToken()
    {
        return substr(bcrypt($this->email.'|'.$this->password.'|'.date("Y-m-d H:i:s")), 10, 32);
    }

    /**
     * Check if user belongs to a tenant. If yes, then return tenant id
     *
     * @return bool|int|null
     */
    public function ofTenant()
    {
        return $this->tenant_id;
    }

    /*-----------------------------------------
    | Section: user group related functions
    |-----------------------------------------*/

    /**
     * Check if user is guest
     *
     * @return bool
     */
    public function isGuest()
    {
        return $this->id ? false : true;
    }

    /**
     * Proxy function Check if user belongs to a group.
     *
     * @param  string  $name
     * @return bool
     */
    public function isA($name = '')
    {
        return $this->inGroup($name);
    }

    /**
     * Check if user belongs to the group
     *
     * @param  string  $name  group name
     * @return bool
     */
    public function isInGroup($name)
    {
        if ($group = Group::byName($name)) {
            return $this->groups->contains('id', $group->id);
        }

        return false;
    }

    /**
     * Check if user belongs to the group.
     *
     * @param  string  $name
     * @return bool
     * @deprecated use isInGroup()
     */
    public function inGroup($name)
    {
        return $this->isInGroup($name);
    }

    /**
     * Check if user belongs to any of the groups
     *
     * @param  array  $names
     * @return bool
     */
    public function inAnyGroup($names = [])
    {
        $names = \Arr::wrap($names);
        foreach ($names as $name) {
            if ($this->isInGroup($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user belongs to all of the given groups
     *
     * @param  array  $names
     * @return bool
     */
    public function inAllGroups($names = [])
    {
        $names = \Arr::wrap($names);
        foreach ($names as $name) {
            if (!$this->isInGroup($name)) {
                return false;
            }
        }

        return true;

    }

    /**
     * Checks if user belongs to the groupId
     *
     * @param $group_id
     * @return bool
     */
    public function inGroupId($group_id)
    {
        if (is_array($group_id)) {
            foreach ($group_id as $id) {
                if (in_array($id, (array) $this->group_ids)) {
                    return true;
                }
            }

            return false;
        }

        return in_array($group_id, (array) $this->group_ids);
    }

    /**
     * Checks if user belongs to one of the following
     *
     * @param $group_ids array
     * @return bool
     */
    public function inGroupIds($group_ids = [])
    {
        foreach ($group_ids as $group_id) {
            if ($this->inGroupId($group_id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find if the user belongs to all the groups.
     *
     * @param  array  $group_ids
     * @return bool
     */
    public function inAllGroupIds($group_ids = [])
    {
        foreach ($group_ids as $group_id) {
            if (!$this->inGroupId($group_id)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the user is a super user - has
     * access to everything regardless of permissions.
     *
     * @return bool
     */
    public function isSuperUser()
    {
        return $this->inGroupId(Group::superadmin()->id) || $this->hasPermission('superuser');
    }

    /**
     * Checks if the user is a super user - has
     * access to everything regardless of permissions.
     *
     * @return bool
     */
    public function isApiUser()
    {
        return $this->inGroupId(Group::api()->id);
    }


    /*--------------------------------------
    | Section: Permission related functions
    |-------------------------------------*/

    /**
     * Returns an array of merged permissions for each
     * group the user is in.
     *
     * @return array
     */
    public function getMergedPermissions()
    {
        $permissions = [];

        foreach ($this->groups as $group) {
            $permissions = array_merge($permissions, $group->permissions);
        }

        $permissions = array_merge($permissions, $this->permissions);
        return $permissions;
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     * Super users have access no matter what.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     * @alias hasPermission($permissions, $all = true)
     */
    public function hasAccess($permissions, $all = true)
    {
        return $this->hasPermission($permissions, $all);
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     * Super users DON'T have access no matter what.
     *
     * @param  string|array  $permissions
     * @param  bool  $all
     * @return bool
     * @noinspection PhpUnusedLocalVariableInspection
     * @noinspection NestedPositiveIfStatementsInspection
     */
    public function hasPermission($permissions, $all = true)
    {
        if ($this->inGroupId(Group::superadmin()->id)) { // Note - Need to keep this
            return true;
        }

        $mergedPermissions = $this->getMergedPermissions();

        foreach ((array) $permissions as $permission) {
            // We will set a flag now for whether this permission was
            // matched at all.
            $matched = true;

            // Now, let's check if the permission ends in a wildcard "*" symbol.
            // If it does, we'll check through all the merged permissions to see
            // if a permission exists which matches the wildcard.
            if ((strlen($permission) > 1) && Str::endsWith($permission, '*')) {
                $matched = false;

                foreach ($mergedPermissions as $mergedPermission => $value) {
                    // Strip the '*' off the end of the permission.
                    $checkPermission = substr($permission, 0, -1);

                    // We will make sure that the merged permission does not
                    // exactly match our permission, but starts with it.
                    if ($checkPermission != $mergedPermission && Str::startsWith($mergedPermission,
                            $checkPermission) and $value == 1) {
                        $matched = true;
                        break;
                    }
                }
            } else {
                if ((strlen($permission) > 1) && Str::startsWith($permission, '*')) {
                    $matched = false;

                    foreach ($mergedPermissions as $mergedPermission => $value) {
                        // Strip the '*' off the beginning of the permission.
                        $checkPermission = substr($permission, 1);

                        // We will make sure that the merged permission does not
                        // exactly match our permission, but ends with it.
                        if ($checkPermission != $mergedPermission && Str::endsWith($mergedPermission,
                                $checkPermission) and $value == 1) {
                            $matched = true;
                            break;
                        }
                    }
                } else {
                    $matched = false;

                    foreach ($mergedPermissions as $mergedPermission => $value) {
                        // This time check if the mergedPermission ends in wildcard "*" symbol.
                        if ((strlen($mergedPermission) > 1) && Str::endsWith($mergedPermission, '*')) {
                            $matched = false;

                            // Strip the '*' off the end of the permission.
                            $checkMergedPermission = substr($mergedPermission, 0, -1);

                            // We will make sure that the merged permission does not
                            // exactly match our permission, but starts with it.
                            if ($checkMergedPermission != $permission && Str::startsWith($permission,
                                    $checkMergedPermission) && $value == 1) {
                                $matched = true;
                                break;
                            }
                        }

                        // Otherwise, we'll fallback to standard permissions checking where
                        // we match that permissions explicitly exist.
                        else {
                            if ($permission == $mergedPermission and $mergedPermissions[$permission] == 1) {
                                $matched = true;
                                break;
                            }
                        }
                    }
                }
            }

            // Now, we will check if we have to match all
            // permissions or any permission and return
            // accordingly.
            if ($all === true and $matched === false) {
                return false;
            }

            if ($all === false and $matched === true) {
                return true;
            }
        }

        return !($all === false);
    }

    /**
     * Returns if the user has access to any of the
     * given permissions.
     *
     * @param  array  $permissions
     * @return bool
     */
    public function hasAnyAccess(array $permissions)
    {
        return $this->hasAccess($permissions, false);
    }

    /*
    |--------------------------------------------------------------------------
    | Static helper functions
    |--------------------------------------------------------------------------
    */

    /**
     * Get a list of admin user email. These will be used when some event
     * takes place in the system and admins should be notified.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function adminEmails()
    {
        return config('projects.'.projectKey().'.config.default_email_recipients');
    }

    /**
     * Find user based on bearer token(auth_token)
     *
     * @param $id
     * @return User|mixed|null
     */
    public static function byId($id = null)
    {
        return User::active()->remember(timer('short'))->find($id);

    }

    /**
     * @param  null  $token
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     */
    public static function bearer($token = null)
    {
        $token = $token ?: request()->bearerToken();

        if ($token) {
            return User::active()
                ->where('auth_token', $token)
                ->remember(timer('short'))
                ->first();
        }

        // return Auth::guard('bearer')->user();
    }

    /**
     * Resolve the API caller
     *
     * @param  null|mixed  $token
     * @param  null|mixed  $clientId
     * @return null|User|mixed
     */
    public static function apiAuthenticator($token = null, $clientId = null)
    {
        $token = $token ?: request()->header('X-Auth-Token');
        $clientId = $clientId ?: request()->header('client-id');

        if ($token && $clientId) {
            return User::active()
                ->where('api_token', $token)
                ->remember(timer('short'))
                ->find($clientId);
        }
    }

    /**
     * Create an empty guest user
     *
     * @return User
     */
    public static function guestInstance()
    {
        return new User(['first_name' => 'guest']);
    }

    /*
    |--------------------------------------------------------------------------
    | Ability to create, edit, delete or restore
    |--------------------------------------------------------------------------
    |
    | An element can be editable or non-editable based on it's internal status
    | This is not related to any user, rather it is a model's individual sate
    | For example - A confirmed quotation should not be editable regardless
    | Of who is attempting to edit it.
    |
    */

    // public function isViewable() { return true; }
    // public function isCreatable() { return true; }
    // public function isEditable() { return true; }
    // public function isDeletable() { return true; }

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    */
    /**
     * Send email verification link.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notifyNow(new VerifyEmail());
    }

    /**
     * Send reset password link
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notifyNow(new ResetPassword($token));
    }

    /**
     * Test user notification
     */
    public function createTestInAppNotification()
    {
        $title = 'Test notification';
        $notification = new InAppNotification([
            'module_id' => $this->module()->id,
            'element_id' => $this->id,
            'user_id' => $this->id,
            'name' => $title,
            'subtitle' => $title,
            'type' => 'generic',
            'data' => json_encode([
                'user_id' => $this->id,
                'type' => 'News alert',
            ]),
        ]);

        $notification->process()->save();
    }

}