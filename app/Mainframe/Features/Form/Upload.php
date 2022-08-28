<?php

namespace App\Mainframe\Features\Form;

use Illuminate\Support\Str;

class Upload extends Input
{
    /** @var string */
    public $containerClass;
    /** @var null|mixed */
    public $moduleId;
    /** @var null|mixed */
    public $elementId;
    /** @var null|mixed */
    public $elementUuid;
    /** @var null|mixed */
    public $type;
    /** @var int */
    public $limit;
    /** @var null|int */
    public $tenantId;
    /** @var string */
    public $uploadBoxId;
    /** * @var string */
    public $uploadableType;
    /** * @var string */
    public $postUrl;
    /** * @var string */
    public $bucket;
    /** * @var string */
    public $zipDownload = false;

    /**
     * Input constructor.
     *
     * @param  \App\Mainframe\Features\Modular\BaseModule\BaseModule  $element
     * @param  array  $var
     */
    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);

        $this->containerClass = $this->var['container_class'] ?? $this->var['div'] ?? '';

        if ($this->element) {
            $this->elementUuid = $this->element->uuid;
            $this->uploadableType = $this->var['uploadable_type'] ?? get_class($this->element);
        }

        if ($this->element && $this->element->isUpdating()) {
            $this->elementId = $this->element->id;
            $this->tenantId = $this->element->tenant_id ?? null;
        }

        $this->moduleId = $this->var['module_id'] ?? $this->element->module()->id;

        $this->elementId = $this->var['element_id'] ?? $this->elementId;
        $this->elementUuid = $this->var['element_uuid'] ?? $this->elementUuid;
        $this->tenantId = $this->var['tenant_id'] ?? $this->tenantId;
        $this->bucket = $this->var['bucket'] ?? trim(config('mainframe.config.upload_root'), "\\/ ");;

        $this->zipDownload = $this->var['zip_download'] ?? $this->zipDownload;
        $this->type = $this->var['type'] ?? null;
        $this->limit = $this->var['limit'] ?? 999;
        $this->postUrl = $this->var['url'] ?? route('uploads.store');
        $this->uploadBoxId = $this->var['upload_box_id'] ?? 'uploadBox'.Str::random(8);

    }

    public function postUrl()
    {
        return $this->postUrl ?: route('uploads.store');
    }

    public function bucket()
    {
        return $this->bucket;
    }

    public function zipDownloadUrl()
    {
        return route('download.zip', ['module_id' => $this->moduleId, 'element_id' => $this->elementId, 'type' => $this->type]);
    }
}