<?php

namespace App\Mainframe\Features\Form\Plugins\ListItems;

use App\Mainframe\Features\Form\Form;
use App\Module;
use Illuminate\Database\Eloquent\Builder;

class ListItems extends Form
{
    public $items;
    public $columns;
    public $createLink;
    public $label;
    public $tableId;
    public $createText;
    public $linkColumn;
    public $tableClass;
    public $showSerial;

    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);
        $this->orderBy = $this->var['order_by'] ?? 'name';

        $this->table = $this->var['table'] ?? null; // Must have table
        $this->model = $this->var['model'] ?? null; // Must have table
        $this->setModel();

        $this->query = $this->var['query'] ?? $this->getQuery(); // DB::table($this->table);
        $this->showInactive = $this->var['show_inactive'] ?? false;
        $this->cache = $this->var['cache'] ?? $this->cache;
        $this->items = $this->var['items'] ?? [];
        $this->columns = $this->var['columns'] ?? [];
        $this->createLink = $this->var['create_link'] ?? null;
        $this->label = $this->var['label'] ?? null;
        $this->tableId = $this->var['table_id'] ?? null;
        $this->createText = $this->var['create_text'] ?? null;
        $this->linkColumn = $this->var['link_column'] ?? null;
        $this->tableClass = $this->var['table_class'] ?? null;
        $this->showSerial = $this->var['show_serial'] ?? false;
    }

    public function setModel()
    {
        if (isset($this->var['model'])) {
            $model = $this->var['model'];
            if (is_string($model)) {
                $model = new $model;
            }
            $this->model = $model;
        }

        if (isset($this->var['table'])) {
            $table = $this->var['table'];
            if ($module = Module::fromTable($table)) {
                $this->model = $module->modelInstance();
            }
        }

        return $this;
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        return $this->model;
    }

    /**
     * Query on columns
     *
     * @return array
     */
    public function columns()
    {
        return $this->columns;
    }

    public function linkColumn()
    {
        return $this->linkColumn ?: \Arr::first($this->fields());
    }

    public function createText()
    {
        return $this->createText ?: 'Create';
    }

    public function tableId()
    {
        return $this->tableId ?: $this->uid;
    }

    public function showSerial()
    {
        return $this->showSerial;
    }

    public function titles()
    {
        return collect($this->columns())->values();
    }

    public function fields()
    {
        return collect($this->columns())->keys();
    }

    public function createLink()
    {
        if ($this->createLink) {
            return $this->createLink;
        }

        return null;
    }

    /**
     * @return array|mixed
     */
    public function items()
    {
        return $this->items;
    }

    public function tableClass()
    {
        return $this->tableClass ?: 'datatable-min';
    }
}