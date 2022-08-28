<script>
    /*
    |--------------------------------------------------------------------------
    | Common - creating and updating
    |--------------------------------------------------------------------------
    */

    $('select[id=groups]').select2();

    /**
     * Assigns validation rules during saving (both creating and updating)
     */
    addValidationRules(); // Assign validation classes/rules
    enableValidation('{{$module->name}}'); // Enable Ajax based form validation.
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
    // $('#{{$module->name}}-redirect-success').val('#'); //  # Stops redirection after save
    @endif
    /*
    |--------------------------------------------------------------------------
    | List of functions
    |--------------------------------------------------------------------------
    |
    */
    /**
     * Assigns validation rules during saving (both creating and updating)
     */
    function addValidationRules() {
        $("input[name=email]").addClass('validate[required]');
        $("select[name=group_ids]").addClass('validate[required]');
    }


</script>