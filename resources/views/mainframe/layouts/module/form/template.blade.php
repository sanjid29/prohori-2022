@extends($view->defaultTemplate())
<?php
/**
 * @var \App\Mainframe\Modules\Modules\Module $module
 * @var \App\User $user
 * @var \App\User $element
 * @var string $formState create|edit
 * @var array $formConfig
 * @var string $uuid Only available for create
 * @var bool $editable
 */
?>

@section('title')
    @include('mainframe.layouts.module.form.includes.title-with-name')
@endsection

@section('content')
    <div class="col-md-12 no-padding">

        @if(($formState == 'create'))
            {{ Form::open($formConfig) }} <input name="uuid" type="hidden" value="{{$element->uuid}}"/>
        @elseif($formState == 'edit')
            {{ Form::model($element, $formConfig)}}
        @endif

        @yield('form-fields')

        @include('form.action-buttons')

        {{ Form::close() }}
    </div>
@endsection

@section('js')
    @parent
    @include('mainframe.layouts.module.form.includes.js')
@endsection