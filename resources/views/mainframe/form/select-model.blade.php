<?php
use App\Mainframe\Features\Form\Form as MfForm;use App\Mainframe\Features\Form\Select\SelectModel;
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
 * @var array $var
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



$var = MfForm::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null, $immutables ?? null,
    $hiddenFields ?? null);
$input = new SelectModel($var);
?>

@if($input->isHidden)
    {{ Form::hidden($input->name, $input->value()) }}
@else
    <div class="{{$input->containerClasses()}}" id="{{$input->uid}}">

        {{-- label --}}
        @include('mainframe.form.includes.label')

        {{-- input --}}
        {{-- {{ Form::select($input->name, $input->options, $input->value(), $input->params) }}--}}

        <?php

        $attributes = "";
        foreach ($input->params as $attr => $val) {
            $attributes .= " $attr='$val' ";
        }


        ?>

        <select name="{!! $input->name  !!}" id="{!! $input->id  !!}" {!! $attributes !!}>


            @if($input->showNullOption())
                <?php
                $selected = "";
                if (in_array(null, Arr::wrap($input->value()))) {
                    $selected = "selected";
                }
                ?>
                <option value="" {!! $selected  !!}>{{$input->nullOptionText}}</option>
            @endif

            @if($input->showZeroOption())
                <?php

                $selected = "";
                if (in_array(0, Arr::wrap($input->value()))) {
                    $selected = "selected";
                }
                ?>
                <option value="0" {!! $selected  !!}>{{$input->zeroOptionText}}</option>
            @endif

            @foreach($input->result() as $option)
                <?php
                /** @var $option */
                $optionVal = $option->{$input->valueField};
                $optionName = $option->{$input->nameField};
                $selected = "";
                if (in_array($optionVal, Arr::wrap($input->value()))) {
                    $selected = "selected";
                }

                $dataAttributes = '';
                foreach ($input->dataAttributes as $attribute) {

                    $dataAttr = "data-{$attribute}";
                    $dataVal = $option->{$attribute};

                    if (!is_string($dataVal)) {
                        $dataVal = json_encode($dataVal);
                    }

                    $dataAttributes .= " $dataAttr= '$dataVal'";
                }
                ?>

                <option value="{!! $optionVal !!}" {{$selected}} {!! $dataAttributes !!}>
                    {!! $optionName !!}
                </option>
            @endforeach
        </select>

        {{-- Place hidden input if not editable--}}
        @if(!$input->isEditable)
            {{ Form::hidden($input->name, $input->value()) }}
        @endif

        {{-- Error --}}
        @include('mainframe.form.includes.show-error')

    </div>
@endif

<?php unset($input); ?>