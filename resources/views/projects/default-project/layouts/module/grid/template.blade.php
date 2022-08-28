@extends('projects.default-project.layouts.default.template')

@section('sidebar-left')
    @include('projects.default-project.layouts.default.includes.navigation.left-menu.menu-items')
@endsection

@section('title')
    @include('mainframe.layouts.module.grid.includes.title')
@endsection

@section('content')
    @include('mainframe.layouts.module.grid.includes.datatable')
@endsection
