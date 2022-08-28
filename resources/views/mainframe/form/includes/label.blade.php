@if($input->label)
    <label id="label_{{$input->name}}"
           class="control-label label_{{$input->name}} {{$input->labelClass}}"
           for="{{$input->name}}">
        {!! $input->label !!}
    </label>
    @if($input->tooltip)
        <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip" data-placement="top"
           title="{{$input->tooltip}}"></i>
    @endif
@endif