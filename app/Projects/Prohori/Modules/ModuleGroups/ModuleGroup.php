<?php

namespace App\Projects\Prohori\Modules\ModuleGroups;

use App\Mainframe\Modules\ModuleGroups\Traits\ModuleGroupTrait;
use App\Projects\Prohori\Features\Modular\BaseModule\BaseModule;

/**
 * App\Projects\Prohori\Modules\ModuleGroups\ModuleGroup
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $name
 * @property string|null $name_ext
 * @property string|null $slug
 * @property string|null $title
 * @property string|null $description
 * @property string|null $route_path
 * @property string|null $route_name
 * @property int|null $parent_id
 * @property int|null $level
 * @property int|null $order
 * @property string|null $default_route
 * @property string|null $color_css
 * @property string|null $icon_css
 * @property int|null $is_visible
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
 * @property-read \App\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant $tenant
 * @property-read \App\User|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereColorCss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereDefaultRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereIconCss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereNameExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereRoutePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleGroup whereUuid($value)
 * @mixin \Eloquent
 */
class ModuleGroup extends BaseModule
{
    // Note: Pull in necessary traits from relevant mainframe class
    use ModuleGroupTrait, ModuleGroupHelper;

    protected $moduleName = 'module-groups';
    protected $table = 'module_groups';
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
        self::observe(ModuleGroupObserver::class);

        // static::saving(function (ModuleGroup $element) { });
        // static::creating(function (ModuleGroup $element) { });
        // static::updating(function (ModuleGroup $element) { });
        // static::created(function (ModuleGroup $element) { });
        // static::updated(function (ModuleGroup $element) { });
        // static::saved(function (ModuleGroup $element) { });
        // static::deleting(function (ModuleGroup $element) { });
        // static::deleted(function (ModuleGroup $element) { });
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
     * @return ModuleGroupProcessor
     * @noinspection SenselessProxyMethodInspection
     */
    public function processor()
    {
        return parent::processor();
    }

}