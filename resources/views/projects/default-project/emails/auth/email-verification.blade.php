@extends('projects.default-project.layouts.email.template')

@section('title')
    <h2 style="text-align: center">Email Verification</h2>
@endsection

@section('content')
    Dear {{$user->first_name}}, <br><br>

    Thank You for taking the time to register.
    <br/><br/>

    Please <a href="{{$url}}">click here</a> to verify your user email.

    <a class="button button-blue action" href="{{$url}}"> {{__('Verify Email')}}</a>
    <a href="{{$url}}">{{$url}}</a>
    <br/><br/>
    Thank you

@endsection