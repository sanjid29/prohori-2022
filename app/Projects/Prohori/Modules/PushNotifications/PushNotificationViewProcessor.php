<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Projects\Prohori\Modules\PushNotifications;

use App\Mainframe\Modules\PushNotifications\Traits\PushNotificationViewProcessorTrait;
use App\Projects\Prohori\Features\Modular\BaseModule\BaseModuleViewProcessor;
use App\PushNotification;

class PushNotificationViewProcessor extends BaseModuleViewProcessor
{
    use PushNotificationViewProcessorTrait;

    /**
     * @var \App\Module $module
     * @var \Illuminate\Database\Eloquent\Builder $model
     * @var PushNotification $element
     * @var bool $editable
     * @var array $immutables
     * @var string $type i.e. View type create, edit, index etc.
     * @var array $vars Variables shared in view blade
     */

    /**
     * @var PushNotification
     */
    public $element;

    // Note: See parent class for available functions
    // public function immutables() { $this->addImmutables(['your_field']); return $this->immutables; }
    // public function hiddenFields() { $this->addHiddenFields(['your_field']); return $this->hiddenFields; }

    /*
    |--------------------------------------------------------------------------
    | Section: Blade template locations
    |--------------------------------------------------------------------------
    */
    // public function formPath($state = 'create') { }
    // public function gridPath() { }
    // public function changesPath() { }
    /*
    |--------------------------------------------------------------------------
    | Section: View Variables
    |--------------------------------------------------------------------------
    */
    // public function varsCreate() { }
    // public function viewVarsEdit() { }
    // public function formTitle() { }

    /*
    |--------------------------------------------------------------------------
    | Section: Condition functions to show a section in view
    |--------------------------------------------------------------------------
    */
    // public function showFormCreateBtn() { }
    // public function showFormListBtn() { }
    // public function showReportLink() { }
    // public function showTenantSelector() { }

    /*
    |--------------------------------------------------------------------------
    | Section: Report related view helpers
    |--------------------------------------------------------------------------
    */

}