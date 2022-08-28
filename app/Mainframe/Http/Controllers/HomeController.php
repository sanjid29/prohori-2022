<?php

namespace App\Mainframe\Http\Controllers;

use App\Mainframe\DataBlocks\SampleDataBlock;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->view('mainframe.dashboards.default.index');
        $sampleData = (new SampleDataBlock)->data();

        return $this->response()
            ->setViewVars(['sampleData' => $sampleData])
            ->send();
    }

}