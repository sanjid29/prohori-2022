<?php

namespace App\Projects\Prohori\Modules\AdminArea\Upazilas;

use App\Projects\Prohori\Features\Modular\BaseModule\BaseModulePolicy;
use App\Upazila;

class UpazilaPolicy extends BaseModulePolicy
{
    // Note: Pull in necessary traits from relevant mainframe class

    /**
     * view-any
     *
     * @param  \App\User  $user
     * @return mixed
     */
    // public function viewAny($user) { return parent::viewAny($user); }

    /**
     * view
     *
     * @param  \App\User  $user
     * @param  \App\Upazila  $element
     * @return mixed
     */
    // public function view($user, $element) {if (! parent::view($user, $element)) {return false;} return true;}
    // public function create($user, $element = null) {if (! parent::create($user, $element)) {return false;} return true;}
    // public function update($user, $element) {if (! parent::update($user, $element)) {return false;} return true;}
    // public function delete($user, $element) {if (! parent::delete($user, $element)) {return false;} return true;}
    // public function restore($user, $element) {if (! parent::restore($user, $element)) {return false;} return true;}
    // public function forceDelete($user, $element) {if (! parent::forceDelete($user, $element)) {return false;} return true;}
    // public function clone($user, $element) {if (! parent::clone($user, $element)) {return false;} return true;}

}
