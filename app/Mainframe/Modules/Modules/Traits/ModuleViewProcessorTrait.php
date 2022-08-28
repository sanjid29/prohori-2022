<?php

namespace App\Mainframe\Modules\Modules\Traits;

trait ModuleViewProcessorTrait
{

    public function hiddenFields()
    {
        // $this->addHiddenFields(['name']);

        return $this->hiddenFields;
    }

}