<?php

namespace App\Projects\Prohori\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\ConfirmPasswordController as MfConfirmPasswordController;
use App\Projects\Prohori\Features\Core\ViewProcessor;
use App\Projects\Prohori\Providers\RouteServiceProvider;

class ConfirmPasswordController extends MfConfirmPasswordController
{
    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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