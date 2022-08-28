<?php

namespace App\Projects\Prohori\Modules\PushNotifications;

use App\Mainframe\Modules\PushNotifications\Traits\PushNotificationTrait;
use App\Projects\Prohori\Features\Modular\BaseModule\BaseModule;

/**
 * App\Projects\Prohori\Modules\PushNotifications\PushNotification
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $project_id
 * @property int|null $tenant_id
 * @property string|null $name
 * @property string|null $name_ext
 * @property string|null $slug
 * @property int|null $user_id Recipient user id
 * @property string|null $device_token Firebase Device Identifier to target a user
 * @property int|null $in_app_notification_id Related in-app notification
 * @property int|null $order Can be used for ordering/sequencing sending
 * @property string|null $type Generic|Popup Type indicates the purpose or objective. It is often mapped with a paired in-app notification'
 * @property string|null $event Name of the event i.e. "appointment.created"
 * @property string|null $body Main body of the notification
 * @property string|null $data Additional JSON payload
 * @property string|null $api_response Full JSON response from the sender service
 * @property string|null $multicast_id Set from FCM response of send attempt. The existence of multicast_id indicates that attempt was successfully made. Fill from api_response
 * @property int|null $success_count Fill from api_response
 * @property int|null $failure_count Fill from api_response
 * @property int|null $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mainframe\Features\Audit\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Change[] $changes
 * @property-read int|null $changes_count
 * @property-read \App\User|null $creator
 * @property-read mixed $api_response_json
 * @property-read mixed $data_json
 * @property-read \App\InAppNotification|null $inAppNotification
 * @property-read \App\Module $linkedModule
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @property-read \App\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant|null $tenant
 * @property-read \App\User|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereApiResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereFailureCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereInAppNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereMulticastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereNameExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereSuccessCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotification whereUuid($value)
 * @mixin \Eloquent
 */
class PushNotification extends BaseModule
{
    use PushNotificationTrait, PushNotificationHelper;

    protected $moduleName = 'push-notifications';
    protected $table = 'push_notifications';
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'project_id',
        'tenant_id',
        'uuid',
        'name',
        'is_active',
    ];

    // protected $guarded = [];
    // protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    // protected $casts = [];
    // protected $with = [];
    // protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | Option values
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
        self::observe(PushNotificationObserver::class);

        // static::saving(function (PushNotification $element) { });
        // static::creating(function (PushNotification $element) { });
        // static::updating(function (PushNotification $element) { });
        // static::created(function (PushNotification $element) { });
        // static::updated(function (PushNotification $element) { });
        // static::saved(function (PushNotification $element) { });
        // static::deleting(function (PushNotification $element) { });
        // static::deleted(function (PushNotification $element) { });
    }

    /*
    |--------------------------------------------------------------------------
    | Section: Query scopes + Dynamic scopes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Section: Accessors
    |--------------------------------------------------------------------------
    */
    // public function getFirstNameAttribute($value) { return ucfirst($value); }

    /*
    |--------------------------------------------------------------------------
    | Section: Mutators
    |--------------------------------------------------------------------------
    */
    // public function setFirstNameAttribute($value) { $this->attributes['first_name'] = strtolower($value); }

    /*
    |--------------------------------------------------------------------------
    | Section: Attributes
    |--------------------------------------------------------------------------
    */
    // public function getUrlAttribute(){return asset($this->path); }

    /*
    |--------------------------------------------------------------------------
    | Section: Relations
    |--------------------------------------------------------------------------
    */
    // public function updater() { return $this->belongsTo(\App\User::class, 'updated_by'); }

    /*
    |--------------------------------------------------------------------------
    | Section: Helpers
    |--------------------------------------------------------------------------
    */
    /**
     * Alias method to get the processor
     *
     * @return PushNotificationProcessor
     * @noinspection SenselessProxyMethodInspection
     */
    public function processor()
    {
        return parent::processor();
    }

}