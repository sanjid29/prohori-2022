<?php

namespace App\Projects\DefaultProject\Modules\Settings;

use App\Mainframe\Modules\Settings\Traits\SettingObserverTrait;
use App\Projects\DefaultProject\Features\Modular\BaseModule\BaseModuleObserver;

class SettingObserver extends BaseModuleObserver
{
    use SettingObserverTrait;

    /**
     * @param  Setting  $element
     * @return void|bool
     */
    // public function saving($element) { }
    // public function creating($element) { }
    // public function created($element) { }
    // public function updating($element) { }
    // public function updated($element) { }
    // public function saved($element) { }
    // public function deleting($element) { }
    // public function deleted($element) { }
    // public function restored($element) { }
    // public function forceDeleted($element) { }
}