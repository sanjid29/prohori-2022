<?php

namespace App\Projects\DefaultProject\Modules\Tenants;

use App\Mainframe\Modules\Tenants\Traits\TenantTrait;
use App\Projects\DefaultProject\Features\Modular\BaseModule\BaseModule;

/**
 * App\Projects\DefaultProject\Modules\Tenants\Tenant
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $project_id
 * @property string|null $name
 * @property string|null $name_ext
 * @property string|null $slug
 * @property string|null $code
 * @property int|null $user_id Tenant admin who signed up
 * @property string|null $route_group
 * @property string|null $class_directory
 * @property string|null $namespace
 * @property string|null $view_directory
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
 * @property-read \App\Module $linkedModule
 * @property-read \App\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant $tenant
 * @property-read \App\User|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereClassDirectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereNameExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereNamespace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereRouteGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereViewDirectory($value)
 * @mixin \Eloquent
 */
class Tenant extends BaseModule
{
    use TenantTrait, TenantHelper;

    public const GLOBAL_TENANT_ID = 0; // These elements are accessible by all tenant
    public const NON_TENANT_ID = null; // Only accessible by admin/non-tenant user

    protected $moduleName = 'tenants';
    protected $table = 'tenants';
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
        self::observe(TenantObserver::class);

        // static::saving(function (Tenant $element) { });
        // static::creating(function (Tenant $element) { });
        // static::updating(function (Tenant $element) { });
        // static::created(function (Tenant $element) { });
        // static::updated(function (Tenant $element) { });
        // static::saved(function (Tenant $element) { });
        // static::deleting(function (Tenant $element) { });
        // static::deleted(function (Tenant $element) { });
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
     * @return TenantProcessor
     * @noinspection SenselessProxyMethodInspection
     */
    public function processor()
    {
        return parent::processor();
    }

}