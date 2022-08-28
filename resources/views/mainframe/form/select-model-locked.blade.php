<?php
/*
|--------------------------------------------------------------------------
| Vars
|--------------------------------------------------------------------------
|
| This view partial can be included with a config variable $var.
| $var is an array and can have following keys.
| if a $var is not set the default value will be use.
|
*/
/**
 *      $var['div'] ?? 'col-md-3';
 *      $var['label']           ?? null;
 *      $var['label_class']     ?? null;
 *      $var['type']            ?? null;
 *      $var['value']           ?? null;
 *      $var['name']            ?? Str::random(8);
 *      $var['params']          ?? [];  // These are the html attributes like css, id etc for the field.
 *      $var['editable']        ?? true;
 *
 * @var \Illuminate\Support\ViewErrorBag $errors
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element
 * @var bool $editable
 * @var array $immutables
 */
$var = \App\Mainframe\Features\Form\Form::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null, $immutables ?? null);
$input = new App\Mainframe\Features\Form\Select\SelectModel($var);
?>

@if($input->isHidden)
    {{ Form::hidden($input->name, $input->value()) }}
@else
    <div class="{{$input->containerClasses()}}" id="{{$input->uid}}">

        {{-- label --}}
        @include('mainframe.form.includes.label')

        {{-- input --}}
        @if($element->isUpdating())
            <div style="position: relative; width: 100%">
                <div style="position: absolute; top: 0; left: 0; width: 100%">
                    {{ Form::select($input->name, $input->options, $input->value(), $input->params) }}
                </div>
                <div class="{{$input->params['class']}} on-click-hide-{{$input->uid}}"
                     style="position: relative; top: 0; left: 0; width: 100%; height:30px; margin-top:5px; opacity: .3">
                    <i class="fa fa-lock pull-right on-click-hide-btn-{{$input->uid}}" style="margin-right: 10px; color:red"></i>
                </div>
            </div>
        @else
            {{ Form::select($input->name, $input->options, $input->value(), $input->params) }}
        @endif

        {{-- Place hidden input if not editable--}}
        @if(!$input->isEditable)
            {{ Form::hidden($input->name, $input->value()) }}
        @endif

        {{-- Error --}}
        @include('mainframe.form.includes.show-error')

    </div>
@endif

@section('js')
    @parent
    <script>
        $('.on-click-hide-btn-{{$input->uid}}').click(function () {
            $('.on-click-hide-{{$input->uid}}').hide();
        })
    </script>
@endsection

<?php unset($input); ?>