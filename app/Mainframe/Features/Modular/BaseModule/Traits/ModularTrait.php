<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Mainframe\Features\Modular\BaseModule\Traits;

use App\Change;
use App\Mainframe\Features\Core\ViewProcessor;
use App\Mainframe\Features\Modular\BaseModule\BaseModule;
use App\Mainframe\Helpers\Mf;
use App\Mainframe\Modules\Modules\ModuleViewProcessor;
use App\Module;
use App\Project;
use App\Spread;
use App\Tenant;
use App\Upload;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Builder;

/** @mixin BaseModule $this */
trait ModularTrait
{

    /*
    |--------------------------------------------------------------------------
    | Section: Default getters
    |--------------------------------------------------------------------------
    */
    public function moduleName()
    {
        return $this->moduleName ?? null;
    }

    public function tenantEnabled()
    {
        return $this->tenantEnabled ?? false;
    }

    public function spreadFields()
    {
        return $this->spreadFields ?? [];
    }

    public function getTagFields()
    {
        return $this->tagFields ?? [];
    }

    public function showGlobalTenantElements()
    {
        return $this->showGlobalTenantElements ?? false;
    }

    public function showNonTenantElements()
    {
        return $this->showNonTenantElements ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes + Dynamic scopes
    |--------------------------------------------------------------------------
    |
    | Scopes allow you to easily re-use query logic in your models. To define
    | a scope, simply prefix a model method with scope:
    */
    /**
     * @param $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.is_active', 1);
    }


    /*
    |--------------------------------------------------------------------------
    | Module features
    |--------------------------------------------------------------------------
    |
    */
    /**
     * Get the module of element
     *
     * @return Module
     */
    public function module()
    {
        return Module::byName($this->moduleName);
    }

    /**
     * Check if a model has a given attribute
     *
     * @param $attribute
     * @return bool
     */
    public function hasAttribute($attribute)
    {
        return array_key_exists($attribute, $this->getAttributes());
    }

    /**
     * Shorthand function for getAttributeKeysExcept.
     * Gets all attribute names
     *
     * @param  array  $except
     * @return array|bool|mixed
     */
    public function fields($except = [])
    {
        return $this->getAttributeKeysExcept($except);
    }

    /**
     * Get all attribute names
     *
     * @return array|bool
     */
    public function getAttributeKeys()
    {
        return array_keys($this->getAttributes());
    }

    /**
     * Get attribute except
     *
     * @param  array  $except
     * @return array|bool
     */
    public function getAttributeKeysExcept($except = [])
    {
        return collect($this->getAttributes())->except($except)->keys()->toArray();
    }

    /**
     * Check if a model table has a given column
     *
     * @param  string  $column
     * @return bool
     */
    public function hasColumn($column)
    {
        return in_array($column, $this->tableColumns());
    }

    /**
     * Get an array of columns that ends with the given string
     *
     * @param $str
     * @return array
     */
    public function getColumnsThatEndsWith($str)
    {
        $found = [];
        $columns = $this->tableColumns();
        foreach ($columns as $column) {
            if (\Str::endsWith($str, $column)) {
                $found[] = $column;
            }
        }
        return $found;
    }

    /**
     * Get all the table columns of the model
     *
     * @return array
     */
    public function tableColumns()
    {
        return Mf::tableColumns($this->getTable());
    }

    /**
     * Get the $append value
     *
     * @return array
     */
    public function getAppends()
    {
        return $this->appends;
    }

    /**
     * Get the $with value
     *
     * @return array
     */
    public function getWith()
    {
        return $this->with;
    }

    /*
    |--------------------------------------------------------------------------
    | Changes and value transitions
    |--------------------------------------------------------------------------
    */

    /**
     * Get latest changes
     * http://www.laravel-auditing.com/docs/9.0/getting-audits
     *
     * @return mixed
     */
    public function latestChanges()
    {
        return $this->audits()->latest()->first()->getModified();
    }

    /**
     * Check if the value of a field has changed
     *
     * @param $field
     * @return bool
     */
    public function fieldHasChanged($field)
    {
        if (array_key_exists($field, $this->getChanges())) {
            return true; // This only works inside boot::saved()
        }

        if (($this->isUpdating() && isset($this->$field)) && $this->getOriginal($field) != $this->$field) {
            return true;
        }

        return false;
    }

    /**
     * Get the last updater user of a field
     *
     * @param  string  $field
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|null
     */
    public function updaterOfField($field)
    {
        $audits = $this->audits()->latest()->get();

        $userId = null;

        foreach ($audits as $audit) {
            $userId = $audit->user_id;
            $changes = $audit->getModified();
            if (array_key_exists($field, $changes)) {
                break;
            }
        }

        if ($userId) {
            return User::remember(timer('long'))->find($userId);
        }

        return $this->creator;
    }

    /**
     * Get old and new value of a changed field
     *
     * @param $field
     * @return array
     */
    public function transition($field)
    {
        if ($this->fieldHasChanged($field)) {
            return [
                'field' => $field,
                'old' => $this->getOriginal($field),
                'new' => $this->$field,
            ];
        }

        return null;
    }

    /**
     * Check if a certain transition took place.
     *
     * @param  string  $field
     * @param  string|array  $from
     * @param  string|array  $to
     * @return bool
     */
    public function hasTransition($field, $from, $to)
    {
        if (!is_array($from)) {
            $from = [$from];
        }

        if (!is_array($to)) {
            $to = [$to];
        }

        $change = $this->transition($field);

        if ($change) {
            return in_array($change['old'], $from) && in_array($change['new'], $to);
        }
    }

    /**
     * Check if a certain transition took place.
     *
     * @param  string  $field
     * @param  string|array  $from
     * @return bool
     */
    public function hasTransitionFrom($field, $from)
    {

        if (!is_array($from)) {
            $from = [$from];
        }

        $change = $this->transition($field);

        if ($change) {
            return in_array($change['old'], $from);
        }
    }

    /**
     * Check if a certain transition took place.
     *
     * @param  string  $field
     * @param  array|string  $to
     * @return bool
     */
    public function hasTransitionTo($field, $to)
    {

        if (!is_array($to)) {
            $to = [$to];
        }

        $change = $this->transition($field);

        if ($change) {
            return in_array($change['new'], $to);
        }
    }

    /**
     * Get an array of allowed next transition values
     *
     * @param $field
     * @param  null  $from
     * @return array
     */
    public function allowedTransitionsOf($field, $from = null)
    {
        $from = $from ?: $this->$field; // from current value

        return $this->processor()->allowedTransitionsOf($field, $from);
    }

    /*
    |--------------------------------------------------------------------------
    | Field specific change tracking using 'changes' module
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Store tracked changes in changes table
     *
     * @return $this
     */
    public function trackFieldChanges()
    {
        $fields = $this->processor()->getTrackedFields();

        // dd($this->getChanges());
        foreach ($fields as $field) {
            if ($this->fieldHasChanged($field)) {
                $transition = $this->transition($field);
                $this->changes()->create([
                    'module_id' => $this->module()->id,
                    'element_id' => $this->id,
                    'element_uuid' => $this->uuid,
                    'field' => $field,
                    'old' => $transition['old'],
                    'new' => $transition['new'],
                ]);
            }
        }

        return $this;
    }

    /**
     * Get a change from a tracked field.
     *
     * @param $field
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function track($field)
    {
        return $this->changes()->where('field', $field);
    }

    /*
    |--------------------------------------------------------------------------
    | User related functions
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Returns array of user ids including creator and updater user ids.
     * This can be overridden in different modules as per business.
     *
     * @return array
     */
    public function relatedUserIds()
    {
        $userIds = []; // Init array to store all user ids
        if (isset($this->creator->id)) {
            $userIds[] = $this->creator->id;
        }
        //get the creator
        //if the creator and updater is same no need to add the id twice
        if (isset($this->updater->id, $this->creator->id)
            && $this->creator->id !== $this->updater->id) {
            $userIds[] = $this->updater->id;
        } //get the updater

        return $userIds;
    }

    /*
    |--------------------------------------------------------------------------
    | Tenants & Project related functions
    |--------------------------------------------------------------------------
    */

    /**
     * Checks if a user has tenant context
     *
     * @return bool
     * @internal param $name
     */
    public function hasTenantContext()
    {
        return $this->hasColumn('tenant_id') && $this->tenantEnabled;
    }

    /**
     * Check if the element is compatible with the user's tenant
     *
     * @param  null  $user
     * @return bool
     */
    public function isTenantCompatible($user = null)
    {
        $user = $user ?: user();

        if (!$this->hasTenantContext()) {
            return true;
        }

        // If the element has null tenant then it is a global element and should be accessible by all tenant
        if ($this->tenant_id == null) {
            return true;
        }

        if (($user->tenant_id) && ($this->tenant_id != $user->tenant_id)) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    |
    */
    /**
     * Check if the model is being created.
     *
     * @return bool
     */
    public function isCreating()
    {
        return !$this->isUpdating();
    }

    /**
     * Check if the model is being updated.
     *
     * @return bool
     */
    public function isUpdating()
    {
        return $this->isEditing();
    }

    /**
     * Check if the model is being updated.
     *
     * @return bool
     */
    public function isEditing()
    {
        return isset($this->id);
    }

    /**
     * @return bool
     */
    public function isCreated()
    {
        return $this->isUpdating();
    }

    /**
     * Disable model events. Useful for avoiding infinite loop scenario.
     *
     * @return $this
     */
    public function disableEvents()
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->module()->model;
        $model::unsetEventDispatcher();

        return $this;
    }

    /**
     * Disable model events while saving.
     *
     * @param  array  $options
     * @return mixed
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Processor related functions
    |--------------------------------------------------------------------------
    |
    */
    /**
     * Get the processor for this element
     *
     * @return mixed|\App\Mainframe\Features\Modular\Validator\ModelProcessor
     */
    public function processor()
    {
        return $this->module()->processorInstance($this);
    }

    /**
     * Shorthand function for processor
     *
     * @return \App\Mainframe\Features\Modular\Validator\ModelProcessor|mixed
     */
    public function process()
    {
        return $this->processor();
    }

    /**
     * Get a processed element after running the processor logic.
     * This element may not be fully valid.
     *
     * @return $this
     */
    public function processed() // Todo: Need to reevaluate this functions purpose.
    {
        return $this->process()->forSave()->element;
    }

    /**
     * Get a valid element.
     *
     * @return bool
     */
    public function validate() // Todo: Need to reevaluate this functions purpose.
    {
        return $this->module()->processorInstance($this)->forSave()->isValid();
    }

    /*
    |--------------------------------------------------------------------------
    | Uploadable and upload related
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Link existing uploads with this element
     *
     * @return $this
     */
    public function linkUploads()
    {
        /** @var Module $this */
        Upload::linkTemporaryUploads($this);

        return $this;
    }

    /**
     * Store values in the database for key related to another model
     */
    public function syncSpreadKeys()
    {

        foreach ($this->spreadFields() as $field => $relatedTo) {

            $ids = toArray($this->$field);

            if (empty($ids)) {
                $this->spreads()->where('key', $field)->forceDelete();
                continue;
            }

            $this->spreads()->where('key', $field)->whereNotIn('related_id', $ids)->forceDelete();

            $existingIds = $this->spreads()->where('key', $field)->pluck('related_id');
            $newIds = collect($ids)->diff($existingIds)->all();

            $name = $this->getTable();

            foreach ($newIds as $relatedId) {

                if (!$relatedId) {
                    continue;
                }
                $spread = [
                    'name' => $name,
                    'key' => $field,
                    'module_id' => $this->module()->id,
                    'element_id' => $this->id,
                    'element_uuid' => $this->uuid,
                    'relates_to' => $relatedTo,
                    'related_id' => $relatedId,
                ];

                $this->spreads()->create($spread);
            }

        }

        return $this;
    }

    /**
     * Store values in the database for key related to another model
     */
    public function syncSpreadTags()
    {

        foreach ($this->getTagFields() as $field) {

            $tags = cleanArray(toArray($this->$field));

            if (empty($tags)) {
                $this->spreads()->where('key', $field)->forceDelete();
                continue;
            }

            $this->spreads()->where('key', $field)->whereNotIn('tag', $tags)->forceDelete();

            $existingTags = $this->spreads()->where('key', $field)->pluck('tag');
            $newTags = collect($tags)->diff($existingTags)->all();

            $name = $this->getTable();

            foreach ($newTags as $tag) {

                $spread = [
                    'name' => $name,
                    'key' => $field,
                    'module_id' => $this->module()->id,
                    'element_id' => $this->id,
                    'element_uuid' => $this->uuid,
                    'tag' => $tag,
                ];

                $this->spreads()->create($spread);
            }

        }

        return $this;
    }

    public function getSpreadTags($field)
    {
        return $this->spreadTags($field)->pluck('tag')->toArray();
    }

    /**
     * Auto fill some of the generic model fields.
     */
    public function autoFill()
    {

        // $this->autoFillTenant();         // Alert: Not needed inject from 'tenant' middleware

        $this->uuid = $this->uuid ?? uuid();
        $this->created_by = $this->created_by ?? user()->id;
        $this->created_at = $this->created_at ?? now();
        $this->updated_by = $this->updated_by ?? user()->id;
        $this->updated_at = now();
        $this->autoFillTenant();

        return $this;
    }

    /**
     * Fill tenant id once during creation. Later tenant id can not be
     * updated.
     */
    public function autoFillTenant()
    {
        if ($this->hasTenantContext()) {
            $this->tenant_id = $this->tenant_id ?: user()->tenant_id;
            // $this->project_id = $this->project_id ?: $this->tenant->project_id; // Excluded project_id injection because settings table had no project_id
        }

        return $this;
    }

    /**
     * Mark an entry as deleted by setting the deleted_at, deleted_by
     *
     * @param  null  $by
     * @param  null  $at
     * @return $this
     */
    public function markDeleted($by = null, $at = null)
    {

        $by = $by ?: user()->id;
        $at = $at ?: now();

        if (isset($this->id) && $this->deleted_by == null) {
            DB::table($this->getTable())->where('id', $this->id)
                ->update(['deleted_by' => $by, 'deleted_at' => $at]);
        }

        return $this;
    }

    /**
     * Fill polymorphic fields.
     * Note: This function should be used in polymorphic model's boot::creating() method.
     *
     * @param  string  $fieldPrefix  i.e.uploadable
     * @return $this
     */
    public function fillModuleAndElement($fieldPrefix)
    {

        // Define polymorphic field names
        $idField = $fieldPrefix.'_id';  // uploadable_id
        $typeField = $fieldPrefix.'_type'; // uploadable_type

        /*---------------------------------------------------------------------------------
        | Case 1. uploadable_type, uploadable_id available from default laravel poly-morph
        | Fill : module_id, element_id, element_uuid
        |---------------------------------------------------------------------------------*/
        if (isset($this->$typeField, $this->$idField)) {
            $this->$typeField = 'App\\'.class_basename($this->$typeField); // Change to root model \App\Upload
            $this->element_id = $this->$idField;

            $linkedModule = Module::byClass($this->$typeField);
            $this->module_id = $linkedModule->id;

            $linkedElement = $linkedModule->modelInstance()->remember(timer('very-long'))
                ->find($this->element_id);
            $this->element_uuid = optional($linkedElement)->uuid;

            return $this;

        }

        /*-----------------------------------------------------------------
        | Case 2. module_id, element_id is available from MF implementation
        | Fill : uploadable_type, uploadable_name, element_uuid
        |-----------------------------------------------------------------*/
        if (isset($this->module_id, $this->element_id)) {

            $linkedModule = $this->linkedModule; // linked based on module_id (i.e. users module)
            $linkedElement = $linkedModule->modelInstance()->remember(timer('very-long'))
                ->find($this->element_id);

            $this->$typeField = trim($linkedModule->rootModelClassPath(), '\\');
            $this->$idField = optional($linkedElement)->id;
            $this->element_uuid = optional($linkedElement)->uuid;

            return $this;
        }

        return $this;
    }

    /**
     * Get an instance of the view processor
     *
     * @return ModuleViewProcessor
     */
    public function viewProcessor()
    {
        $module = $this->module();
        $modelClassPath = $module->modelClassPath();

        $classPaths = [

            // Step 1: Check in same folder
            $modelClassPath.'View',
            $modelClassPath.'ViewProcessor',

            // Step 2: Check in module directory
            $module->namespace.'\\'.$module->modelClassName().'View',
            $module->namespace.'\\'.$module->modelClassName().'ViewProcessor',

            // Step 3: Check project default
            projectNamespace().'\Features\Modular\BaseModule\BaseModuleView',
            projectNamespace().'\Features\Modular\BaseModule\BaseModuleViewProcessor',

            // Step 4: Fallback to mainframe
            '\App\Mainframe\Features\Modular\BaseModule\BaseModuleView',
            '\App\Mainframe\Features\Modular\BaseModule\BaseModuleViewProcessor',
        ];

        foreach ($classPaths as $classPath) {
            if (class_exists($classPath)) {
                /** @var ViewProcessor $view */
                $view = new $classPath;

                /** @var BaseModule $this */
                return $view->setModel($this)->setModule($module);
            }
        }

        return null;

    }

    /**
     * Index page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function indexUrl($params = [])
    {
        return route($this->module()->route_name.'.index', $params);
    }

    /**
     * Create page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function createUrl($params = [])
    {
        return route($this->module()->route_name.'.create', $params);
    }

    /**
     * Store request url. Note: This URL is used for form POST
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function storeUrl($params = [])
    {
        return route($this->module()->route_name.'.store', $params);
    }

    /**
     * Show details page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function showUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['element' => $this], $params);
            return route($this->module()->route_name.'.show', $params);
        }

        return null;
    }

    /**
     * Edit page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function editUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['element' => $this], $params);
            return route($this->module()->route_name.'.edit', $params);
        }

        return null;
    }

    /**
     * update request url. Note: This URL is used for form POST
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function updateUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['element' => $this], $params);
            return route($this->module()->route_name.'.update', $params);
        }

        return null;
    }

    /**
     * Delete/Destroy request url. Note: This URL is used for form POST
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function destroyUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['element' => $this], $params);
            return route($this->module()->route_name.'.destroy', $params);
        }

        return null;
    }

    /**
     * Datatable JSON url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function datatableJsonUrl($params = [])
    {
        return route($this->module()->route_name.'.datatable-json', $params);
    }

    /**
     * List JSON url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function listJsonUrl($params = [])
    {
        return route($this->module()->route_name.'.list-json', $params);
    }

    /**
     * Report page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function reportUrl($params = [])
    {
        return route($this->module()->route_name.'.report', $params);
    }

    /**
     * Uploads page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function uploadsUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['id' => $this], $params);
            return route($this->module()->route_name.'.uploads', $params);
        }

        return null;
    }

    /**
     * Index page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function changesUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['id' => $this], $params);
            return route($this->module()->route_name.'.changes', $params);
        }

        return null;
    }

    /**
     * Index page url
     *
     * @param  array  $params  Pass additional url params
     * @return string
     */
    public function cloneUrl($params = [])
    {
        if ($this->isCreated()) {
            $params = array_merge(['id' => $this], $params);
            return route($this->module()->route_name.'.clone', $params);
        }

        return null;
    }

    /**
     * HTML link
     *
     * @param  string  $field
     * @param  array  $params
     * @return string
     */
    public function editLink($field = 'id', $params = [])
    {
        return "<a href='".$this->editUrl($params)."'>".($this->$field ?? $field)."</a>";
    }



    /*
    |--------------------------------------------------------------------------
    | Ability to create, edit, delete or restore
    |--------------------------------------------------------------------------
    |
    | An element can be editable or non-editable based on its internal status
    | This is not related to any user, rather it is a model's individual sate
    | For example - A confirmed quotation should not be editable regardless
    | Of who is attempting to edit it.
    |
    */
    /**
     * Check if the model can be viewed based on its values.
     *
     * @return bool
     */
    public function isViewable()
    {
        return true;
    }

    /**
     * Check if the model can be created based on its values.
     *
     * @return bool
     */
    public function isCreatable()
    {
        return true;
    }

    /**
     * Check if the model can be edited based on its values.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Check if the model can be deleted based on its values.
     *
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }

    /**
     * Check if the model can be restored based on its values.
     *
     * @return bool
     */
    public function isRestorable()
    {
        return true;
    }

    /**
     * Check if the model can be clones based on its values.
     *
     * @return bool
     */
    public function isCloneable()
    {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function linkedModule()
    {
        return $this->belongsTo(Module::class, 'module_id')->remember(timer('very-long'));
    }

    public function changes()
    {
        return $this->hasMany(Change::class, 'element_id')
            ->where('module_id', $this->module()->id);
        // return $this->morphMany('App\Mainframe\Modules\Changes\Change', 'changeable'); Note: Do not use morphMany
    }

    public function uploads()
    {
        // return $this->morphMany(Upload::class,'uploadable');
        return $this->hasMany(Upload::class, 'element_id')->where('module_id', $this->module()->id);
    }

    public function spreads()
    {
        return $this->morphMany(Spread::class, 'spreadable'); // Note: Keep morphMany!!
    }

    public function spreadModels($slug)
    {
        $key = $slug;
        if (!\Str::endsWith($slug, '_ids')) {
            $key = \Str::singular($slug).'_ids';
        }

        $class = $this->spreadFields[$key];

        return $this->belongsToMany($class, 'spreads', 'spreadable_id', 'related_id')
            ->where('key', $key);

    }

    public function spreadTags($field)
    {
        return $this->hasMany(Spread::class, 'element_id')
            ->where('module_id', $this->module()->id)
            ->where('key', $field);
        // return $this->morphMany(Spread::class, 'spreadable')->where('key', $field);
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class, 'element_id')->where('module_id', $this->module()->id);
    // }

    /**
     * For multi-tenancy add a sequence number.
     *
     * @return int|mixed|void|null
     */
    public function putTenantSerial()
    {
        if (!$this->tenant_id) {
            return;
        }
        if ($this->tenant_sl) {
            return;
        }

        $last = DB::table($this->getTable())
            ->where('id', '!=', $this->id)
            ->where('tenant_id', $this->tenant_id)
            ->latest()->first(['tenant_sl']);

        $sl = 1;
        if ($last && $last->tenant_sl) {
            $sl += $last->tenant_sl;
        }

        $this->tenant_sl = $sl;
        $this->saveQuietly();

        return $sl;

    }

    /**
     * This function updates(saves) other tables where changes of this model should reflect
     *
     * @return void
     */
    public function syncData()
    {

    }

    /**
     * This function updates(saves) the denormalize fields with in the same entry
     * for example if in the same module based on client_id it fills other fields
     * like client_name, client_address etc. use setters to set values
     *
     * @return $this
     */
    public function denormalize()
    {

        return $this;

    }

    // /**
    //  * Set name_ext values
    //  *
    //  * @return $this
    //  */
    // public function setNameExt()
    // {
    //     if (!$this->hasColumn('name_ext')) {
    //         return $this;
    //     }
    //
    //     if ($this->hasColumn('name') && $this->hasColumn('name_ext')) {
    //         $this->name_ext = $this->name;
    //     }
    //
    //     if ($this->hasTenantContext() && $this->tenant) {
    //         $this->name_ext .= ' ('.$this->tenant->name.')';
    //     }
    //
    //     return $this;
    // }
    //
    // /**
    //  * Set a slug
    //  *
    //  * @return $this
    //  */
    // public function setSlug()
    // {
    //
    //     if ($this->hasColumn('name') && $this->hasColumn('slug')) {
    //         $this->slug = \Str::slug($this->name);
    //     }
    //
    //     return $this;
    // }

    /**
     * Find by name
     *
     * @param $name
     * @return mixed|Module
     */
    public static function byName($name)
    {
        return self::where('name', $name)->first();
    }

    /**
     * Find by uuid
     *
     * @param $uuid
     * @return mixed|Module
     */
    public static function byUuid($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }

    /**
     * Find by slug
     *
     * @param $slug
     * @return mixed|Module
     */
    public static function bySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Find by code
     *
     * @param $code
     * @return mixed|Module
     */
    public static function byCode($code)
    {
        return self::where('code', $code)->first();
    }

}