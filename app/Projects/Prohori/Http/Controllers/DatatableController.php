<?php

namespace App\Projects\Prohori\Http\Controllers;

use App\Mainframe\Features\Datatable\Traits\DatatableControllerTrait;

class DatatableController extends BaseController
{
    use DatatableControllerTrait;

    /**
     * Directory where DataBlock classes are stored
     *
     * @var string
     */
    public $path; // '\App\Projects\Prohori\Datatables';

    public function __construct()
    {
        parent::__construct();
        $this->path = projectNamespace().'\Datatables';
    }

}