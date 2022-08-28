<?php

$scripts = [
    /**
     * Admin Lte CSS
     */
    // 'mainframe/templates/admin/css/skins/_all-skins.min.css',
    // 'mainframe/templates/admin/bootstrap/css/bootstrap.min.css',
    // 'mainframe/templates/admin/css/main.css',
    // 'mainframe/templates/admin/plugins/morris/morris.css',
    // 'mainframe/templates/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
    // 'mainframe/templates/admin/plugins/datatables/dataTables.bootstrap.css',
    // 'mainframe/templates/admin/plugins/validation/css/validationEngine.jquery.css',
    'mainframe/templates/admin/plugins/select2-3.5.1/select2.css',
    // 'mainframe/templates/admin/plugins/uploadfile/uploadfile.css',
    // 'mainframe/templates/admin/plugins/iCheck/all.css',
    // 'mainframe/templates/admin/plugins/iCheck/square/blue.css',
    // 'mainframe/templates/admin/plugins/datepicker/datepicker3.css',
    // 'mainframe/templates/admin/plugins/daterangepicker/daterangepicker.css',
    // 'mainframe/templates/admin/plugins/datetimepicker/bootstrap-datetimepicker.css',
    // 'mainframe/templates/admin/plugins/bootstrap-slider/slider.css',
    // 'mainframe/templates/admin/plugins/jQueryUI/jquery-ui.css',
    'css/all.css',
    /**
     * Custom CSS files
     */
    'mainframe/css/mainframe.css',
];

$scripts = array_merge($scripts, config('mainframe.config.load.css'));
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
{{--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">--}}

{{-- Google font icons --}}
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Two+Tone" rel="stylesheet">
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>

@foreach($scripts as $script)
    <link rel="stylesheet" href="{{asset($script)}}">
@endforeach

{{-- Font awesome --}}


@section('css')
@show