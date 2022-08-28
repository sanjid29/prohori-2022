@extends('mainframe.layouts.module.form.template')

<?php
use App\Setting;
/**
 * @var Setting $element
 * @var string $formState
 * @var Setting $formState
 * @var array $formConfig
 * @var string $uuid
 * @var bool $editable
 * @var \App\Mainframe\Modules\Modules\Module $module
 * @var \App\Mainframe\Modules\Settings\SettingViewProcessor $view
 */
$types = Setting::$types;
?>

@section('content')
    <div class="col-md-12 col-lg-10 no-padding">

        @if(($formState == 'create'))
            {{ Form::open($formConfig) }} <input name="uuid" type="hidden" value="{{$uuid}}"/>
        @elseif($formState == 'edit')
            {{ Form::model($element, $formConfig)}}
        @endif
        {{-- Form inputs: starts    --}}
        {{-- --------------------    --}}
        @include('form.text',['var'=>['name'=>'name','label'=>'Name']])
        @include('form.text',['var'=>['name'=>'title','label'=>'Title']])
        @include('form.select-array',['var'=>['name'=>'type','label'=>'type', 'options'=>$types]])
        <div class="clearfix"></div>

        {{--  @include('form.textarea',['var'=>['name'=>'value','label'=>'Value']])--}}
        <div class="clearfix"></div>

        <div class="clearfix"></div>
        <a class="btn btn-default pull-right"
           href="https://jsoneditoronline.org/#right=local.zelija&left=cloud.2c8cda9906ed4a7fa776b6674ab1bcda">
            Use online JSON Formatter
        </a>
        <div class="clearfix"></div>

        <div class="col-md-6 no-padding">
            @include('form.textarea',['var'=>['name'=>'value','label'=>'Value(For array type put JSON)','div'=>'col-md-12']])
            <div class="col-md-12 no-padding-l">
                {{ var_dump($element->getValue()) }}
            </div>
        </div>

        <label class="control-label ">
            Sample JSON for configuring invoice
        </label>
        <pre>
{
    "invoices": [
        {
            "event": "order.status.accepted",
            "percentage": 50,
            "due_after_days": 10
        },
        {
            "event": "order.status.completed",
            "percentage": 50,
            "due_after_days": 30
        }
    ]
}
        </pre>

        @include('form.textarea',['var'=>['name'=>'description','label'=>'Description', 'params'=>['class'=>'ckeditor']]])
        @include('form.is-active')
        {{-- Form inputs: ends --}}

        @include('form.action-buttons')
        {{ Form::close() }}
    </div>
@endsection

@section('content-bottom')
    @parent
    <div class="col-md-6 no-padding-l">
        <h5>File upload</h5>
        <small>Upload one or more files</small>

        @include('form.uploads',['var'=>['limit'=>99, 'bucket'=>'public']])
    </div>
@endsection

@section('js')
    @parent
    @include('mainframe.modules.settings.form.js')
    <script>
        autosize(document.querySelectorAll('textarea'));
    </script>
@endsection