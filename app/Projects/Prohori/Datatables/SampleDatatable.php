<?php

namespace App\Projects\Prohori\Datatables;

use App\Projects\Prohori\Features\Datatable\Datatable;

class SampleDatatable extends Datatable
{
    // use CustomDatatableTrait;
    public $booleans = ['is_active'];
    public $datetimes = ['updated_at'];
    public $transforms = [
        'is_active' => [
            '0' => "Zero",
            '1' => "<span class='badge bg-green'>One</span>",
        ]
    ];

    /**
     * @param $module
     */
    public function __construct()
    {
        parent::__construct();
        $this->setModule('users');
        // $this->setTable('users');
    }

    /**
     * Define grid SELECT statement and HTML column name.
     *
     * @return array
     */
    // public function columns()
    // {
    //     return [
    //         [$this->table.".id", 'id', 'ID'],
    //         [$this->table.".name", 'name', 'Name'],
    //         [$this->table.".group_ids", 'group_ids', 'Groups'],
    //         ['updater.name', 'user_name', 'Updater'],
    //         [$this->table.".updated_at", 'updated_at', 'Updated at'],
    //         [$this->table.".is_active", 'is_active', 'Active'],
    //     ];
    // }
}