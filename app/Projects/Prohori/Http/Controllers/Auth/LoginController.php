<?php

namespace App\Projects\Prohori\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\LoginController as MfLoginController;
use App\Projects\Prohori\Features\Core\ViewProcessor;
use App\Projects\Prohori\Providers\RouteServiceProvider;

class LoginController extends MfLoginController
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /** @var string */
    protected $form = 'projects.prohori.auth.login';

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
