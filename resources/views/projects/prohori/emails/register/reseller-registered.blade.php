@extends('projects.prohori.layouts.email.template')
<?php
/** @var \App\User $user */
$reseller = $user->reseller;
?>


@section('title')
    Project |  A new Partner Request
@endsection

@section('content')

    Dear Project,<br><br>

    You have a new partner- <b>{{$reseller->name}}</b> request.<br><br>

    Please <a href="{{route('resellers.edit',$user->reseller_id)}}">click here</a> to view it.<br><br>

    Project Onboarding Team

@endsection


