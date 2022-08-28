@extends('mainframe.layouts.module.form.template')

<?php
/**
 * @var \App\Module $module
 * @var \App\User $user
 * @var string $formState create|edit
 * @var array $formConfig
 * @var string $uuid Only available for create
 * @var bool $editable
 * @var array $immutables
 * @var \App\Content $element
 * @var \App\Content $content
 * @var \App\Mainframe\Modules\Contents\ContentViewProcessor $view
 */
$content = $element;
?>
@section('css')
    @parent
    <style>
        #partsTable {margin-bottom: 0;}
        #partsTable > tbody > tr > td {border-top: 0;}
    </style>
@endsection

@section('content')
    <div class="col-md-12 no-padding">
        @if(($formState == 'create'))
            {{ Form::open($formConfig) }} <input name="uuid" type="hidden" value="{{$uuid}}"/>
        @elseif($formState == 'edit')
            {{ Form::model($element, $formConfig)}}
        @endif

        {{---------------|  Form input start |-----------------------}}
        @include('form.text',['var'=>['name'=>'name','label'=>'Name']])
        @include('form.text',['var'=>['name'=>'key','label'=>'Key <code>key-format</code>']])
        <div class="clearfix"></div>
        @include('form.text',['var'=>['name'=>'title','label'=>'Title', 'div'=>'col-md-12']])
        @include('form.textarea',['var'=>['name'=>'body','label'=>'Body', 'div'=>'col-md-12', 'class'=>'ckeditor']])

        @include('form.is-active')
        @include('form.tags',['var'=>['name'=>'tags','label'=>'Tags', 'div'=>'col-md-12']])
        {{---------------|  Form input start |-----------------------}}


        <?php
        $var = [
            'name' => 'parts',
            'label' => 'Parts',
        ]
        ?>
        @include('mainframe.form.parts', compact('var'))
        @include('form.action-buttons')
        {{ Form::close() }}
    </div>
@endsection

@section('content-bottom')
    @parent
    <div class="col-md-6 no-padding-l">
        <h5>File upload</h5><small>Upload one or more files</small>
        @include('form.uploads',['var'=>['limit'=>99]])
    </div>
@endsection

@section('js')
    @parent
    @include('mainframe.modules.contents.form.js')
@endsection