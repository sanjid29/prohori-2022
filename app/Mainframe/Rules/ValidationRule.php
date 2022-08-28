<?php

namespace App\Mainframe\Rules;

use App\Mainframe\Features\Modular\BaseModule\BaseModule;
use Illuminate\Contracts\Validation\Rule;

class ValidationRule implements Rule
{
    /** @var BaseModule|mixed|null */
    public $element;

    public function __construct($element = null)
    {
        $this->element = $element;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $element = $this->element;

        if ($element->$attribute != $value) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Error with :attribute';
    }
}