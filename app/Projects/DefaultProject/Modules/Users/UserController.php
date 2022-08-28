<?php

namespace App\Projects\DefaultProject\Modules\Users;

use App\Mainframe\Features\Report\ModuleList;
use App\Mainframe\Modules\Users\Traits\UserControllerTrait;
use App\Projects\DefaultProject\Features\Modular\ModularController\ModularController;

/**
 * @group  Users
 * APIs for managing users
 */
class UserController extends ModularController
{
    use UserControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'users';

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    |
    */

    /**
     * Index/List
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->user->can('view-any', $this->model)) {
            return $this->permissionDenied();
        }

        if ($this->expectsJson()) {
            return (new UserList($this->module))->json(UserCollection::class);
        }

        $this->view->setType('index')->setDatatable($this->datatable());
        $this->view->addVars(['columns' => $this->datatable()->columns()]);

        return $this->view($this->view->gridPath());
    }

    /**
     * Show
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @urlParam  id required The ID of the item.
     */
    public function show($id)
    {
        $relations = request('with') ? explode(',', request('with')) : [];

        if (!$this->element = $this->model->with($relations)->find($id)) {
            return $this->notFound();
        }

        if (!$this->user->can('view', $this->element)) {
            return $this->permissionDenied();
        }

        // Redirect to edit page.
        return $this->load(new UserResource($this->element))
            ->to(route($this->moduleName.'.edit', $id))
            ->send();

    }

    /**
     * @return UserDatatable
     */
    public function datatable()
    {
        return new UserDatatable($this->module);
    }

    /**
     * Show and render report
     *
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Support\Collection|\Illuminate\View\View|mixed
     */
    // public function report(){if (! user()->can('view-report', $this->model)) {return $this->permissionDenied();}return (new ModuleReportBuilder($this->module))->output();}

    /**
     * Returns a collection of objects as Json for an API call
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function listJson() { return (new ModuleList($this->module))->json(); }

    /**
     * Get the view processor instance
     *
     * @return mixed|null
     */
    // public function viewProcessor()
    // {
    //     return new UserViewProcessor();
    // }

    /**
     * Show and render report
     *
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Support\Collection|\Illuminate\View\View|mixed
     */
    // public function report() { }
    // public function transformInputRequests() { }
    // public function storeRequestValidator() { }
    // public function updateRequestValidator() { }
    // public function saveRequestValidator() { }
    // public function attemptStore() { }
    // public function attemptUpdate() { }
    // public function attemptDestroy() { }
    // public function stored() { }
    // public function updated() { }
    // public function saved() { }
    // public function deleted() { }

    /*
    |--------------------------------------------------------------------------
    | Custom Controller functions
    |--------------------------------------------------------------------------
    | Write down additional controller functions that might be required for your project to handle bu
    |
    */

}
