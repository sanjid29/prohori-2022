<?php

namespace App\Projects\Prohori\Modules\Notifications;

use App\Mainframe\Modules\Notifications\Traits\NotificationControllerTrait;
use App\Projects\Prohori\Features\Modular\ModularController\ModularController;
use App\Projects\Prohori\Features\Report\ModuleList;

/**
 * @group  Notification
 * APIs for managing notifications
 */
class NotificationController extends ModularController
{
    use NotificationControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'notifications';
    /** @var Notification */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * Notification Datatable
     *
     * @return NotificationDatatable
     */
    public function datatable()
    {
        return new NotificationDatatable($this->module);
    }

    /**
     * List Notification
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