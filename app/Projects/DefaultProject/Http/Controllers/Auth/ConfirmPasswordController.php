<?php

namespace App\Projects\DefaultProject\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\ConfirmPasswordController as MfConfirmPasswordController;
use App\Projects\DefaultProject\Features\Core\ViewProcessor;
use App\Projects\DefaultProject\Providers\RouteServiceProvider;

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