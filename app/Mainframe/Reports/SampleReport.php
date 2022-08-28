<?php

namespace App\Mainframe\Reports;

use App\Mainframe\Features\Report\ReportBuilder;
use App\Mainframe\Features\Report\Traits\ModuleReportBuilderTrait;
use App\User;

class SampleReport extends ReportBuilder
{
    use ModuleReportBuilderTrait;

    /** @var int */
    public $rowsPerPage = 100;

    /** @var array */
    public $zoneWiseStats;

    /**
     * ZoneDurationReport constructor.
     */
    public function __construct()
    {

        $this->enableAutoRun();

        // // Use Module Report feature to leverage Eloquent features like relation, helper
        // $this->setModule('zone-durations');
        //
        // $this->dataSource = ZoneDuration::with(
        //     [
        //         'user:id,name,role_id',
        //         'user.role:id,title',
        //     ])
        //     ->select($this->defaultColumns())
        //     ->latest();

        parent::__construct();

    }

    /*---------------------------------
    | Section: Define paths
    |---------------------------------*/
    /**
     * Set the result view blade.
     *
     * @return string
     */
    public function resultViewPath()
    {
        return 'projects.jiminy.reports.zone-durations.result-default-map';
    }

    /**
     * Set the filter view blade.
     *
     * @return string|\View
     */
    public function filterPath()
    {
        return 'projects.jiminy.reports.zone-durations.filter';
    }


    /*---------------------------------
    | Section: Query building
    |---------------------------------*/
    /**
     * Default selected columns
     *
     * @return array|string[]
     */
    public function defaultColumns()
    {
        return [
            'id',
            'client_id',
            'name',
            'user_id',
            'user_name',
            'role_title',
            'device_id',
            'device_uid',
            'zone_id',
            'zone_name',
            'site_id',
            'site_name',
            // 'from',
            // 'till',
            'seconds',
        ];
    }

    /**
     * @return array|string[]
     */
    public function selectedColumns()
    {
        return array_diff($this->defaultColumns(), ['seconds']);
    }

    /**
     * Some times we need to pass column names that do not exists directly in the source (model/table).
     * Such should be skipped in the query building. Value of these columns should be assigned
     * through mutateResult() function. This is useful to include custom fields in the report
     * User can also see these ghost columns in the column selection option in the advanced
     * report option.
     *
     * @return array
     */
    public function ghostColumns()
    {
        return ['user_name', 'site_name', 'zone_name', 'role_title'];
    }

    /*---------------------------------
    | Section : Group by
    |---------------------------------*/

    /**
     * @return array|string[]
     */
    public function groupByFields()
    {
        // return ['zone_id']; // Only group by zone so that different users can be seen
        return ['zone_id', 'user_id'];
    }

    /**
     * Adds the custom COUNT/SUM column in SQL SELECT.
     *
     * @param  array  $keys
     * @return array
     */
    public function queryAddColumnForGroupBy($keys = [])
    {
        if ($this->hasGroupBy()) {
            $keys[] = \DB::raw('round((sum(seconds)/60),2) as total_minutes');
            $keys[] = \DB::raw('SEC_TO_TIME(sum(seconds)) as total_duration');
            $keys[] = \DB::raw('MIN(`from`) as first_from');
            $keys[] = \DB::raw('MAX(till) as last_till');
        }

        return $keys;
    }

    /**
     * @return array|string[]
     */
    public function additionalSelectedColumnsDueToGroupBy()
    {
        //return ['total_minutes'];
        return ['first_from', 'last_till', 'total_duration'];

    }

    /**
     * @return array|string[]
     */
    public function additionalAliasColumnsDueToGroupBy()
    {
        //return ['Total (Minutes)'];
        return ['From', 'Till', 'Total Duration'];
    }

    //------------------- Group by -------------------------------//

    /*---------------------------------
    | Mutate Results
    |---------------------------------*/

    /**
     * Function changes result, show_column, aliasColumns for the final output
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     * @throws \Exception
     */
    public function mutateResult()
    {
        $result = $this->result();

        foreach ($result as $row) {
            if (in_array('user_name', $this->selectedColumns())) {
                $row->user_name = $this->linkUser($row);
            }
            if (in_array('site_name', $this->selectedColumns())) {
                $row->site_name = $this->linkSite($row);
            }
            if (in_array('zone_name', $this->selectedColumns())) {
                $row->zone_name = $this->linkZone($row);
            }
            if (in_array('role_title', $this->selectedColumns())) {
                $row->role_title = $row->user->role->title;
            }
        }

        return $result;
    }

    /**
     * @param  ZoneDuration  $row
     * @return mixed|null|string
     */
    public function linkUser($row)
    {
        if (!$row->user) {
            return null;
        }

        return $row->user->name;
    }

    /**
     * @param  ZoneDuration  $row
     * @return mixed|null|string
     */
    public function linkSite($row)
    {
        if (!$row->site) {
            return null;
        }

        return $row->site->name;
    }

    /**
     * @param $row
     * @return mixed|null|string
     */
    public function linkZone($row)
    {
        if (!$row->zone) {
            return null;
        }

        return $row->zone->name;
    }


    /*---------------------------------
    | Additional view variables
    |---------------------------------*/
    /**
     * Additional view variables to pass to view blade
     *
     * @return array
     */
    public function customViewVars()
    {

        $result = $this->result();
        $features = [];

        $popups = $this->zonePopupContents();

        /*-----------------------------------------------------------------------
        | Find unique zones and and update the geoJson label and popup properties
        |-----------------------------------------------------------------------*/
        $zoneDurations = $result->unique('zone_id')->all();

        foreach ($zoneDurations as $zoneDuration) {

            /** @var ZoneDuration $zoneDuration */

            if ($zoneDuration->zone && $zoneDuration->zone->geo_json) {

                // Show total time in map label
                // -----------------------------

                $total = $result->where('zone_id', $zoneDuration->zone_id)
                    ->sum('total_minutes');

                $label = "{$zoneDuration->zone->name} (".gmdate("H:i:s", $total).")";

                /** @var Feature $feature */
                $feature = Gis::setProperty($zoneDuration->zone->geo_json, 'label', $label);

                // load popup property
                // -----------------------------
                $feature = Gis::setProperty($feature, 'popup', $popups[$zoneDuration->zone_id] ?? 'No data');

                $features[] = Gis::toFeature($feature);
            }
        }

        $featureCollection = new \GeoJson\Feature\FeatureCollection($features);

        return ['geoJson' => Gis::geoJson($featureCollection)];
    }

    /**
     * Prepare the HTML content
     *
     * @return array
     */
    public function zonePopupContents()
    {
        $zones = $this->zoneWiseStats();
        $popups = [];
        foreach ($zones as $id => $stats) {
            $html = "<table class='table'>";

            foreach ($stats as $type => $count) {
                $html .= "<tr><td>{$type}</td><td>{$count}</td></tr>";
            }

            $html .= "</table>";
            $popups[$id] = $html;
        }

        return $popups;

    }

    /**
     * @return mixed|array
     */
    public function zoneWiseStats()
    {

        if ($this->zoneWiseStats) {
            return $this->zoneWiseStats;
        }

        $query = ZoneDuration::leftJoin('users', 'zone_durations.user_id', 'users.id')
            ->leftJoin('roles', 'users.role_id', 'roles.id')
            ->select('zone_durations.name', 'zone_id', 'user_id', 'roles.title AS role_title', \DB::raw('COUNT(DISTINCT(user_id)) as total'))
            ->whereNotNull('roles.title');
        // ->where('zone_id',197)
        // ->distinct('user_id')

        # Apply filters
        $query = $this->filter($query);

        # Group-by
        $query->groupBy('zone_id')->groupBy('user_id');

        $results = $query->get();

        $stats = [];

        foreach ($results as $zoneDuration) {

            /** @var ZoneDuration $zoneDuration */
            $temp[$zoneDuration->role_title] = $zoneDuration->total;

            $stats[$zoneDuration->zone_id] = $temp;
        }

        foreach ($stats as $zoneId => $roles) {
            $total = collect($roles)->sum();
            $stats[$zoneId]['Total'] = $total;
            foreach ($roles as $roleName => $count) {
                $stats[$zoneId][$roleName] = $count." (".round(($count * 100) / $total, 1)."%)";
            }
        }

        // my_printr($stats);

        $this->zoneWiseStats = $stats;

        return $this->zoneWiseStats;

    }

    /*---------------------------------
    | Section : Helpers
    |---------------------------------*/

}