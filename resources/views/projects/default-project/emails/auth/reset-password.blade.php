@extends('projects.default-project.layouts.email.template')

@section('title')
    <h2 style="text-align: center">Reset Password</h2>
@endsection

@section('content')
    {{__('You are receiving this email because we received a password reset request for your account.')}}
    <a class="button button-blue action" href="{{$url}}"> {{__('Reset Password')}}</a>
    <br/><br/>
    <a href="{{$url}}">{{$url}}</a>
    <br/><br/>
    {{__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])}}
    <br/><br/>
    {{__('If you did not request a password reset, no further action is required.')}}
@endsection