<?php

namespace App\Mainframe\Features\Form\Select;

use App\Mainframe\Features\Form\Input;

class Select extends Input
{
    public $options;

    /**
     * @var array|mixed
     */
    public $nullOption;
    public $nullOptionText;
    public $zeroOption;
    public $zeroOptionText;

    /**
     * Input constructor.
     *
     * @param  \App\Mainframe\Features\Modular\BaseModule\BaseModule  $element
     * @param  array  $var
     */
    public function __construct($var = [], $element = null)
    {
        parent::__construct($var, $element);

        $this->options = $this->var['options'] ?? [];

        // Allow null/zero selection
        $this->nullOption = $this->var['null_option'] ?? true;
        $this->nullOptionText = $this->var['null_option_text'] ?? '-';
        $this->zeroOption = $this->var['zero_option'] ?? false;
        $this->zeroOptionText = $this->var['zero_option_text'] ?? '-All-';
        // $this->options[null] = '-'; // By default laravel Form::select adds and empty selection for null

        if (!$this->isEditable) {
            $this->params = array_merge(['disabled' => 'disabled'], $this->params);
        }

    }

    /**
     * Value
     *
     * @return null|array|\Illuminate\Http\Request|string
     */
    public function value()
    {
        // if ($this->nullOption || $this->zeroOption) {
        //     return $this->value;
        // }
        return parent::value();
    }

    /**
     * Print value
     *
     * @return null|array|\Illuminate\Http\Request|string
     */
    public function print()
    {
        return $this->options()[$this->value()] ?? '';
    }

    /**
     * Check if input has multiple select
     *
     * @return bool
     */
    public function isMultiple()
    {
        return isset($this->params['multiple']) && $this->params['multiple'] == 'multiple';
    }

    /**
     * Check if null option should be shown
     *
     * @return bool
     */
    public function showNullOption()
    {
        return !$this->isMultiple() && $this->nullOption;
    }

    /**
     * Check if zero option should be shown
     *
     * @return bool
     */
    public function showZeroOption()
    {
        return $this->zeroOption;
    }

}