<?php

namespace App\Projects\DefaultProject\Modules\Uploads;

use App\Mainframe\Modules\Uploads\Traits\UploadTrait;
use App\Projects\DefaultProject\Features\Modular\BaseModule\BaseModule;

/**
 * App\Projects\DefaultProject\Modules\Uploads\Upload
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $project_id
 * @property int|null $tenant_id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $path
 * @property int|null $order
 * @property string|null $ext
 * @property int|null $bytes
 * @property string|null $description
 * @property string|null $uploadable_type
 * @property int|null $uploadable_id
 * @property int|null $module_id
 * @property int|null $element_id
 * @property string|null $element_uuid
 * @property int|null $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Change[] $changes
 * @property-read int|null $changes_count
 * @property-read \App\User|null $creator
 * @property-read mixed $dir
 * @property-read mixed $url
 * @property-read \App\Module|null $linkedModule
 * @property-read \App\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant|null $tenant
 * @property-read \App\User|null $updater
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $uploadable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereElementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereElementUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUploadableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUploadableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUuid($value)
 * @mixin \Eloquent
 * @property string|null $name_ext
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereNameExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereSlug($value)
 */
class Upload extends BaseModule
{
    use UploadTrait, UploadHelper;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    // protected $guarded = [];
    // protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    // protected $casts = [];
    // protected $with = [];
    protected $moduleName = 'uploads';
    protected $table = 'uploads';

    /*
    |--------------------------------------------------------------------------
    | Option values
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'project_id',
        'tenant_id',
        'uuid',
        'name',
        'type',
        'path',
        'order',
        'ext',
        'bytes',
        'description',
        'module_id',
        'element_id',
        'element_uuid',
        'uploadable_id',
        'uploadable_type',
        'is_active',
    ];
    // protected $appends = ['url', 'dir'];
    protected $hidden = ['linked_module'];

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    |
    */
    /**
     * Upload type options
     */
    public const TYPE_GENERIC = 'Generic';
    public const TYPE_PROFILE_PIC = 'Profile Picture';
    public const TYPE_LOGO = 'Logo';

    public static $types = [
        self::TYPE_GENERIC,
        self::TYPE_PROFILE_PIC,
        self::TYPE_LOGO,
    ];

    /**
     * Keeps only the latest file and deletes old.
     * For cases like profile pic
     *
     * @var string[]
     */
    public static $typesWithSingleImage = [
        self::TYPE_PROFILE_PIC,
        self::TYPE_LOGO,
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot method and model events.
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
        self::observe(UploadObserver::class);

        // static::saving(function (Upload $element) { });
        // static::creating(function (Upload $element) { });
        // static::updating(function (Upload $element) { });
        // static::created(function (Upload $element) { });
        // static::updated(function (Upload $element) { });
        // static::saved(function (Upload $element) { });
        // static::deleting(function (Upload $element) { });
        // static::deleted(function (Upload $element) { });
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

}