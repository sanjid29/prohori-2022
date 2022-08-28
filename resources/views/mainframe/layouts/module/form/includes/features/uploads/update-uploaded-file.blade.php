<?php

/**
 * @var \App\Upload $element
 * @var bool $editable
 * @var array $immutables
 * @var array $var
 */

$var = $var ?? ['limit' => 1];

$var = \App\Mainframe\Features\Form\Form::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null,
    $immutables ?? null, $hiddenFields ?? null);
$input = new App\Mainframe\Features\Form\Upload($var);

$uploads = [$element];


?>

{{-- upload div + form --}}
<div class="{{$input->containerClass}} {{$input->uid}}" id="{{$input->uid}}">
    @if(user()->can('update',$element->uploadable))

        <div id="{{$input->uploadBoxId}}" class="uploads-container">
            @csrf
            <input type="hidden" name="ret" value="json"/>
            <input type="hidden" name="upload_id" value="{{$element->id}}"/>

            <div class="file-uploader">Upload file</div>
        </div>

    @endif

    {{-- uploaded file list --}}
    @if(count($uploads))
        @include('mainframe.layouts.module.form.includes.features.uploads.uploads-list-default',['uploads'=>$uploads,'input'=>$input])
    @endif
</div>

{{-- js --}}
@section('js')

    @if($input->isEditable)
        <script>
            {{--initUploader("{{$input->uploadBoxId}}", "{{ route($module->name.'.uploads.store', $element->id)}}");--}}
            initUploader("{{$input->uploadBoxId}}", "{!! route('uploads.update-file') !!}");
        </script>
    @endif

    @parent
@endsection

<?php unset($input); ?>