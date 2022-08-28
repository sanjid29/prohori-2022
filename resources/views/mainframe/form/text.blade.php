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
 * @var array $var
 */

use App\Mainframe\Features\Form\Text\InputText;

$var = \App\Mainframe\Features\Form\Form::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null,
    $immutables ?? null, $hiddenFields ?? null);
$input = new InputText($var);
?>

@if($input->isHidden)
    {{ Form::hidden($input->name, $input->value(), $input->params) }}
@else
    <div class="{{$input->containerClasses()}}" id="{{$input->uid}}">
        {{-- label --}}
        @include('mainframe.form.includes.label')

        @if($input->isEditable)
            @if($input->type == 'password')
                {{ Form::password($input->name, $input->params) }}
            @elseif(($input->type == 'number'))
                {{ Form::number($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'email'))
                {{ Form::email($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'tel'))
                {{ Form::tel($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'date'))
                {{ Form::date($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'time'))
                {{ Form::time($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'week'))
                {{ Form::week($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'month'))
                {{ Form::month($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'year'))
                {{ Form::year($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'url'))
                {{ Form::url($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'color'))
                {{ Form::color($input->name, $input->value(), $input->params) }}
            @elseif(($input->type == 'range'))
                {{ Form::range($input->name, $input->value(), $input->params) }}
            @else
                {{ Form::text($input->name, $input->value(), $input->params) }}
            @endif
        @else
            @include('mainframe.form.includes.read-only-view')
        @endif

        {{-- Error --}}
        @include('mainframe.form.includes.show-error')
    </div>
@endif

<?php unset($input) // Make sure to clear $input var ?>