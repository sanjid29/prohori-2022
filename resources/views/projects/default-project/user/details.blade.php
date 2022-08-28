@extends('projects.default-project.layouts.default.template')

@section('content')

    <?php
    /** @var \App\User $userInfo */

    ?>
    @if($userInfo)
        <h4 class="pull-left">User Info</h4>
        <table id="user-info" class="table ">
            <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userInfo->attributesToArray() as $k=>$v)
                @if(is_string($v))
                    <tr>
                        <td><b> {{$k}} </b></td>
                        <td>{{$v}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

    @endif

@endsection