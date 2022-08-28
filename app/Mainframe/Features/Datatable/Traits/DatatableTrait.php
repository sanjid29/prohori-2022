<?php

namespace App\Mainframe\Features\Datatable\Traits;

use App\Mainframe\Features\Datatable\Datatable;

/** @mixin Datatable */
trait DatatableTrait
{
    /**
     * Define Query for generating results for grid
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function source()
    {
        return \DB::table($this->table)->leftJoin('users as updater', 'updater.id', $this->table.'.updated_by');
    }

    /**
     * Define grid SELECT statement and HTML column name.
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

    /**
     * Construct SELECT statement (field1 AS f1, field2 as f2...)
     *
     * @return array
     */
    public function selects()
    {
        $columns = $this->columns();

        // Note: Modify the $columns as you need.

        return $this->selectQueryString($columns);
    }

    /**
     * Create a sql query string
     *
     * @param $columns
     * @return array
     */
    public function selectQueryString($columns)
    {
        $selects = [];
        foreach ($columns as $col) {
            $selects[] = $col[0].' as '.$col[1]; // user.name as name
        }

        return $selects;
    }

    /**
     * Define Query for generating results for grid
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|mixed
     */
    public function query()
    {
        $query = $this->source()->select($this->selects());

        // Exclude deleted rows
        $query = $query->whereNull($this->table.'.deleted_at');

        return $this->filter($query);
    }

    /**
     * Apply filter on query.
     *
     * @param $query \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|mixed
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|mixed
     */
    public function filter($query)
    {
        return $query;
    }

    /**
     * Auto apply filter based on query.
     *
     * @param $query \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|mixed
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|mixed
     */
    public function applyAutoFilterUsingRequestParameters($query)
    {
        $skipAutoFilterColumns = ['order'];

        if ($this->model) {
            $columns = $this->model->tableColumns();
            $table = $this->model->getTable();
            foreach ($columns as $column) {

                if (in_array($column, $skipAutoFilterColumns)) {
                    continue;
                }

                if (strlen(request($column)) || is_array(request($column))) { // Example code
                    /** @noinspection UnknownColumnInspection */
                    $query->whereIn($table.'.'.$column, \Arr::wrap(request($column)));
                }
            }
        }

        return $query;
    }

    /**
     * Modify datatable values
     *
     * @return mixed
     * @var $dt \Yajra\DataTables\DataTableAbstract
     */
    public function modify($dt)
    {
        // Handle binary columns

        // if ($this->hasColumn('name')) {
        //     // $dt = $dt->editColumn('name', '<a href="{{ route(\''.$this->module->name.'.edit\', $id) }}">{{$name}}</a>');
        //     $dt = $dt->editColumn('name', function ($row) {
        //         return '<a href="'.route($this->module->name.'.edit', $row->id).'">'.$row->name.'</a>';
        //     });
        // }

        return $dt;
    }

    /**
     * Modify date fields
     *
     * @return mixed
     * @var $dt \Yajra\DataTables\DataTableAbstract
     */
    public function transformValues($dt)
    {
        $transforms = \Arr::wrap($this->transforms());

        foreach ($transforms as $field => $value) {
            if ($this->hasColumn($field)) {
                $dt->editColumn($field, function ($row) use ($field, $value) {
                    return $value[(string) $row->$field] ?? $row->$field;
                });
            }
        }

        return $dt;
    }

    /**
     * Modify date fields
     *
     * @return mixed
     * @var $dt \Yajra\DataTables\DataTableAbstract
     */
    public function transformBooleans($dt)
    {
        $booleans = \Arr::wrap($this->booleans());

        foreach ($booleans as $field) {
            if ($this->hasColumn($field)) {
                $dt->editColumn($field, function ($row) use ($field) {

                    if ($row->$field == 1) {
                        return 'Yes';
                    }
                    if ($row->$field == 0) {
                        return '<span class="text-red">No</span>';
                    }
                    return $row->$field;
                });
            }
        }

        return $dt;
    }

    /**
     * Modify date fields
     *
     * @return mixed
     * @var $dt \Yajra\DataTables\DataTableAbstract
     */
    public function transformDatetimes($dt)
    {
        $datetimes = \Arr::wrap($this->datetimes());

        foreach ($datetimes as $field) {
            if ($this->hasColumn($field)) {
                $dt->editColumn($field, function ($row) use ($field) {

                    if ($row->$field) {
                        return formatDateTime($row->$field);
                    }

                    return $row->$field;
                });
            }
        }

        return $dt;
    }

    /**
     * Modify boolean fields
     *
     * @return mixed
     * @var $dt \Yajra\DataTables\DataTableAbstract
     */
    public function transformDates($dt)
    {
        $dates = \Arr::wrap($this->dates());

        foreach ($dates as $field) {
            if ($this->hasColumn($field)) {
                $dt->editColumn($field, function ($row) use ($field) {

                    if ($row->$field) {
                        return formatDate($row->$field);
                    }

                    return $row->$field;
                });
            }
        }

        return $dt;
    }

    public function process()
    {

        $this->datatable();
        $dt = $this->dt;

        if (count($this->whiteList)) {
            $dt->whitelist($this->whiteList);
        }

        if (count($this->blackList)) {
            $dt->blacklist($this->blackList);
        }

        $dt->rawColumns(array_merge($this->rawColumns, $this->columnKeys()));

        $dt = $this->transformBooleans($dt);
        $dt = $this->transformDateTimes($dt);
        $dt = $this->transformDates($dt);
        $dt = $this->transformValues($dt);

        return $dt;
    }

    /**
     * Returns datatable json for the module index page
     * A route is automatically created for all modules to access this controller function
     *
     * @return \Illuminate\Http\JsonResponse
     * @var \Yajra\DataTables\DataTables $dt
     */
    public function json()
    {
        $dt = $this->process();

        return $this->modify($dt)->toJson();
    }

    /**
     * Instantiate data table.
     *
     * @return $this
     */
    public function datatable()
    {

        $query = $this->query();

        // Set a default limit if not in set in request
        if (!request('length')) {
            $query->limit(10);
        }

        $this->dt = datatables($query);

        return $this;
    }

    /**
     * Check if a column exists in the data table
     *
     * @param $column
     * @return bool
     */
    public function hasColumn($column)
    {
        foreach ($this->columns() as $col) {
            if ($col[1] == $column) {
                return true;
            }
        }

        return false;
    }

    public function visibleColumns()
    {
        return collect($this->columns())->reject(function ($item, $key) {
            return in_array($item[1], $this->hidden());
        })->all();
    }

    /**
     * Titles extracted from the column definition
     *
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public function titles()
    {
        $titles = $this->visibleColumns();

        return collect($titles)->map(function ($item, $key) {
            return $item[2];             // Take 3rd index. Check datatable class select()
        })->all();
    }

    public function columnKeys()
    {
        $columns = $this->visibleColumns();

        return collect($columns)->map(function ($item, $key) {
            return $item[1];             // Take 3rd index. Check datatable class select()
        })->all();
    }

    /**
     * Json value for Javascript dataTable.ajax
     * { data: 'id', name: 'settings.id' },{ data: 'name', name: 'settings.name' },...
     *
     * @return mixed
     */
    public function columnsJson()
    {
        return collect($this->columns())->reduce(function ($carry, $item) {
            if (!in_array($item[1], $this->hidden())) {
                return $carry."{ data: '".$item[1]."', name: '".$item[0]."' },";
            }

            return $carry;
        });
    }

    /**
     * API url
     *
     * @return mixed
     */
    public function ajaxUrl()
    {
        return urlWithParams($this->ajaxUrl, parse_url(\URL::full(), PHP_URL_QUERY));
    }

    /**
     * Datatable unique identifier
     *
     * @return string|null
     */
    public function identifier()
    {

        // if ($this->module) {
        //     return \Str::camel($this->module->name.'Dt');
        // }
        //
        // if ($this->table) {
        //     return \Str::camel($this->table.'Dt');
        // }

        return \Str::camel(class_basename($this).'Dt');

    }

    /**
     * The name should be CamelCase
     *
     * @return string
     */
    public function name()
    {
        if ($this->name) {
            return \Str::camel($this->name);
        }

        return \Str::camel($this->identifier());
    }

}