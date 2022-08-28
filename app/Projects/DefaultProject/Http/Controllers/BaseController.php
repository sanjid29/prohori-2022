<?php

namespace App\Projects\DefaultProject\Http\Controllers;

use App\Mainframe\Http\Controllers\BaseController as MainframeBaseController;
use App\Projects\DefaultProject\Features\Core\ViewProcessor;
use View;

class BaseController extends MainframeBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->user = user();
        $this->view = new ViewProcessor();

        View::share([
            'user' => $this->user,
            'view' => $this->view,
        ]);
    }
}