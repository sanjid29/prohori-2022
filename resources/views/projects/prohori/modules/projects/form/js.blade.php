<?php
/**
 * @var \App\Module $module
 * @var \App\User $user
 * @var string $formState create|edit
 * @var array $formConfig
 * @var string $uuid Only available for create
 * @var bool $editable
 * @var array $immutables
 * @var \App\Project $element
 * @var \App\Project $project
 * @var \App\Projects\Prohori\Modules\Projects\ProjectViewProcessor $view
 */
?>
<script>
    /*
    |--------------------------------------------------------------------------
    | Common - creating and updating
    |--------------------------------------------------------------------------
    */
    // $('select').select2(); // Make all select2

    // Redirection after delete
    @if($element->parent_id)
    $('.delete-cta button[name=genericDeleteBtn]').attr('data-redirect_success', '{!! route('parent.edit',$element->parent_id) !!}')
    @endif

    // Validation
    addValidationRules();
    enableValidation('{{$module->name}}');

    /*
    |--------------------------------------------------------------------------
    | creating
    |--------------------------------------------------------------------------
    */
    @if($element->isCreating())
    // Todo: write codes here.
    @endif

    /*
    |--------------------------------------------------------------------------
    | updating
    |--------------------------------------------------------------------------
    */
    @if($element->isUpdating())
    // Todo: write codes here.
    // Redirection after saving
    // $('#{{$module->name}}-redirect-success').val('#'); //  # Stops redirection
    @endif
    /*
    |--------------------------------------------------------------------------
    | List of functions
    |--------------------------------------------------------------------------
    */
    /**
     * Add CSS for validation rules
     */
    function addValidationRules() {
        $("input[name=name]").addClass('validate[required]');
    }
</script>