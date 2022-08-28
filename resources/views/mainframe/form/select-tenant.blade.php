<?php
/**
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element *
 *
 * view variables
 * ---------------------------
 * @var  bool $hide_for_tenant_users
 * @var  string $tenant_field_name
 * @var  string $tenant_field_label
 * @var  string $null_option_text
 */
$var = [
    'name' => $tenant_field_name ?? 'tenant_id',
    'label' => $tenant_field_label ?? 'Tenant',

    'model' => \App\Tenant::class,
    'value' => $element->{$tenant_field_name},

    'null_option' => $null_option ?? $element->showNonTenantElements(),
    'null_option_text' => $null_option_text ?? '-',
    'zero_option' => $zero_option ?? $element->showGlobalTenantElements(),
    'zero_option_text' => $zero_option_text ?? '-- All --'
];

if (user()->ofTenant()) {
    $var = array_merge($var, [
        'value' => $element->isCreating() ? user()->{$tenant_field_name} : $element->{$tenant_field_name},
        'editable' => false,
        'hidden' => $hide_for_tenant_users ?? false, // Hide for tenant
    ]);
}
?>
@include('form.select-model',['var'=>$var])

@unset($var,$label,$hide_for_tenant)
