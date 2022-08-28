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
 *      name_field
 *      value_field
 *      table
 *      query
 *      show_inactive
 *      cache
 *
 * @var \Illuminate\Support\ViewErrorBag $errors
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element
 * @var bool $editable
 * @var array $immutables
 * @var array $var
 */

$var = \App\Mainframe\Features\Form\Form::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null,
    $immutables ?? null, $hiddenFields ?? null);
$input = new \App\Mainframe\Features\Form\Select\SelectModelMultiple($var);
?>

@if($input->isHidden)
    @if(is_array($input->value()))
        @foreach($input->value() as $value)
            {{ Form::hidden($input->name.'[]', $value)}}
        @endforeach
    @endif
@else
    <div class="{{$input->containerClasses()}}" id="{{$input->uid}}" data-parent="{{$input->dataParent}}">

        {{-- label --}}
        @include('mainframe.form.includes.label')

        {{-- input --}}
        {{--        {{ Form::select($input->name.'[]', $input->options(), $input->value(), $input->params) }}--}}

        <?php
        $attributes = "";
        foreach ($input->params as $attr => $val) {
            $attributes .= " $attr='$val' ";
        }
        ?>

        <select name="{!! $input->name  !!}[]" id="{!! $input->id  !!}" {!! $attributes !!} multiple>
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
        {{--
        Ghost input
        ==================================================
        In the case of multiple select, if no option is selected then the empty value
        does not post. As a result the model fills with old value. To avoid this, when
        there is no selection, a blank html input is enabled.
        --}}
        <input type="hidden" name="{{$input->name}}" class="ghost" value="" disabled/>

        {{-- Place hidden input if not editable--}}
        @if(!$input->isEditable)
            @if(is_array($input->value()))
                @foreach($input->value() as $value)
                    {{ Form::hidden($input->name.'[]', $value)}}
                @endforeach
            @endif
        @endif

        {{-- Error --}}
        @include('mainframe.form.includes.show-error')
    </div>
@endif

@section('js')

    <script>
        $('[data-parent={{$input->dataParent}}] select[id={{$input->params['id']}}] ').change(function () {
            if (!$(this).val()) {
                $('[data-parent= {{$input->dataParent}}]  input[class=ghost]')
                    .prop('disabled', false);
            } else {
                $('[data-parent= {{$input->dataParent}}]  input[class=ghost]')
                    .prop('disabled', true);
            }
        });
        $('[data-parent={{$input->dataParent}}] select[id={{$input->params['id']}}] ').trigger('change');
    </script>

    @parent
@endsection

<?php unset($input); ?>