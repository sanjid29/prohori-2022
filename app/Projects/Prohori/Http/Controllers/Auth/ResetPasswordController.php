<?php

namespace App\Projects\Prohori\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\ResetPasswordController as MfResetPasswordController;
use App\Projects\Prohori\Features\Core\ViewProcessor;

class ResetPasswordController extends MfResetPasswordController
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
