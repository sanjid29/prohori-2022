<?php

namespace App\Mainframe\Features\Form\Select;

use App\Mainframe\Features\Modular\BaseModule\BaseModule;
use App\Module;
use Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SelectModel extends SelectArray
{
    public $nameField;
    public $valueField;
    public $orderBy;
    public $table;

    /** @var BaseModule|null */
    public $model;
    public $query;
    public $result;
    public $showInactive;
    public $cache = 5;
    public $dataAttributes;

    /**
     * SelectModel constructor.
     *
     * @param  array  $var
     * @param  null  $element
     */
    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);

        $this->nameField = $this->var['name_field'] ?? 'name';
        $this->valueField = $this->var['value_field'] ?? 'id';
        $this->orderBy = $this->var['order_by'] ?? $this->nameField;

        $this->table = $this->var['table'] ?? null; // Must have table
        $this->model = $this->var['model'] ?? null; // Must have table
        $this->setModel();

        $this->query = $this->getQuery(); // DB::table($this->table);
        $this->showInactive = $this->var['show_inactive'] ?? false;
        $this->cache = $this->var['cache'] ?? $this->cache;
        $this->dataAttributes = $this->var['data_attributes'] ?? [];

        $this->options = $this->options();
    }

    public function setModel()
    {
        if (isset($this->var['model'])) {
            $model = $this->var['model'];
            if (is_string($model)) {
                $model = new $model;
            }
            $this->model = $model;
        }

        if (isset($this->var['table'])) {
            $table = $this->var['table'];
            if ($module = Module::fromTable($table)) {
                $this->model = $module->modelInstance();
            }
        }

        return $this;
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        return $this->var['query'] ?? $this->model;
    }

    /**
     * Query on columns
     *
     * @return array
     */
    public function columns()
    {

        $columns = [$this->nameField, $this->valueField];

        foreach ($this->dataAttributes as $attribute) {

            // Include in columns if the attribute exists
            if ($this->model->hasColumn($attribute)) {
                $columns[] = $attribute;
            }
        }

        return $columns;
    }

    /**
     * Get a query result with all necessary fields
     *
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function result()
    {
        if ($this->result) {
            return $this->result;
        }

        $q = $this->query
            ->select($this->columns())
            ->whereNull('deleted_at');

        if (!$this->showInactive) {
            $q->where('is_active', 1);
        }

        // Inject tenant context.
        if ($this->inTenantContext()) {
            $q->where(function ($q) {
                /** @var Builder $q */
                $q->where('tenant_id', user()->tenant_id)->orWhereNull('tenant_id');
            });
        }

        $q->orderBy($this->orderBy);

        $this->result = Cache::remember($this->cacheKey(), $this->cache, function () use ($q) {
            return $q->get();
        });

        return $this->result;
    }

    public function cacheKey()
    {
        return 'select-'.$this->name.'-'.user()->id;
    }

    /**
     * Select options
     *
     * @return array
     */
    public function options()
    {
        $options = $this->result()
            ->pluck($this->nameField, $this->valueField)
            ->toArray();

        // $options[0] = null; // Zero fill empty selection
        if (!$this->isMultiple()) {
            $options[null] = $this->nullOptionText;  // Null fill empty selection
        }

        return Arr::sort($options);
    }

    /**
     * Check if currently in tenant context.
     *
     * @return bool
     */
    public function inTenantContext()
    {
        return user()->ofTenant() && isset($this->model) && $this->model->hasTenantContext();
    }

    /**
     * Print value
     *
     * @return null|array|\Illuminate\Http\Request|string
     */
    public function print()
    {
        return $this->options()[$this->value()] ?? '';
    }

}