<?php

namespace App\Projects\DefaultProject\Modules\ModuleGroups;

use App\Mainframe\Modules\ModuleGroups\Traits\ModuleGroupControllerTrait;
use App\Projects\DefaultProject\Features\Modular\ModularController\ModularController;
use App\Projects\DefaultProject\Features\Report\ModuleList;

/**
 * @group  ModuleGroup
 * APIs for managing module-groups
 */
class ModuleGroupController extends ModularController
{
    use ModuleGroupControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'module-groups';
    /** @var ModuleGroup */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * ModuleGroup Datatable
     *
     * @return ModuleGroupDatatable
     */
    public function datatable()
    {
        return new ModuleGroupDatatable($this->module);
    }

    /**
     * List ModuleGroup
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