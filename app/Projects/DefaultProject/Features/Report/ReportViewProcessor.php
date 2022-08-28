<?php

namespace App\Projects\DefaultProject\Features\Report;

use App\Projects\DefaultProject\Features\Core\ViewProcessor;

class ReportViewProcessor extends ViewProcessor
{

    /**
     * ReportViewProcessor constructor.
     *
     * @param $reportBuilder
     */
    public function __construct($reportBuilder)
    {
        parent::__construct();
        $this->report = $reportBuilder;
    }

}