<?php

namespace App\Projects\DefaultProject\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\LoginController as MfLoginController;
use App\Projects\DefaultProject\Features\Core\ViewProcessor;
use App\Projects\DefaultProject\Providers\RouteServiceProvider;

class LoginController extends MfLoginController
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /** @var string */
    protected $form = 'projects.default-project.auth.login';

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
