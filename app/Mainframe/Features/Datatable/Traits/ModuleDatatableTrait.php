<?php

namespace App\Mainframe\Features\Datatable\Traits;

use App\Mainframe\Features\Datatable\ModuleDatatable;
use App\Mainframe\Features\Modular\BaseModule\BaseModule;
use App\Module;

/** @mixin ModuleDatatable */
trait ModuleDatatableTrait
{

    /**
     * Select columns, alias and corresponding HTML title
     *
     * @return array
     */
    public function columns()
    {
        return [
            [$this->table.'.id', 'id', 'ID'],
            [$this->table.'.name', 'name', 'Name'],
            ['updater.name', 'user_name', 'Updater'],
            [$this->table.'.updated_at', 'updated_at', 'Updated at'],
            [$this->table.'.is_active', 'is_active', 'Active'],
        ];
    }

    public function source()
    {
        return $this->model->leftJoin('users as updater', 'updater.id', $this->table.'.updated_by');
    }

    /**
     * Define Query for generating results for grid
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|mixed
     */
    public function query()
    {
        $query = $this->source()->select($this->selects());

        // Note: If you are not using model based query you need to manually inject tenant context.
        // if (user()->ofTenant() && $this->module->tenantEnabled()) {
        //     $query->where($this->module->tableName().'.tenant_id', user()->tenant_id);
        // }

        // Exclude deleted rows
        $query->whereNull($this->table.'.deleted_at');

        return $this->filter($query);
    }

    /**
     * Modify datatable row values
     *
     * @return mixed
     * @var $dt \Yajra\DataTables\DataTableAbstract
     */
    public function modify($dt)
    {
        // dd($dt->toArray());
        if ($this->hasColumn('name')) {
            // $dt = $dt->editColumn('name', '<a href="{{ route(\''.$this->module->name.'.edit\', $id) }}">{{$name}}</a>');
            $dt = $dt->editColumn('name', function ($row) {
                return '<a href="'.route($this->module->name.'.edit', $row->id).'">'.$row->name.'</a>';
            });
        }

        if ($this->hasColumn('id')) {
            $dt = $dt->editColumn('id', '<a href="{{ route(\''.$this->module->name.'.edit\', $id) }}">{{$id}}</a>');
        }

        if ($this->hasColumn('updated_by')) {
            $dt = $dt->editColumn('updated_by', function ($row) {
                return $row->updater->name ?? $row->updated_by;
            });
        }

        return $dt;
    }

    /**
     * API url
     *
     * @return mixed
     */
    public function ajaxUrl()
    {
        if (!$this->ajaxUrl) {
            $this->ajaxUrl = route($this->module->name.'.datatable-json');
        }

        return urlWithParams($this->ajaxUrl, parse_url(\URL::full(), PHP_URL_QUERY));
    }

    /**
     * @param  \App\Module|string  $module
     * @return ModuleDatatableTrait|bool
     */
    public function setModule($module)
    {

        if ($module) {
            return parent::setModule($module);
        }

        if (isset($this->moduleName)) {
            $module = Module::byName($this->moduleName);
        }

        if (!$module) {
            return false;
        }

        return parent::setModule($module);
    }

    /**
     * Automatically make is_active field as boolean
     *
     * @return array|string[]
     */
    public function booleans()
    {
        return array_merge($this->booleans, ['is_active']);
    }

    /**
     * Get all the date fields defined in the model and set them as datetime
     *
     * @return array
     */
    public function datetimes()
    {
        /** @var BaseModule $this */
        $model = $this->module->modelInstance();
        return array_merge($this->datetimes, $model->getDates());
    }

}