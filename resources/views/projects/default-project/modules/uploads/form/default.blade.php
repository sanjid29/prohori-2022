@extends('projects.default-project.layouts.module.form.template')

<?php
/**
 * @var \App\Mainframe\Modules\Modules\Module $module
 * @var \App\User $user
 * @var \App\Upload $element
 * @var string $formState create|edit
 * @var array $formConfig
 * @var string $uuid Only available for create
 * @var bool $editable
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $uploadable
 * @var \App\Mainframe\Modules\Uploads\UploadViewProcessor $view
 */
?>
@section('content-top')
    @parent
    @if($element->uploadable)
        @include('mainframe.form.back-link',['var'=>['element'=>$element->uploadable]])
    @endif
    <div class="clearfix"></div>
@endsection

@section('content')
    <div class="col-md-10 no-padding">
        @if(($formState == 'create'))
            {{ Form::open($formConfig) }} <input name="uuid" type="hidden" value="{{$uuid}}"/>
        @elseif($formState == 'edit')
            {{ Form::model($element, $formConfig)}}
        @endif

        {{-- ******** Form inputs: starts *********************************** --}}
        {{-- **************************************************************** --}}

        @include('form.text',['var'=>['name'=>'name','label'=>'Name','div'=>'col-md-6']])
        @include('form.text',['var'=>['name'=>'type','label'=>'Type']])
        <div class="clearfix"></div>
        @include('form.text',['var'=>['name'=>'ext','label'=>'Extension', 'div'=>'col-md-2']])
        @include('form.number',['var'=>['name'=>'order','label'=>'Order','div'=>'col-md-2']])
        @include('form.text',['var'=>['name'=>'bytes','label'=>'Bytes','div'=>'col-md-2']])
        <div class="clearfix"></div>
        @include('form.textarea',['var'=>['name'=>'description','label'=>'Description','div'=>'col-md-6']])

        <div class="clearfix"></div>
        <?php
        $value = 'URL: ';
        if ($element->isPublic()) {
            $value = "<span class='badge badge-danger'>PUBLIC</span> ".$element->url;
        } else {
            $value = $element->downloadUrl();
        }
        ?>
        <div class="form-group col-md-9">
            <label class="control-label">URL</label>
            <span class="form-control readonly">{!! $value !!}</span>
        </div>

        <div class="clearfix"></div>
        @include('form.is-active')
        {{--  ******** Form inputs: ends ************************************ --}}


        <div class="clearfix"></div>
        <div class="col-md-6 no-padding-l">
            <small>Update existing file</small>
            @include('mainframe.layouts.module.form.includes.features.uploads.update-uploaded-file')
        </div>

        @include('form.action-buttons',['float'=>false])

        {{ Form::close() }}
    </div>
@endsection


@section('js')
    @parent
    @include('projects.default-project.modules.uploads.form.js')
    @if($element->uploadable)
        <script>
            $('.delete-btn').attr('data-redirect_success', '{!! $element->uploadable->editUrl() !!}')
        </script>
    @endif
@endsection