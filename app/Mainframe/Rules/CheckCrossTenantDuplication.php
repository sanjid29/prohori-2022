<?php

namespace App\Mainframe\Rules;

use App\Tenant;

class CheckCrossTenantDuplication extends ValidationRule
{
    public $element;

    public function __construct($element = null)
    {
        parent::__construct($element);
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
        $query = $element->where('name', $element->name);

        if ($element->tenant_id) {
            $query->where(function ($q) use ($element) {
                /** @var \Illuminate\Database\Query\Builder $q */
                $q->whereNull('tenant_id')
                    ->orWhere('tenant_id', Tenant::globalTenantId())
                    ->orWhere('tenant_id', $element->tenant_id);
            });
        }

        if ($element->isEditing()) {
            $query->where('id', '!=', $element->id);
        }

        if ($exists = $query->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}