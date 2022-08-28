@extends('projects.prohori.layouts.centered.template')
<?php
/** @var \App\Mainframe\Modules\Groups\Group $group */
?>

@section('content')

    <h4>{{ $group->title }} Registration </h4>

    <div class="card-body">

        {{ Form::open(['route' => ['register',$group->name],'class'=>"user-registration-form", 'name'=>'user_registration_form']) }}

        {{-- <input type="hidden" name="group_ids[]" value="{{$group->id}}"> --}}
        @include('form.text',['var'=>['name'=>'first_name','label'=>'First Name', 'div'=>'col-sm-12']])
        @include('form.text',['var'=>['name'=>'last_name','label'=>'Surname', 'div'=>'col-sm-12']])
        @include('form.text',['var'=>['name'=>'mobile','label'=>'Contact No', 'div'=>'col-sm-12']])
        @include('form.text',['var'=>['name'=>'email','label'=>'Email Address', 'div'=>'col-sm-12']])
        @include('form.text',['var'=>['name'=>'password','type'=>'password','label'=>'Password','value'=>'', 'div'=>'col-sm-12']])
        @include('form.text',['var'=>['name'=>'password_confirmation','type'=>'password','label'=>'Confirm Password', 'div'=>'col-sm-12']])

        <div class="form-group row mb-0">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block lb-bg">{{ __('Register') }}</button>
            </div>
        </div>

        {{ Form::close() }}

        <div class="col-md-12 text-center" style="margin: 15px 0">
            <a href="{{ route('login') }}">
                {{ __('Already have account? Log in.') }}
            </a>
        </div>
        <div class="clearfix"></div>

    </div>

@endsection

@section('js')
    @parent
    <script>
        $("input[name=first_name]").addClass('validate[required]');
        $("input[name=email]").addClass('validate[required]');
        $("input[name=mobile]").addClass('validate[required]');
        $("input[name=password]").addClass('validate[required]');
        $("input[name=password_confirmation]").addClass('validate[required]');

        showRequiredIcons();

        $('form[name=user_registration_form]').validationEngine({
            prettySelect: true,
            promptPosition: "topLeft",
            scroll: true
        });
    </script>

@endsection