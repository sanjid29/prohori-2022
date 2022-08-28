@extends('projects.prohori.layouts.module.form.template')

<?php
/**
 * @var \App\User $element
 * @var string $formState create|edit
 * @var string $formState
 * @var array $formConfig
 * @var string $uuid Only available for create
 * @var bool $editable
 * @var \App\Mainframe\Modules\Modules\Module $module
 * @var \App\Projects\Prohori\Modules\Users\UserViewProcessor $view
 */
?>

@section('content')
    <div class="col-md-12 col-lg-10 no-padding">
        @if(($formState == 'create'))
            {{ Form::open($formConfig) }} <input name="uuid" type="hidden" value="{{$uuid}}"/>
        @elseif($formState == 'edit')
            {{ Form::model($element, $formConfig)}}
        @endif

        {{--    Form inputs: starts    --}}
        {{--   --------------------    --}}
        @include('form.text',['var'=>['name'=>'email','label'=>'Email']])
        @include('form.datetime',['var'=>['name'=>'email_verified_at','label'=>'Email verified at']])

        <div class="clearfix"></div>
        @include('form.text',['var'=>['name'=>'first_name','label'=>'First Name', 'tooltip'=>'First Name']])
        @include('form.text',['var'=>['name'=>'last_name','label'=>'Last Name']])

        {{-- show password only for editable--}}
        @if($editable)
            <div class="clearfix"></div>
            <h3>Password</h3>
            @include('form.text',['var'=>['name'=>'password','type'=>'password','label'=>'New password','value'=>'']])
            @include('form.text',['var'=>['name'=>'password_confirmation','type'=>'password','label'=>'Confirm new password']])
        @endif

        <div class="clearfix"></div>
        <h3>Group</h3>
        <?php
        $var = [
            'name' => 'group_ids',
            // 'label' => 'Group',
            'model' => new \App\Group,
            'name_field' => 'title',
            'params' => ['id' => 'groups'],
            'container_class' => 'col-sm-6',
            'data_attributes' => ['name']
        ];
        ?>
        @include('form.select-model-multiple', compact('var'))
        <div class="clearfix"></div>

        <div class="depends-on-groups {{\App\User::USER_GROUP}}" style="display: none">
            {{-- Section: Show input fields for specific user group--}}
        </div>
        <div class="depends-on-groups {{\App\User::TENANT_ADMIN_GROUP}}" style="display: none">
            {{-- Section: Show input fields for specific user group--}}
        </div>

        <div class='clearfix'></div>
        {{-- @include('projects.prohori.modules.users.form.includes.token-fields')--}}
        <div class="clearfix"></div>
        @include('form.is-active')
        @include('form.action-buttons')

        {{ Form::close() }}
    </div>
@endsection

@section('content-bottom')
    @parent
    <div class="col-md-6 no-padding-l">
        <h3>Upload profile pic</h3>
        <small>Upload one or more files</small>
        @include('form.uploads',['var'=>['type'=>\App\Upload::TYPE_PROFILE_PIC,'bucket'=>'public','limit'=>1]])
    </div>
@endsection

@section('js')
    @parent
    @include('projects.prohori.modules.users.form.js')
    <script>
        /***********************************************
         * Show a div block based on some selection
         ************************************************/
        $('#groups').on('change', function () {
            var identifiers = getMultiSelectAsArray('#groups', 'data-name');
            $('.depends-on-groups').hide(); // Hide all dependant group
            _(identifiers).forEach(function (identifier) { // This is a low-dash function :)  https://lodash.com/docs/2.4.2#forEach
                if (identifier.length) {
                    $('div.' + identifier).show();
                }
            })
        }).trigger('change');
    </script>
@endsection