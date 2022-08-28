<?php

namespace App\Projects\DefaultProject\Modules\Users;

use App\Projects\DefaultProject\Features\Report\ModuleReportBuilder;

class UserList extends ModuleReportBuilder
{

    public $moduleName = 'users';

    /**
     * @var string[]
     */
    public $fullTextFields = ['name', 'name_ext', 'email', 'first_name', 'last_name'];
}