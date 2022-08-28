<?php

namespace App\Projects\Prohori\Modules\Modules;

use App\Mainframe\Modules\Modules\Traits\ModuleControllerTrait;
use App\Projects\Prohori\Features\Modular\ModularController\ModularController;
use App\Projects\Prohori\Features\Report\ModuleList;

/**
 * @group  Module
 * APIs for managing modules
 */
class ModuleController extends ModularController
{
    use ModuleControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'modules';
    /** @var Module */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * Module Datatable
     *
     * @return ModuleDatatable
     */
    public function datatable()
    {
        return new ModuleDatatable($this->module);
    }

    /**
     * List Module
     * Returns a collection of objects as Json for an API call
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listJson()
    {
        return (new ModuleList($this->module))->json();
    }

    // public function report() { }
    // public function storeRequestValidator() { }
    // public function updateRequestValidator() { }
    // public function saveRequestValidator() { }
    // public function attemptStore() { }
    // public function attemptUpdate() { }
    // public function attemptDestroy() { }
    // public function stored() { }
    // public function updated() { }
    // public function saved() { }
    // public function deleted() { }

    /*
    |--------------------------------------------------------------------------
    | Custom Controller functions
    |--------------------------------------------------------------------------
    | Write down additional controller functions that might be required for your project to handle bu
    */

}