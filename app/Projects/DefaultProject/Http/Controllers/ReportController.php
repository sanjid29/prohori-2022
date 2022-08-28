<?php

namespace App\Projects\DefaultProject\Http\Controllers;

use App\Mainframe\Features\Report\Traits\ReportControllerTrait;

class ReportController extends BaseController
{
    use ReportControllerTrait;

    /**
     * Directory where DataBlock classes are stored
     *
     * @var string
     */
    public $path;  //'\App\Projects\DefaultProject\Reports';

    public function __construct()
    {
        parent::__construct();
        $this->path = projectNamespace().'\Reports';

    }
}