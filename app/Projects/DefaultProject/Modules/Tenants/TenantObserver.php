<?php

namespace App\Projects\DefaultProject\Modules\Tenants;

use App\Mainframe\Modules\Tenants\Traits\TenantObserverTrait;
use App\Projects\DefaultProject\Features\Modular\BaseModule\BaseModuleObserver;

class TenantObserver extends BaseModuleObserver
{
    use TenantObserverTrait;

    // /**
    //  * @param  App\Tenant  $element
    //  * @return void|bool
    //  */
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