<?php

namespace App\Mainframe\Features\Form\Text;

use App\Mainframe\Features\Form\Input;

class TextArea extends Input
{
    public $editorConfig;

    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);

        $this->containerClass = $this->var['container_class'] ?? $this->var['div'] ?? 'col-md-6';
        $this->editorConfig = $var['editorConfig'] ?? $var['editor_config'] ?? 'editor_config_basic';
    }

    /**
     * Text area value. Make everything string to show
     *
     * @return array|false|\Illuminate\Http\Request|string|null
     */
    public function value()
    {
        $value = parent::value();

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return $value;
    }
}