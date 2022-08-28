<?php

namespace App\Mainframe\Modules\Tenants\Traits;

use App\Tenant;

trait TenantTrait
{

    /**
     * Global tenant elements are accessible by all tenants.
     * Dictionary tables should set this value to elements that should be
     * accessible and usable by all tenants.
     *
     * @return int
     */
    public static function globalTenantId()
    {
        if (defined(Tenant::class.'::GLOBAL_TENANT_ID')) {
            return Tenant::GLOBAL_TENANT_ID;
        }
        return 0;
    }

    /**
     * @return null|int
     */
    public static function nonTenantId()
    {
        if (defined(Tenant::class.'::NON_TENANT_ID')) {
            return Tenant::NON_TENANT_ID;
        }
        return null;
    }

    /**
     * Keeps the 'tenants' table updated based on changes in auxilieary tenant
     *
     * @return void
     */
    public static function syncWithAuxiliaryTenant($element)
    {
        // dd('here');
        $tenant = Tenant::updateOrCreate(
            ['id' => $element->id], [
            'name' => $element->name
        ]);

        $element->tenant_id = $tenant->id;
        $element->saveQuietly();
    }

}