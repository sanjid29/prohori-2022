<?php

namespace App\Mainframe\Modules\Reports\Traits;

use App\Module;
use App\Report;
use Illuminate\Support\Str;

trait ReportTrait
{
    /**
     * Get module default report url.
     *
     * @param $module_id
     * @return string
     */
    public static function defaultForModule($module_id)
    {
        /** @var Report $defaultReport */
        $defaultReport = Report::where('module_id', $module_id)
            ->where('is_module_default', 1)
            ->remember(timer('long'))->first();

        if ($defaultReport) {
            $report_url = $defaultReport->url();
        } else {
            $module = Module::remember(timer('very-long'))->find($module_id);
            $report_url = route($module->name.'.report')."?submit=Run&"
                ."select_columns_csv=id%2Cname%2Ccreated_by%2Ccreated_at%2Cupdated_by%2Cupdated_at%2Cis_active"
                ."&columns_csv=id%2Cname%2Ccreated_by%2Ccreated_at%2Cupdated_by%2Cupdated_at%2Cis_active"
                ."&alias_columns_csv=Id%2CName%2CCreated+by%2CCreated+at%2CUpdated+by%2CUpdated+at%2CActive%3F"
                ."&rows_per_page=25";
        }

        return $report_url;
    }

    /**
     * Get report full url
     *
     * @return string
     */
    public function url()
    {
        // If param is json then build the url with the array
        if (isJson($this->parameters)) {
            $url = route('home', json_decode($this->parameters, 1));
        } else {
            $url = route('home').urldecode($this->parameters);
        }

        if (!Str::contains($url, '?')) {
            $url .= '?';
        }

        return $url.'&report_name='.urlencode($this->name); // add report name
    }

    /**
     * Generates report url from id
     *
     * @param $id
     * @return string
     */
    public static function getReportUrlFromId($id)
    {
        if ($report = Report::remember(timer('short'))->find($id)) {
            return $report->url();
        }

        return false;
    }

}