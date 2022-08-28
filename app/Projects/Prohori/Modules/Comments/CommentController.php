<?php

namespace App\Projects\Prohori\Modules\Comments;

use App\Mainframe\Modules\Comments\Traits\CommentControllerTrait;
use App\Projects\Prohori\Features\Modular\ModularController\ModularController;
use App\Projects\Prohori\Features\Report\ModuleList;

/**
 * @group  Comment
 * APIs for managing comments
 */
class CommentController extends ModularController
{
    use CommentControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    */
    protected $moduleName = 'comments';
    /** @var Comment */
    protected $element;

    /*
    |--------------------------------------------------------------------------
    | Existing Controller functions
    |--------------------------------------------------------------------------
    | Override the following list of functions to customize the behavior of the controller
    */
    /**
     * Comment Datatable
     *
     * @return CommentDatatable
     */
    public function datatable()
    {
        return new CommentDatatable($this->module);
    }

    /**
     * List Comment
     * Returns a collection of objects as Json for an API call
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listJson()
    {
        return (new ModuleList($this->module))->json();
    }

    // public function report() { }
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
    */

}