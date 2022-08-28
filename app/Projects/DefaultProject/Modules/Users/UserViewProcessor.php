<?php

namespace App\Projects\DefaultProject\Modules\Users;

use App\Projects\DefaultProject\Features\Modular\BaseModule\BaseModuleViewProcessor;

class UserViewProcessor extends BaseModuleViewProcessor
{
    /**
     * @var \App\Mainframe\Modules\Modules\Module $module
     * @var \Illuminate\Database\Eloquent\Builder $model test
     * @var User $element
     * @var bool $editable
     * @var array $immutables
     * @var string $type i.e. View type create, edit, index etc.
     * @var array $vars Variables shared in view blade
     */

    /**
     * @var User
     */
    public $element;

    // public function formPath($state = 'create') { return parent::formPath($state) }
    // public function gridPath()
    // ... See parent class for available functions

}