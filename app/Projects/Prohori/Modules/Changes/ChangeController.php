<?php

namespace App\Projects\Prohori\Modules\Changes;

use App\Mainframe\Modules\Changes\Traits\ChangeControllerTrait;
use App\Projects\Prohori\Features\Modular\ModularController\ModularController;
use App\Projects\Prohori\Features\Report\ModuleList;

/**
 * @group  Change
 * APIs for managing changes
 */
class ChangeController extends ModularController
{
    use ChangeControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'changes';
    /** @var Change */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * Change Datatable
     *
     * @return ChangeDatatable
     */
    public function datatable()
    {
        return new ChangeDatatable($this->module);
    }

    /**
     * List Change
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