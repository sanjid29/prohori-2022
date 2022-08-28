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
use App\Mainframe\Features\Form\Form as MfForm;

$var = MfForm::setUpVar($var, $errors ?? null
    , $element ?? null, $editable ?? null
    , $immutables ?? null, $hiddenFields ?? null);

$input = new \App\Mainframe\Features\Form\Select\SelectAjaxPreviewSearch($var);
$section = 'ajax_search_modal_'.$input->id;

?>
@if($input->isHidden)
    {{ Form::hidden($input->name, $input->value()) }}
@else
    <div class="{{$input->containerClasses()}} ajax-select-container" id="{{$input->uid}}">

        {{-- label --}}
        @include('mainframe.form.includes.label')

        {{-- input --}}
        <div class="clearfix"></div>

        {{--clear button--}}
        <div class="selection-data col-md-12 no-padding-l">
            
            <div class="col-md-{{ $input->isEditable ? '10' : '12'}} no-padding">
                {{ Form::text($input->name, $input->value(), $input->params) }}
                <input name="preload" type="hidden" class="select2-ajax-preload" value="{{$input->preload()}}"/>
            </div>

            @if($input->isEditable)
                <div class="col-md-2 col-sm-3 col-xs-3 no-padding pull-left btn-group">
                    <a id="clear_{{$input->name}}" class="btn btn-default bg-white selectClearBtn"
                       data-target="{{$input->uid}}"
                       href="#">
                        <i class="fa fa-trash"></i>
                    </a>
                    @if($input->advancedSearch)
                        @include($input->modal)
                    @endif
                </div>
            @endif

            @if($input->preview)
                <div class="clearfix"></div>
                <div id="preview_for_{!!$input->id!!}" class="preview col-md-12 no-padding">
                    {{-- Load preview here --}}
                </div>
            @endif

            {{-- Error --}}
            @include('mainframe.form.includes.show-error')
        </div>
    </div>
@endif

@section('js')
    @if(!$input->isHidden)
        <script type="text/javascript">
            initSelectFor{!! $input->id !!}();

            /**
             * Function to instantiate the AJAX select2 for input selection
             * Using function solves the variable instantiation conflict.
             */
            function initSelectFor{!! $input->id !!}() {

                // Local function variable
                // ---------------------------
                var divId = '{!! $input->uid !!}';
                var url = '{!! $input->url !!}';
                var detailsContainer = $('#preview_for_{!!$input->id!!}');

                // Clear selection
                //-----------------
                $("#" + divId + " .selectClearBtn").on('click', function () {
                    $("#" + divId + " input.ajax").select2("val", "");
                    detailsContainer.html("");
                });

                // Init Ajax
                //-----------------
                $("#" + divId + " input.ajax").select2({
                    minimumInputLength: {{$input->minimumInputLength}},
                    allowClear: true,

                    // Initial selection
                    //------------------
                    initSelection: function (element, callback) {
                        callback({
                            id: element.val(),
                            text: $("#" + divId + ' .select2-ajax-preload').val()
                        });
                    },

                    // Ajax Call to get list
                    //-----------------------
                    ajax: {
                        dataType: "json",
                        url: url,
                        quietMillis: 500,
                        data: function (term, page) { // Post data in search URL
                            return {
                                '{{$input->nameField}}': term,
                                // id: term, // Pass additional search param
                                page: page
                            }
                        },
                        results: function (response, page) {
                            var more = (page * 20) < response.data.total; // whether or not there are more results available
                            return {
                                results: $.map(response.data.items, function (item) {
                                    {{-- var text = item.id + ' ' + item.{{$input->nameField}}; --}}
                                    var text = item.{{$input->nameField}};
                                    return {
                                        text: text,
                                        id: item.id
                                    }
                                }),
                                more: more
                            };
                        }
                    }
                }).on("change", function () {

                    @if($input->preview)
                    // Load the content block on change
                    // ---------------------------------
                    detailsContainer.html("loading...");
                    var id = $("#" + divId + " input.ajax").select2('val');
                    // console.log(id);
                    $.ajax({
                        type: "GET",
                        data: {
                            {!!  $input->previewUrlParam!!}: id
                        },
                        url: "{!! $input->previewUrl !!}"
                    }).done(function (ret) {
                        // console.log(ret);
                        detailsContainer.hide();
                        detailsContainer.html(ret).fadeIn();
                    });
                    @endif

                }).trigger('change');
            }

            @if($input->advancedSearch)
            /**
             * Function used in datatable.
             * @param id
             * @param text
             */
            function selectDtResult_{{$input->id}}(id, text = null) {
                // $('#{{$section}}Modal').modal('hide');
                $("#{{$section}}ModalCloseButton").click();
                // console.log(id);
                $("#{!! $input->uid !!} .select2-ajax-preload").val(text);
                $("#{!! $input->uid !!} input.ajax").select2("val", id).trigger('change');
            }
            @endif
        </script>
    @endif
    @parent
@endsection

<?php unset($input, $section) ?>