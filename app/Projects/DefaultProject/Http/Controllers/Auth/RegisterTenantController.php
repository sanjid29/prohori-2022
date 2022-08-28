<?php /** @noinspection ALL */

namespace App\Projects\DefaultProject\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\RegisterTenantController as MfRegisterTenantController;
use App\Projects\DefaultProject\Features\Core\ViewProcessor;

class RegisterTenantController extends MfRegisterTenantController
{
    public function __construct()
    {
        parent::__construct();
        // Share project's view processor
        $this->view = new ViewProcessor();
        \View::share([
            'view' => $this->view,
        ]);
    }

}
