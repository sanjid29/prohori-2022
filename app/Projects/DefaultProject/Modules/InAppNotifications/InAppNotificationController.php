<?php

namespace App\Projects\DefaultProject\Modules\InAppNotifications;

use App\Mainframe\Modules\InAppNotifications\Traits\InAppNotificationControllerTrait;
use App\Projects\DefaultProject\Features\Modular\ModularController\ModularController;
use App\Projects\DefaultProject\Features\Report\ModuleList;

/**
 * @group  InAppNotification
 * APIs for managing in-app-notifications
 */
class InAppNotificationController extends ModularController
{
    use InAppNotificationControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'in-app-notifications';
    /** @var InAppNotification */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * InAppNotification Datatable
     *
     * @return InAppNotificationDatatable
     */
    public function datatable()
    {
        return new InAppNotificationDatatable($this->module);
    }

    /**
     * List InAppNotification
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