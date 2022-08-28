<?php

namespace App\Mainframe\Features\Form;

class Parts extends Input
{
    /**
     * Input constructor.
     *
     * @param  \App\Mainframe\Features\Modular\BaseModule\BaseModule  $element
     * @param  array  $var
     */
    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);

        $this->type = $var['type'] ?? 'text'; // Can be text or password

        if (!$this->isEditable) {
            $this->params['readonly'] = 'readonly';
        }
    }

    /**
     * Class of the main div
     *
     * @return string
     */
    public function containerClasses()
    {
        return 'col-md-12 no-padding'
            .' '.$this->errors->first($this->name, 'has-error')
            .' '.$this->uid;
    }

    public function vueElement()
    {
        return $this->var['vue_element'] ?? 'PartsBuilder';
    }

    public function type()
    {
        return $this->var['type'] ?? 'input';
    }

    public function value()
    {
        $value = parent::value();

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }

}