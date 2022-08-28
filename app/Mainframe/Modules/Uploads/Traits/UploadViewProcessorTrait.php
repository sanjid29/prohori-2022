<?php

namespace App\Mainframe\Modules\Uploads\Traits;

trait UploadViewProcessorTrait
{

    /**
     * Make following readonly in frontend
     *
     * @return string[]
     */
    public function immutables()
    {
        return array_merge(parent::immutables(), [
            'type',
            'ext',
            'bytes',
        ]);

    }
}