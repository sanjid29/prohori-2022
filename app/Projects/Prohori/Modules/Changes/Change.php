<?php

namespace App\Projects\Prohori\Modules\Changes;

use App\Mainframe\Modules\Changes\Traits\ChangeTrait;
use App\Projects\Prohori\Features\Modular\BaseModule\BaseModule;

/**
 * App\Projects\Prohori\Modules\Changes\Change
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $project_id
 * @property int|null $tenant_id
 * @property string|null $name
 * @property string|null $name_ext
 * @property string|null $slug
 * @property int|null $changeable_id
 * @property string|null $changeable_type
 * @property int|null $module_id
 * @property int|null $element_id
 * @property string|null $element_uuid
 * @property string|null $field
 * @property string|null $old
 * @property string|null $new
 * @property string|null $note
 * @property int|null $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mainframe\Features\Audit\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $changeable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Change[] $changes
 * @property-read int|null $changes_count
 * @property-read \App\User|null $creator
 * @property-read \App\Module|null $linkedModule
 * @property-read \App\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant|null $tenant
 * @property-read \App\User|null $updater
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Change newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Change newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Change query()
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereChangeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereChangeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereElementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereElementUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereNameExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Change whereUuid($value)
 * @mixin \Eloquent
 */
class Change extends BaseModule
{
    // Note: Pull in necessary traits from relevant mainframe class
    use ChangeTrait, ChangeHelper;

    protected $moduleName = 'changes';
    protected $table = 'changes';
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
        self::observe(ChangeObserver::class);

        // static::saving(function (Change $element) { });
        // static::creating(function (Change $element) { });
        // static::updating(function (Change $element) { });
        // static::created(function (Change $element) { });
        // static::updated(function (Change $element) { });
        // static::saved(function (Change $element) { });
        // static::deleting(function (Change $element) { });
        // static::deleted(function (Change $element) { });
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
     * @return ChangeProcessor
     * @noinspection SenselessProxyMethodInspection
     */
    public function processor()
    {
        return parent::processor();
    }

}