@extends('projects.default-project.layouts.default.template')


@section('head-title')
    @parent
    Admin Dashboard
@endsection

@section('title')
    Admin Dashboard
@endsection

@section('content')

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-3">
            <div class="info-box bg-purple">
                <a href="#" style="color:white">
                <span class="info-box-icon">
                    <ion-icon name="calendar-number-outline"></ion-icon>
                </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text">Today</span>
                    <span class="info-box-number">{{formatDate(today())}}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">
                    50% Completed
                  </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box bg-smart-red">
                <a href="{{route('users.index')}}" style="color:white">
                <span class="info-box-icon">
                   <ion-icon name="people-outline"></ion-icon>
                </span>
                </a>
                <div class="info-box-content">
                    <span class="info-box-number"> Users</span>
                    <table style="width: 100%">
                        <tr>
                            <td>Total:</td>
                            <td>{{\App\User::remember(timer('long'))->count()}}</td>
                        </tr>
                    </table>
                    <a href="{{route('users.index')}}" style="color:white">Manage</a> <i class="fa fa-angle-right"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-gray">
                <a href="{{route('reports.index')}}" style="color:white">
                <span class="info-box-icon">
                   <i class="fa fa-file-text-o"></i>
                </span>
                </a>
                <div class="info-box-content">
                    <span class="info-box-number"> Reports</span>

                    <span>
                    <a href="{{route('reports.index')}}">View all report</a>
                </span>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-gray">
                <a href="{{route('reports.index')}}" style="color:white">
                <span class="info-box-icon">
                   <i class="fa fa-cog"></i>
                </span>
                </a>
                <div class="info-box-content">
                    <span class="info-box-number"> Settings</span>

                    <span>
                    <a href="{{route('settings.index')}}">View all settings</a>
                </span>

                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-12 bordered">
        <h3 class="no-margin-t">Recently Added Users</h3>
        <?php $datatable = new \App\Projects\DefaultProject\Modules\Users\UserDatatable(); ?>
        @include('mainframe.layouts.module.grid.includes.datatable',['datatable'=>$datatable])
    </div>

@endsection