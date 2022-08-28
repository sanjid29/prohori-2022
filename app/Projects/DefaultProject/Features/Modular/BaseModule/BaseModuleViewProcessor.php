<?php

namespace App\Projects\DefaultProject\Features\Modular\BaseModule;

use App\Projects\DefaultProject\Features\Core\ViewProcessor;

class BaseModuleViewProcessor extends ViewProcessor
{
    /*
    |--------------------------------------------------------------------------
    | Blade template locations
    |--------------------------------------------------------------------------
    */
    // public function formPath($state = 'create') { }
    // public function gridPath() { }
    // public function changesPath() { }

    /*
    |--------------------------------------------------------------------------
    | View Variables
    |--------------------------------------------------------------------------
    */
    // public function varsCreate() { }
    // public function viewVarsEdit() { }
    // public function getImmutables() { }
    // public function formTitle() { }

    /*
    |--------------------------------------------------------------------------
    | Condition functions to show a section in view
    |--------------------------------------------------------------------------
    */
    // public function showFormCreateBtn() { }
    // public function showFormListBtn() { }
    // public function showReportLink() { }
    // public function showTenantSelector() { }

    /**
     * Show clone button in module form
     *
     * @return bool
     */
    public function showCloneBtn()
    {

        if (!$this->element->isCloneable()) {
            return false;
        }

        if (!$this->user->can('clone', $this->element)) {
            return false;
        }

        return true;
    }
}