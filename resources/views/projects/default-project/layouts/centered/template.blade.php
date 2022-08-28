<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('projects.default-project.layouts.default.includes.analytics')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @section('head-title')
            {{config('app.name')}}
        @show
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @section('head')
    @show
    @include('mainframe.layouts.default.includes.css')
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">
</head>
<body class="hold-transition login-page lb-bg">
<div class="login-box shadow">
    <div class="login-logo">
        {{config('app.name')}}
    </div>
    <div class="login-box-body">
        @include('mainframe.layouts.default.includes.alerts.messages-top')

        @section('content-top')
        @show

        @section('content')
        @show

        @section('content-bottom')
        @show
    </div>
    @include('mainframe.layouts.default.includes.modals.messages')
    @include('mainframe.layouts.default.includes.modals.delete')
</div>

@include('mainframe.layouts.default.includes..js')
@section('js')
    {{-- js section   --}}
@show
<script>
    {{-- Any additional js--}}
</script>
</body>
</html>