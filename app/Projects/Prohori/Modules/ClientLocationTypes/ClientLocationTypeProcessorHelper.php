<?php

namespace App\Projects\Prohori\Modules\ClientLocationTypes;

/** @mixin ClientLocationTypeProcessor $this */
trait ClientLocationTypeProcessorHelper
{
    /*
    |--------------------------------------------------------------------------
    | Section: Functions for deriving immutables & allowed transitions
    |--------------------------------------------------------------------------
    */
    /* Further customize immutables and allowed value transitions*/
    // public function immutables(){return $this->immutables; }
    // public function transitions(){return $this->transitions; }

    /*
    |--------------------------------------------------------------------------
    | Section: Other helper functions
    |--------------------------------------------------------------------------
    */
    // Todo: Other helper functions

    /*
    |--------------------------------------------------------------------------
    | Section: Validation helper functions
    |--------------------------------------------------------------------------
    */

    // Todo: Functions for validation
    /**
     * Example code
     *
     * @return $this
     */
    public function checkName()
    {
        $element = $this->element; // Short hand variable.

        if ($element->name == 'Joker') { // Validate
            $this->error('Name can not be Joker', 'name'); // Raise error
        }

        return $this; // Return the same object for validation method chaining
    }
}