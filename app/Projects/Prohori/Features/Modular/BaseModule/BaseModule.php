<?php

namespace App\Projects\Prohori\Features\Modular\BaseModule;

use App\Mainframe\Features\Core\Traits\Validable;
use App\Mainframe\Features\Modular\BaseModule\Traits\ModularTrait;
use App\Mainframe\Features\Multitenant\GlobalScope\CheckTenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Watson\Rememberable\Rememberable;

/**
 * App\Projects\Prohori\Features\Modular\BaseModule\BaseModule
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Change[] $changes
 * @property-read int|null $changes_count
 * @property-read \App\User $creator
 * @property-read \App\Module $linkedModule
 * @property-read \App\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spread[] $spreads
 * @property-read int|null $spreads_count
 * @property-read \App\Tenant $tenant
 * @property-read \App\User $updater
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModule query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|BaseModule onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|BaseModule withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BaseModule withoutTrashed()
 */
class BaseModule extends Model implements Auditable
{
    /*
    |--------------------------------------------------------------------------
    | Include Mainframe module traits
    |--------------------------------------------------------------------------
    */
    use SoftDeletes,                // Laravel default trait to enable soft delete
        Rememberable,               // Third party plugin to cache model query
        \OwenIt\Auditing\Auditable, // 3rd party audit log
        ModularTrait,               // Mainframe modular features.
        Validable                   // Allow validation
        ;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = ''; // Note: demo module name to create ide-helper doc block

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    /**
     * Date fields
     *
     * @var string[]
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Hidden in serialized output/json
     *
     * @var string[]
     */
    protected $hidden = ['project_id', 'tenant_id', 'slug', 'deleted_by', 'deleted_at'];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = ['updated_at',];

    /*
    |--------------------------------------------------------------------------
    | Code: Spread configs
    |--------------------------------------------------------------------------
    */

    /**
     * Define the spread attribute mapping that link to a another model.
     * Note: Table field must follow *_model_ids, i.e. visited_country_ids, active_group_ids
     *
     * @var array
     */
    protected $spreadFields = [
        // 'group_ids' => Group::class,
    ];

    /**
     * Define the tag attributes of the model that will be saved in spreads table.
     *
     * @var array
     */
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
    | Boot method and model events.
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

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
    }

}