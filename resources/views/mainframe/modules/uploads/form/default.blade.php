@extends('mainframe.layouts.module.form.template')

@section('content-top')
    @parent
    @include('mainframe.form.back-link',['var'=>['element'=>$element->uploadable]])
@endsection

@section('content')
    <div class="col-md-10 no-padding">

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
    @include('mainframe.modules.uploads.form.js')
    <script>
        $('.delete-btn').attr('data-redirect_success', '{!! $element->uploadable->editUrl() !!}')
    </script>
@endsection