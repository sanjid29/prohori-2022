<?php

namespace App\Mainframe\Features\Form\Text;

use App\Mainframe\Features\Form\Input;

class InputHidden extends Input
{

    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);
        $this->type = 'hidden';
        $this->containerClass = $this->var['container_class'] ?? $this->var['div'] ?? '';
    }

    /**
     * Hidden input value. Convert
     *
     * @return false|\Illuminate\Http\Request|string|null
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