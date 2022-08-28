<?php

namespace App\Projects\DefaultProject\Modules\Groups;

use App\Mainframe\Modules\Groups\Traits\GroupControllerTrait;
use App\Projects\DefaultProject\Features\Modular\ModularController\ModularController;
use App\Projects\DefaultProject\Features\Report\ModuleList;

/**
 * @group  Group
 * APIs for managing groups
 */
class GroupController extends ModularController
{
    use GroupControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'groups';
    /** @var Group */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * Group Datatable
     *
     * @return GroupDatatable
     */
    public function datatable()
    {
        return new GroupDatatable($this->module);
    }

    /**
     * List Group
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