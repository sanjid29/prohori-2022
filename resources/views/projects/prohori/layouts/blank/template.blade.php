@extends($view->defaultTemplate())

@section('sidebar-left')
    @include('mainframe.layouts.default.includes.navigation.left-menu.menu-items')
@endsection

@section('title')
    {{$title ?? ''}}
@endsection

@section('content')
    {{$body ?? ''}}
@endsection

