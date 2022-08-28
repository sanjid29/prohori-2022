<?php

namespace App\Projects\DefaultProject\Http\Controllers;

use App\Projects\DefaultProject\DataBlocks\SampleDataBlock;
use App\User;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function public()
    {
        return view('projects.default-project.public.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
        # If, user is not logged in then show public
        if (!auth()->check()) {
            return redirect('public');
        }

        $this->view('projects.default-project.dashboards.admin');
        $sampleData = (new SampleDataBlock)->get();

        return $this->response()
            ->setViewVars(['sampleData' => $sampleData])
            ->send();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function userDetails($id)
    {
        $userInfo = User::find($id);

        $userInfo->email = null;

        if (!$userInfo) {
            return $this->notFound();
        }

        $userInfo->email = null; // <-- Setting to null to trigger validation error

        $processor = $userInfo->processor()->checkForSave(); // Attempting to save

        if ($processor->isInvalid()) {
            $this->mergeValidatorErrors($processor->validator);
            $this->fail('Ahha! Can not save it 😩');
        } else {
            $this->success('User saved successfully 😃');
        }

        return $this->load($userInfo)
            ->setViewPath('projects.default-project.user.details')
            ->setViewVars(['userInfo' => $userInfo])
            ->send();

    }

}