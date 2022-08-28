<?php

namespace App\Projects\Prohori\Http\Controllers\Api;

use App\Mainframe\Http\Controllers\Api\Traits\ApiControllerTrait;
use App\Projects\Prohori\Http\Controllers\BaseController;

class ApiController extends BaseController
{
    use ApiControllerTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add a user parameter in the request
     */
    public function injectUserIdentityInRequest()
    {
        // if ($this->user->ofReseller()) {
        //     request()->merge(['reseller_id' => $this->user->reseller_id]);
        // }
    }

}