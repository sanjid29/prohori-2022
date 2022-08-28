<?php /** @noinspection PhpVariableVariableInspection */

namespace App\Mainframe\Features\Form\Select;

use App\Module;

class SelectAjaxPreviewSearch extends SelectAjax
{
    public $advancedSearch;
    public $preview;
    public $modal; // View path to modal
    public $previewUrl; // Get preview HTML from this url
    public $previewUrlParam; // sends id=xx to the get preview(view-partial)route
    public $datatable; // Datatable object
    public $datatableView; // Datatable view blade

    public function __construct(&$var = [], $element = null)
    {
        // First force config in var
        $var['name_field'] = $var['name_field'] ?? 'name';
        $var['model'] = $var['model'] ?? null;
        parent::__construct($var, $element);

        // Then fill the attributes from $this->var[...]
        $this->advancedSearch = $this->var['advanced_search'] ?? true;
        $this->preview = $this->var['preview'] ?? true;
        $this->previewUrl = $this->var['preview_url'] ?? null;
        $this->previewUrlParam = $this->var['preview_url_param'] ?? null;
        $this->modal = $this->var['modal'] ?? null;
        $this->datatable = $this->var['datatable'] ?? null;             //todo
        $this->datatableView = $this->var['datatable_view'] ?? null;    //todo

        $this->id = \Str::camel($this->id); // For camel case because function names will be created using this Id
    }

    public function url()
    {
        if ($this->url) {
            return $this->url;
        }

        $urlParams = $this->urlParams;
        // 1. Add column selections
        if (!array_key_exists('columns_csv', $urlParams)) {
            $urlParams['columns_csv'] = implode(',', [
                $this->valueField,
                $this->nameField,
                // 'name_bn' // Additional fields
            ]);
        }

        // 2. Show inactive items?
        if (!$this->showInactive) {
            $urlParams['is_active'] = 1;
        }

        // 3. Build Model/Table query
        $moduleName = null;
        if ($this->table) {
            $moduleName = Module::fromTable($this->table)->name;
        }

        if ($this->model) {
            $moduleName = $this->model->module()->name;
        }

        return route("$moduleName.list-json", $urlParams);
    }

    public function preload()
    {
        if ($this->value()) {
            $item = $this->getQuery()
                ->select([$this->valueField, $this->nameField])
                ->where($this->valueField, $this->value())
                ->first();

            $nameField = $this->nameField;
            if ($item) {
                return $item->$nameField;
            }
        }
        return $this->preload;
    }

}