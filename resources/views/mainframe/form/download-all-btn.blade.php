<?php
/** @var array $var
 * @var \App\Mainframe\Features\Modular\BaseModule\BaseModule $element
 */
$label = $var['label'] ?? "Download Zip";
$url = $var['url'] ?? route('download.zip', ['module_id' => $element->module()->id, 'element_id' => $element->id]);;
?>

<a class="btn btn-default bg-white" href="{!! $url !!}">
    <i class="fa fa-file-zip-o"></i> {{$label}}
</a>

@unset($var, $label, $url)