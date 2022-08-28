<?php

if (!isset($levels)) {
    $levels = [
        //['model' => \App\Division::class, 'name' => 'division_id', 'label' => 'Division'],
        //['model' => \App\District::class, 'name' => 'district_id', 'label' => 'District'],
        //['model' => \App\Upazila::class, 'name' => 'upazila_id', 'label' => 'Upazila'],
        //['model' => \App\Paurasava::class, 'name' => 'paurasava_id', 'label' => 'Paurasava'],
        //['model' => \App\Union::class, 'name' => 'union_id', 'label' => 'Union'],
        //['model' => \App\Ward::class, 'name' => 'ward_id', 'label' => 'Ward'],
    ];
}
$emptyValue = $emptyValue ?? '';

?>

@foreach($levels as $level)
    <?php
    /** @var  $loop */
    /** @var array $level */
    $class = $level['model'];
    $urlParam = Str::singular((new($class))->getTable())."_id"; // division_id
    $levels[$loop->index]['url_param'] = $level[$loop->index]['url_param'] ?? $urlParam;

    if (!$loop->first && !isset($level['query']) && isset($element)) {
        $previous = $loop->index - 1;
        $previousLevel = $levels[$previous];
        /** @var \App\Projects\Prohori\Features\Modular\BaseModule\BaseModule $class */
        /** @var \App\Projects\Prohori\Features\Modular\BaseModule\BaseModule $previousLevelClass */
        $previousLevelClass = $previousLevel['model'];
        $targetField = Str::singular((new($previousLevelClass))->getTable())."_id"; // division_id
        $level['query'] = $level['query'] ?? $class::where($targetField, $element->{$previousLevel['name']});
        // echo $targetField."+".$previousLevel['name'];
    }
    ?>
    @include('form.select-model',['var'=>$level])
@endforeach


@section('js')
    @parent

    @foreach($levels as $level)
        <?php
        /** @var array $level */
        /** @var $loop */
        /** @var \App\Division $currentModel */
        $currentSelect = 'select_'.$level['name']; // select_division_id
        $currentModel = new ($level['model']);
        ?>
        @if(!$loop->last)
            <?php
            $childLevel = $levels[$loop->index + 1];
            $childSelect = 'select_'.$childLevel['name']; // select_district_id
            $childOldValue = 'old_'.$childSelect; // select_district_id

            /** @var \App\District $currentModel */
            $childModel = new ($childLevel['model']);
            $url = $childLevel['url'] ?? route($childModel->module()->name.'.list-json',
                    ['is_active' => '1', 'force_all_data' => 'yes', 'columns' => 'id,name']);
            $url_param = $level['url_param'] ?? $level['name'];

            $currentSelectId = $level['id'] ?? $level['name'];
            $childSelectId = $childLevel['id'] ?? $childLevel['name'];

            ?>

            <script>
                /* Chained dropdown for level: {{$level['name'] }} */

                var {{$currentSelect}} = $('select[id={{$currentSelectId}}]');
                var {{$childSelect}} = $("select[id={{$childSelectId}}]");

                {{$currentSelect}}.select2();
                {{$childSelect}}.select2();

                {{$currentSelect}}.on('change', function () { // https://select2.github.io/select2/ > Events

                    var val = $(this).select2('val');


                    if (!val || val == 0) {
                        {{$childSelect}}.select2("val", "").empty().select2('enable', false);
                        {{--{{$childSelect}}.trigger('change');--}}
                            return;
                    }


                    axios.get('{!! $url !!}', {
                        params: {
                            {{$url_param}}: $(this).select2('val')
                        }
                    }).then((response) => {

                        var {{$childOldValue}} = {{$childSelect}}.select2('val'); // Temporarily store value to assign after ajax loading
                        {{$childSelect}}.select2("val", "").empty().select2('enable', false); // Clear and disable child

                        $({{$childSelect}}).append("<option value='{{$emptyValue}}'>" + "-" + "</option>"); // Add empty selection
                        $.each(response.data.data.items, function (i, obj) { // Load options
                            $({{$childSelect}}).append("<option value='" + obj.id + "'>" + obj.name + "</option>");
                        });

                        {{$childSelect}}.select2('enable', true); // Enable back child after the ajax call
                        {{$childSelect}}.val({{$childOldValue}}).select2(); // Assign back old selection
                        {{$childSelect}}.trigger('change');
                    });


                }).trigger('change');

                {{-- {{$currentSelect}}.trigger('change'); // Force trigger a 'change' event  so that child options refresh based on current parent selection.--}}
            </script>
        @endif
    @endforeach
@endsection

@unset($levels,$emptyValue)