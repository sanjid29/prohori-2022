<?php

namespace App\Projects\Prohori\Modules\Users;

use App\Projects\Prohori\Features\Report\ModuleReportBuilder;

class UserList extends ModuleReportBuilder
{

    public $moduleName = 'users';

    /**
     * @var string[]
     */
    public $fullTextFields = ['name', 'name_ext', 'email', 'first_name', 'last_name'];
}