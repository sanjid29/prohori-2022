<?php

namespace App\Mainframe\Helpers\Test;

use App\User;
use Tests\TestCase;

class UserTestCase extends TestCase
{

    /**
     * Logged in user
     *
     * @var User
     */
    public $user;

    /**
     * A prefix to add in the DB fields to indicate test entries.
     * They will be later deleted.
     *
     * @var string
     */
    public $testPrefix = 'TEST--';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::remember(timer('long'))->find(env('API_USER_ID'));
        $this->be($this->user); // Impersonate as the currently created admin user
    }

}


