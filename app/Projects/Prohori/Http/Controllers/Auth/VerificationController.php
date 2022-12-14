<?php

namespace App\Projects\Prohori\Http\Controllers\Auth;

use App\Mainframe\Http\Controllers\Auth\VerificationController as MfVerificationController;
use App\Projects\Prohori\Features\Core\ViewProcessor;
use Illuminate\Http\Request;

class VerificationController extends MfVerificationController
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

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        if ($request->user()) {
            return $request->user()->hasVerifiedEmail()
                ? redirect($this->redirectPath())
                : view('projects.prohori.auth.verify');
        }

        return view('projects.prohori.auth.verify');
    }
}
