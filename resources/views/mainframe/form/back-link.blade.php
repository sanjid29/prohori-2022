<?php
/** @var array $var
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $backToElement
 */
$backToElement = $var['element'];
$showField = $var['field'] ?? 'name';
$label = null;
if ($backToElement) {
    $label = Str::singular($backToElement->module()->title);
}
$label = $var['label'] ?? $label;
?>

@if($backToElement)
    <a class="btn btn-default bg-white back-link" href="{{$backToElement->editUrl()}}">
        <i class="fa fa-angle-left"></i>

        @if($label)
            <span class="badge badge-primary bg-blue flat"><b>{{$label}}</b></span>
        @endif
        <span class="badge badge-primary bg-red flat">{{$backToElement->id}}</span>
        <b>{{$backToElement->$showField}}</b>
    </a>
@endif

@unset($var)