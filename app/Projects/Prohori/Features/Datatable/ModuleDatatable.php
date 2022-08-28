<?php

namespace App\Projects\Prohori\Features\Datatable;

use App\Mainframe\Features\Datatable\Traits\ModuleDatatableTrait;

class ModuleDatatable extends Datatable
{
    use ModuleDatatableTrait;

    /** @var \App\Module */
    public $module;

    /**
     * @param $module
     */
    public function __construct($module = null)
    {
        parent::__construct();
        $this->setModule($module);
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed|void
     */
    public function filter($query)
    {
        $query = $this->applyAutoFilterUsingRequestParameters($query); // Auto apply filter

        return $query;

    }
}