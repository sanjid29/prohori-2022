<?php

namespace App\Mainframe\Features\Report\Traits;

use App\Mainframe\Helpers\Convert;
use App\Mainframe\Helpers\Sanitize;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

/** @mixin \App\Mainframe\Features\Report\ReportBuilder $this */
trait Filterable
{
    /**
     * Transform request inputs
     */
    public function transformRequest() { }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Builder|mixed  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Builder|mixed
     */
    public function filter($query)
    {
        $escapeFields = $this->defaultFilterEscapeFields(); // Default filter logic will not apply on these

        $requests = request()->all();

        foreach ($requests as $field => $val) {
            if ($val == null) {
                continue;
            }
            if (in_array($field, $escapeFields)) {
                $query = $this->customFilterOnEscapedFields($query, $field, $val);
            } else {
                $query = $this->defaultFilter($query, $field, $val);
            }
        }

        $query = $this->keySearch($query);
        $query = $this->customFilter($query);

        // Apply raw SQL clause input from front-end.
        if ($this->additionalFilterConditions()) {
            $query = $query->whereRaw($this->additionalFilterConditions());
        }

        $query = $this->excludeDeleted($query);

        return $query;
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Builder|mixed
     */
    public function excludeDeleted($query)
    {
        // exclude deleted_at fields

        $deleteField = 'deleted_at';
        if ($this->table) {
            $deleteField = $this->table.'.deleted_at';
        }
        if (in_array('deleted_at', $this->dataSourceColumns())) {
            $query = $query->whereNull($deleteField);
        }

        return $query;
    }

    /**
     * Some filters needs to escaped from default handling and used for custom query
     * generation.
     *
     * @return array
     */
    public function defaultFilterEscapeFields()
    {
        return $this->ghostColumns();
    }

    /**
     * Custom query for escaped filter fields.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function customFilterOnEscapedFields($query, $field, $val)
    {
        // if($field == 'some_name'){
        //     $query = $query->where($field,strtok($val));
        // }
        return $query;
    }

    /**
     * Additional custom filter on the full query
     *
     * @param $query
     * @return mixed
     */
    public function customFilter($query)
    {

        // Additional general filter
        return $query;

    }

    /**
     * Specific fields might have to be discarded from default query builder based on some
     * pattern.
     *
     * @param $field
     * @return bool
     */
    public function isEscapedField($field)
    {
        if (Str::startsWith($field, 'formatted_')) {
            return true;
        }

        return false;
    }

    /**
     * Field name with table prefix
     *
     * @param $field
     * @return string
     */
    public function dotField($field)
    {
        if (str_contains($field, '.')) {
            return $field;
        }
        if ($this->table) {
            return $this->table.'.'.$field;
        }

        return $field;
    }

    /**
     * Default query builder from input.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function defaultFilter($query, $field, $val)
    {
        if ($this->isEscapedField($field)) {
            return $query;
        }

        // The input field name matches a data source field name.
        if ($this->fieldExists($field)) {
            return $this->queryForExitingFields($query, $field, $val);
        }

        /**
         * If there is some field like created_at_from, end_date_from then
         * the builder smartly handles it to create a date range query.
         */
        if ($this->isFromRange($field)) {
            return $this->queryForFromRange($query, $field, $val);
        }

        if ($this->isToRange($field)) {
            return $this->queryForToRange($query, $field, $val);
        }

        return $query;
    }

    /**
     * Query for fields that exists in the data-source
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function queryForExitingFields($query, $field, $val)
    {
        // Handle array input: ?key[]=1,key[]=2

        $field = $this->dotField($field); // change field_name to table.field name to avoid ambiguity

        if ($this->paramIsArray($val)) {
            return $this->queryForArrayParam($query, $field, $val);
        }

        // Handle csv input: ?key=1,2,3
        if ($this->paramIsCsv($val)) { // Input is CSV
            return $this->queryForCsvParam($query, $field, $val);
        }

        // Handle int/string input: ?key=1 or request()->merge(['key'=>1]);
        if ((is_int($val) || $this->paramIsString($val)) && strlen(trim($val))) { // Input is string
            return $this->queryForStringParam($query, $field, $val);
        }

        return $query;
    }

    /**
     * Default query builder from input.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function queryForArrayParam($query, $field, $val)
    {
        if ($this->possibleJsonField($field)) { // Todo: Data stored in table is possibly json
            // return $query->whereJsonContains($field, $val); // Does't work for older maria db
        }

        return $query->whereIn($field, Sanitize::array($val));
    }

    /**
     * Default query builder from input.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function queryForCsvParam($query, $field, $val)
    {
        return $query->whereIn($field, Convert::csvToArray($val));
    }

    /**
     * Default query builder from input.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function queryForStringParam($query, $field, $val)
    {

        if ($val == 'null') {
            return $query->whereNull($field);
        }

        if ($this->columnIsFullText($field)) { // Substring search. Good for name, email etc.
            return $query->where($field, 'LIKE', '%'.$val.'%');
        }

        return $query->where($field, $val);
    }

    /**
     * Key based search in multiple columns. The columns are defined in $searchFields
     *
     * @param  Builder  $query
     * @return mixed
     */
    public function keySearch($query)
    {
        $key = request('search_key');

        if (!$key) {
            return $query;
        }

        # Key based search
        $query->where(function ($query) use ($key) {
            foreach ($this->searchFields as $field) {
                /** @var Builder $query */
                // $query->where('name', 'LIKE', "{$key}%");

                if (!$this->fieldExists($field)) {
                    continue;
                }
                if ($key == 'null') {
                    $query->orWhereNull($field);
                } elseif ($this->columnIsFullText($field)) { // Substring search. Good for name, email etc.
                    $query->orWhere($field, 'LIKE', "%{$key}%");
                } else {
                    $query->orWhere($field, $key);
                }

            }

        });

        return $query;

    }

    /**
     * Default query builder from input.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function queryForFromRange($query, $field, $val)
    {
        if (!is_string($val)) {
            return $query;
        }

        if ($this->isFromRange($field) && strlen($val)) {
            $dateTime = Carbon::parse($val);

            if (strlen($val) <= 10) { // String is date 2021-06-30 not datetime
                $dateTime->startOfDay();// Consider start of day
            }

            return $query->where($this->getActualDateField($field), '>=', $dateTime);
        }

        return $query;
    }

    /**
     * Default query builder from input.
     *
     * @param $query Builder
     * @param $field
     * @param $val
     * @return mixed
     */
    public function queryForToRange($query, $field, $val)
    {
        if (!is_string($val)) {
            return $query;
        }

        if ($this->isToRange($field) && strlen($val)) {
            $dateTime = Carbon::parse($val);

            if (strlen($val) <= 10) { // String is date 2021-06-30 not datetime
                $dateTime->endOfDay();// Consider end of day
            }

            return $query->where($this->getActualDateField($field), '<=', $dateTime);
        }

        return $query;
    }

    /**
     * Check if a filter parameter has array value
     *
     * @param $input
     * @return bool|int
     */
    public function paramIsArray($input)
    {
        if (is_array($input) && count($input)) {
            return count(Sanitize::array($input));
        }

        return false;
    }

    /**
     * Possibly the field contains json data
     *
     * @param $field
     * @return bool
     */
    public function possibleJsonField($field)
    {
        if (Str::contains($field, ['_ids', '_json'])) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a column exists in data source.
     *
     * @param $field
     * @return bool
     */
    public function fieldExists($field)
    {

        // If table.column is given then only extract column and check in source
        if (str_contains($field, '.')) {
            [$table, $field] = explode('.', $field);
        }

        return in_array($field, $this->dataSourceColumns());
    }

    /**
     * Check if param is csv
     *
     * @param $input
     * @return bool|int
     */
    public function paramIsCsv($input)
    {
        if (!is_string($input)) {
            return false;
        }

        if (strlen($input) && strpos($input, ',') !== false) {
            return strlen(Sanitize::csv($input));
        }

        return false;
    }

    /**
     * Check if param is string.
     *
     * @param $input
     * @return string
     */
    public function paramIsString($input)
    {
        if (!is_string($input)) {
            return false;
        }

        return trim(strlen($input));
    }

    /**
     * Check if a column is for full text search. These will be processed with %LIKE%
     *
     * @param $column
     * @return bool
     */
    public function columnIsFullText($column)
    {
        $fieldNameWithoutDot = $column;
        if (Str::contains($fieldNameWithoutDot, '.')) {
            $fieldNameWithoutDot = Str::after($column, '.');
        }

        return in_array($column, $this->getFullTextFields()) ||
            in_array($fieldNameWithoutDot, $this->getFullTextFields());
    }

    /**
     * Get an array for full text search. These fields will be SQL LIKE
     *
     * @return array
     */
    public function getFullTextFields()
    {
        return $this->fullTextFields;
    }

    /**
     * From the name of the input try to assume if it is some data-from field
     *
     * @param $field
     * @return bool
     */
    public function isFromRange($field)
    {
        if (Str::contains($field, ['_from', '_start', '_starts', '_min'])) {
            return true;
        }

        return false;
    }

    /**
     * From the name of the input try to assume if it is some data-to field
     *
     * @param $field
     * @return bool
     */
    public function isToRange($field)
    {
        if (Str::contains($field, ['_to', '_till', '_end', '_ends', '_max'])) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the input field is date format
     *
     * @param $field
     * @return bool
     */
    public function columnLooksLikeDateField($field)
    {
        if (Str::contains($field, ['_at', 'on', 'date'])) {
            return true;
        }

        return false;
    }

    /**
     * Get the actual date field
     *
     * @param $field
     * @return string
     */
    public function getActualDateField($field)
    {
        $replaces = [
            '_from',
            '_start',
            '_starts',
            '_to',
            '_till',
            '_end',
            '_ends',
        ];

        $actual = $field;
        foreach ($replaces as $replace) {
            $actual = Str::replaceLast($replace, '', $actual);
        }

        return $actual;
    }

    /**
     * Additional filters
     *
     * @return mixed
     */
    public function additionalFilterConditions()
    {
        return request('additional_conditions');
    }

}