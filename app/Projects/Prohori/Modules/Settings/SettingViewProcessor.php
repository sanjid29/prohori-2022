<?php

namespace App\Projects\Prohori\Modules\Settings;

use App\Mainframe\Modules\Settings\Traits\SettingViewProcessorTrait;
use App\Module;
use App\Projects\Prohori\Features\Modular\BaseModule\BaseModuleViewProcessor;

class SettingViewProcessor extends BaseModuleViewProcessor
{
    use SettingViewProcessorTrait;

    /**
     * @var Module $module
     * @var \Illuminate\Database\Eloquent\Builder $model test
     * @var Setting $element
     * @var bool $editable
     * @var array $immutables
     * @var string $type i.e. View type create, edit, index etc.
     * @var array $vars Variables shared in view blade
     */

    /**
     * @var Setting
     */
    public $element;
    // public $immutables = ['name'];

}