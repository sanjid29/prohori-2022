<?php

namespace App\Projects\DefaultProject\Modules\Uploads;

use App\Mainframe\Modules\Uploads\Traits\UploadControllerTrait;
use App\Projects\DefaultProject\Features\Modular\ModularController\ModularController;

/**
 * @group  Uploads
 * APIs for managing uploads
 */
class UploadController extends ModularController
{

    use UploadControllerTrait;

    /*
    |--------------------------------------------------------------------------
    | Module definitions
    |--------------------------------------------------------------------------
    |
    */
    protected $moduleName = 'uploads';

    /**
     * @return UploadDatatable
     */
    public function datatable()
    {
        return new UploadDatatable($this->module);
    }
}