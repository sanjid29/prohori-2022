<?php

namespace App\Projects\Prohori\Modules\Groups;

/** @mixin GroupProcessor $this */
trait GroupProcessorHelper
{
    /*
    |--------------------------------------------------------------------------
    | Functions for deriving immutables & allowed transitions
    |--------------------------------------------------------------------------
    */
    /* Further customize immutables and allowed value transitions*/
    // public function immutables(){return $this->immutables; }
    // public function transitions(){return $this->transitions; }

    /*
    |--------------------------------------------------------------------------
    | Other helper functions
    |--------------------------------------------------------------------------
    */
    // Todo: Other helper functions

    /*
    |--------------------------------------------------------------------------
    | Validation helper functions
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