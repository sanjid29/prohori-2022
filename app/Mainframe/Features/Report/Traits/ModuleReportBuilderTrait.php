<?php

namespace App\Mainframe\Features\Report\Traits;

use App\Mainframe\Features\Report\ReportBuilder;
use App\Module;

/** @mixin ReportBuilder $this */
trait ModuleReportBuilderTrait
{

    /**
     * Transform request inputs
     */
    public function transformRequest()
    {
        # Hide inactive items for non-admins
        if (!$this->user->isSuperUser()) {
            request()->merge(['is_active' => 1,]); // Note: creates field ambiguity if join is made
        }
    }

    /**
     * @param  Module|string  $module
     * @return ModuleReportBuilderTrait
     */
    public function setModule($module)
    {
        if (is_string($module)) {
            $module = Module::byName($module);
        }

        $this->module = $module;
        $this->model = $this->module->modelInstance();
        $this->table = $this->model->getTable();

        $this->dataSource = $this->model;

        if ($columns = $this->querySelectColumns()) {
            $this->dataSource = $this->model->select($columns);
        }

        if (request('with')) {
            $this->dataSource->with($this->queryRelations());
        }

        return $this;
    }

    public function viewProcessor()
    {
        return $this->model->viewProcessor()->setReport($this);
    }

    /**
     * Default select id column to link to module details page
     *
     * @return string[]
     */
    public function defaultColumns()
    {
        // return ['id'];
        if ($columns = $this->getColumnsFromRequest()) {
            return array_merge($columns, ['id', 'uuid', 'tenant_id']);
        }

        return $this->model->tableColumns();
    }

    /**
     * By default show a limited number of columns in module report
     *
     * @return array|string[]
     */
    public function selectedColumns()
    {
        # Check if the column names are available in request().
        if ($columns = $this->getColumnsFromRequest()) {
            return $columns;
        }

        return ['id', 'name', 'created_at', 'updated_at',];
    }

}