<?php

namespace App\Projects\Prohori\Http\Controllers;

use App\Projects\Prohori\Notifications\Auth\ResetPassword;
use App\Projects\Prohori\Notifications\Auth\VerifyEmail;

class TestController extends BaseController
{

    public function test()
    {
        return;
    }

    /**
     * @param $id
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function previewUserVerifyEmail($id)
    {
        $user = \App\User::find($id);

        return (new VerifyEmail($user))
            ->toMail($user);
    }

    /**
     * @param $id
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function previewUserResetPasswordEmail($id)
    {
        $user = \App\User::find($id);

        return (new ResetPassword($user))
            ->toMail($user);
    }
}