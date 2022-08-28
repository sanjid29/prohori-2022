@extends('projects.prohori.layouts.module.form.template')
<?php
/**
 * @var \App\Module $module
 * @var \App\User $user
 * @var string $formState create|edit
 * @var array $formConfig
 * @var string $uuid Only available for create
 * @var bool $editable
 * @var array $immutables
 * @var \App\Group $element
 * @var \App\Group $group
 * @var \App\Tenant $tenant
 * @var \App\Projects\Prohori\Modules\Groups\GroupViewProcessor $view
 */
$group = $element;
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
        @include('form.text',['var'=>['name'=>'title','label'=>'Title']])
        @include('form.text',['var'=>['name'=>'name','label'=>'System Name']])
        @include('form.is-active')
        {{--    Form inputs: ends    --}}
        <div class="clearfix"></div>
        @if($formState === 'edit')
            @include('mainframe.modules.groups.form.permission-blocks')
        @endif

        @include('form.action-buttons')

        {{ Form::close() }}
    </div>
@endsection

@section('js')
    @parent
    @include('projects.prohori.modules.groups.form.js')
@endsection