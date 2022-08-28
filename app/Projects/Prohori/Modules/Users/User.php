<?php

namespace App\Projects\Prohori\Modules\Users;

use App\Group;
use App\Mainframe\Features\Core\Traits\Validable;
use App\Mainframe\Features\Modular\BaseModule\Traits\ModularTrait;
use App\Mainframe\Features\Multitenant\GlobalScope\CheckTenantScope;
use App\Mainframe\Modules\Users\Traits\UserTrait;
use App\Projects\Prohori\Notifications\Auth\ResetPassword;
use App\Projects\Prohori\Notifications\Auth\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Watson\Rememberable\Rememberable;

/**
 * App\Projects\Prohori\Modules\Users\User
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $project_id
 * @property int|null $tenant_id
 * @property string|null $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $api_token X-Auth-Token
 * @property string|null $api_token_generated_at
 * @property int $is_tenant_editable
 * @property array $permissions
 * @property int|null $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $deleted_by
 * @property string|null $name_initial
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $full_name
 * @property string|null $gender
 * @property string|null $device_token
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $county
 * @property int|null $country_id
 * @property string|null $country_name
 * @property string|null $zip_code
 * @property string|null $phone
 * @property string|null $mobile
 * @property \Illuminate\Support\Carbon|null $first_login_at
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $auth_token Bearer token
 * @property string|null $email_verified_at
 * @property string|null $email_verification_code
 * @property string|null $currency
 * @property string|null $social_account_id
 * @property string|null $social_account_type
 * @property string|null $dob
 * @property array|null $group_ids
 * @property int|null $is_test
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Change[] $changes
 * @property-read int|null $changes_count
 * @property-read \App\Country|null $country
 * @property-read \App\User|null $creator
 * @property-read null|string $profile_pic
 * @property-read string $type
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InAppNotification[] $inAppNotifications
 * @property-read int|null $in_app_notifications_count
 * @property-read \App\Module $linkedModule
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant|null $tenant
 * @property-read \App\User|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApiTokenGeneratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAuthToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGroupIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsTenantEditable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNameInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @property-read mixed $group
 * @property string|null $date_of_birth
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @property string|null $name_ext
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNameExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSlug($value)
 */
class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use SoftDeletes,
        Rememberable,
        \OwenIt\Auditing\Auditable,
        ModularTrait,
        Validable,
        Notifiable;

    use UserTrait;
    use UserHelper;

    /**
     * Constants
     */
    public const PASSWORD_VALIDATION_RULE = 'required|confirmed|min:6|regex:/[a-zA-Z]/|regex:/[0-9]/';

    /*
    |--------------------------------------------------------------------------
    | User group definitions
    |--------------------------------------------------------------------------
    */
    public const SUPERUSER_GROUP_ID = 1;
    public const API_GROUP_ID = 2;
    public const TENANT_ADMIN_GROUP_ID = 3;
    public const PROJECT_ADMIN_GROUP_ID = 4;
    public const USER_GROUP_ID = 5;

    public const SUPERUSER_GROUP = 'superuser';
    public const API_GROUP = 'api';
    public const TENANT_ADMIN_GROUP = 'tenant-admin';
    public const PROJECT_ADMIN_GROUP = 'project-admin';
    public const USER_GROUP = 'user';

    public const GENDER_MALE = 'Male';
    public const GENDER_FEMALE = 'Female';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'users';
    protected $table = 'users';

    protected $fillable = [
        'project_id',
        'tenant_id',
        'uuid',
        'name',
        'email',
        'password',
        'remember_token',
        'api_token',
        'api_token_generated_at',
        'is_tenant_editable',
        'permissions',
        'is_active',
        'name_initial',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'device_token',
        'address1',
        'address2',
        'city',
        'county',
        'country_id',
        'country_name',
        'zip_code',
        'phone',
        'mobile',
        'first_login_at',
        'last_login_at',
        'auth_token',
        'email_verified_at',
        'email_verification_code',
        'currency',
        'social_account_id',
        'social_account_type',
        'dob',
        'group_ids',
        'is_test',
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'first_login_at', 'last_login_at',];
    protected $casts = ['group_ids' => 'array'];
    // protected $with = [];
    protected $appends = [
        // 'type', 'profile_pic'
    ];

    protected $spreadFields = [
        'group_ids' => Group::class,
    ];

    protected $tagFields = [
        // 'first_name',
        // 'group_ids',
    ];

    /*
    |--------------------------------------------------------------------------
    | Code: Tenant configs
    |--------------------------------------------------------------------------
    */
    /**
     * Enable tenant context
     *
     * @var bool
     */
    protected $tenantEnabled = false;

    /**
     * If true then tenants will be able to see items where tenant_id=0
     *
     * @var bool
     */
    protected $showGlobalTenantElements = true;

    /**
     * If true then tenants will be able to see items where tenant_id=null
     *
     * @var bool
     */
    protected $showNonTenantElements = true;

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    */
    // public static $types = [];

    /*
    |--------------------------------------------------------------------------
    | Boot method and model events.
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();
        self::observe(UserObserver::class);

        // Skip audit log if there is no change
        Audit::creating(function (Audit $model) {
            if (empty($model->old_values) && empty($model->new_values)) {
                return false;
            }
        });

        // Add tenant scope to model if current user() belongs to a tenant
        if (user()->ofTenant()) {
            static::addGlobalScope(new CheckTenantScope);
        }

        // static::saving(function (User $element) { });
        // static::creating(function (User $element) { });
        // static::updating(function (User $element) { });
        // static::created(function (User $element) { });
        // static::updated(function (User $element) { });
        static::saved(function (User $element) {
            $element->groups()->sync($element->group_ids);
        });
        // static::deleting(function (User $element) { });
        // static::deleted(function (User $element) { });
    }

    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */
    /**
     * Send email verification link.
     */
    public function sendEmailVerificationNotification()
    {
        // Note: Utilize project asset instead of Mainframe default
        $this->notifyNow(new VerifyEmail());
    }

    /**
     * Send reset password link
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        // Note: Utilize project asset instead of Mainframe default
        $this->notifyNow(new ResetPassword($token));
    }


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
    // public function getFirstNameAttribute($value) { return ucfirst($value); }

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */
    // public function setFirstNameAttribute($value) { $this->attributes['first_name'] = strtolower($value); }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    // public function getUrlAttribute(){return asset($this->path); }
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

        if ($this->isA('user')) {
            return 'user';
        }

        return '-';
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function sGroups()
    {
        return $this->spreadModels('groups');
    }

    public function sTags()
    {
        return $this->spreadTags('first_name');
    }

    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */

}