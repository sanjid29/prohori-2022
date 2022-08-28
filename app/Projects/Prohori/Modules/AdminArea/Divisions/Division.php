<?php

namespace App\Projects\Prohori\Modules\AdminArea\Divisions;

use App\Projects\Prohori\Features\Modular\BaseModule\BaseModule;

class Division extends BaseModule
{
    // Note: Pull in necessary traits from relevant mainframe class
    use DivisionHelper;

    protected $moduleName = 'divisions';
    protected $table = 'divisions';
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
        self::observe(DivisionObserver::class);

        // static::saving(function (Division $element) { });
        // static::creating(function (Division $element) { });
        // static::updating(function (Division $element) { });
        // static::created(function (Division $element) { });
        // static::updated(function (Division $element) { });
        // static::saved(function (Division $element) { });
        // static::deleting(function (Division $element) { });
        // static::deleted(function (Division $element) { });
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
     * @return DivisionProcessor
     * @noinspection SenselessProxyMethodInspection
     */
    public function processor()
    {
        return parent::processor();
    }

}
