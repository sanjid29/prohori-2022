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

$var = \App\Mainframe\Features\Form\Form::setUpVar($var, $errors ?? null, $element ?? null, $editable ?? null, $immutables ?? null, $hiddenFields ?? null);
$input = new \App\Mainframe\Features\Form\Parts($var);
?>

<div id="{{$input->vueElement()}}" class="{{$input->containerClasses()}}" style="clear: both">

    <h4>{{$input->label}}</h4>
    <div class="clearfix"></div>

    <table id="partsTable" class="table partBuilderTable">

        <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        </thead>

        <tr v-for="(part, i) in parts" :key="i">
            <td style="width: 20%">
                <button type="button" v-on:click="removeRow(i)" class="btn btn-default btn-sm remove-package pull-left">
                    <i class="fa fa-close"></i>
                </button>
                <input type="text" :name="'parts['+i+'][key]'" v-model="part.key"
                       class="validate[required] form-control pull-right" style="width: 80%" placeholder="key"/>
            </td>
            <td>
                @if($input->type()=='textarea')
                    <textarea type="text" :name="'parts['+i+'][value]'" v-model="part.value"
                              class="validate[required] form-control " placeholder="value">

                </textarea>
                @else
                    <input type="text" :name="'parts['+i+'][value]'" v-model="part.value"
                           class="validate[required] form-control " placeholder="value"/>
                @endif
            </td>
        </tr>
    </table>
    <div class="clearfix"></div>
    <button type="button" v-on:click="addRow" class="btn btn-default"><i class="fa fa-plus"></i> &nbsp;Add</button>

    @include('form.hidden',['var'=>['name'=>$input->name,'params'=>['v-model'=>'partsString'],'value'=>($input->value() ?? '[]')]])
</div>

@section('js')
    @parent
    <script>
        var {{$input->vueElement()}} =
            new Vue({
                el: '#{{$input->vueElement()}}',
                data: {
                    parts: JSON.parse($("#{{$input->name}}").val())
                },

                computed: {
                    partsString: function () {
                        return JSON.stringify(this.parts);
                    },
                },
                methods: {
                    addRow: function () {
                        this.parts.push({key: '', value: ''});
                    },
                    removeRow: function (index) {
                        this.parts.splice(index, 1);
                    },
                }
            });
    </script>

@endsection