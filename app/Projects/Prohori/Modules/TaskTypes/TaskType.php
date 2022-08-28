<?php

namespace App\Projects\Prohori\Modules\TaskTypes;

use App\Projects\Prohori\Features\Modular\BaseModule\BaseModule;

class TaskType extends BaseModule
{
    // Note: Pull in necessary traits from relevant mainframe class
    use TaskTypeHelper;

    protected $moduleName = 'task-types';
    protected $table = 'task_types';
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
    // protected $with = []; // Note: Should be left empty! and used only when needed : $model->append(...)!
    // protected $appends = []; // Note: Should be left empty! and used only when needed : $model->load(...)!

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
        self::observe(TaskTypeObserver::class);

        // static::saving(function (TaskType $element) { });
        // static::creating(function (TaskType $element) { });
        // static::updating(function (TaskType $element) { });
        // static::created(function (TaskType $element) { });
        // static::updated(function (TaskType $element) { });
        // static::saved(function (TaskType $element) { });
        // static::deleting(function (TaskType $element) { });
        // static::deleted(function (TaskType $element) { });
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
     * @return TaskTypeProcessor
     * @noinspection SenselessProxyMethodInspection
     */
    public function processor()
    {
        return parent::processor();
    }

}