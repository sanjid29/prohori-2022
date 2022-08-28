@extends($view->defaultTemplate())

@section('sidebar-left')
    @include($view->leftMenu())
@endsection

@section('title')
    {{$title ?? ''}}
@endsection

@section('content')
    {{$body ?? ''}}
@endsection
