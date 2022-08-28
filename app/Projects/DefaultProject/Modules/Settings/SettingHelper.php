<?php

namespace App\Projects\DefaultProject\Modules\Settings;

/** @mixin Setting $this */
trait SettingHelper
{
    /*
    |--------------------------------------------------------------------------
    | Autofill and functions to calculated field updates
    |--------------------------------------------------------------------------
    */
    /**
     * Populate model
     * Fill data and set calculated data in fields for saving the module
     * This can depend of supporting fillFunct, setFunct,calculateFunct
     * return $this
     */
    public function populate()
    {
        // Example code
        // $this->fillAddress()->setAmounts();
        return $this;
    }



    /*
    |--------------------------------------------------------------------------
    | Non-static helper functions
    |--------------------------------------------------------------------------
    */
    // Todo: Write non-static helper functions here

    /*
    |--------------------------------------------------------------------------
    | Static helper functions
    |--------------------------------------------------------------------------
    */
    // Todo: static helper functions

    /*
    |--------------------------------------------------------------------------
    | Ability to create, edit, delete or restore
    |--------------------------------------------------------------------------
    |
    | An element can be editable or non-editable based on it's internal status
    | This is not related to any user, rather it is a model's individual sate
    | For example - A confirmed quotation should not be editable regardless
    | Of who is attempting to edit it.
    |
    */

    // public function isViewable() { return true; }
    // public function isCreatable() { return true; }
    // public function isEditable() { return true; }
    // public function isDeletable() { return false; }

    public function isCloneable() { return true; }

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    */
    // /**
    //  * Notify admins when quote is accepted
    //  */
    // public function sendSomeNotification()
    // {
    //     Notification::send($users, new NotificationClass($this));
    // }

}