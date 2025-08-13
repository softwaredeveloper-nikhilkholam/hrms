<?php

$user = Auth::user();
$empId = $user->empId;
$userType = $user->userType;

?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Notifications</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <ul class="nav panel-tabs">
                                @if($userType == '61' || $userType == '51' || $userType == '11' || $userType == '21')
                                    <li><a href="#tab1" class="active" data-toggle="tab">Reportee&nbsp;<b style="color:Red;">{{count($applications)}}</b></a></li>
                                @else
                                    <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Personal Approval&nbsp;<b style="color:Red;">{{count($personalApplications)}}</b></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            @if($userType == '61' || $userType == '51' || $userType == '11' || $userType == '21')
                                <div class="tab-pane active" id="tab1">
                                    <div class="card-body">
                                        @if(isset($applications))
                                            @if(count($applications) >= 1)
                                                <div class="table-responsive">
                                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example">
                                                        <thead class="bg-primary text-white">
                                                            <tr>
                                                                <th class="text-white w-5">#</th>
                                                                <th class="text-white w-10">Date</th>
                                                                <th class="text-white">Employee Name</th>
                                                                <th class="text-white w-15">Application Type</th>
                                                                <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($applications as $app)
                                                                <tr>
                                                                    <td>{{$i++}}</td>
                                                                    <td>{{date('d-m-Y H:i:s', strtotime($app->created_at))}}</td>
                                                                    <td>@if($app->firmType == 1)
                                                                            {{$app->empCode}}
                                                                        @elseif($app->firmType == 2)
                                                                            AFF{{$app->empCode}}
                                                                        @else
                                                                            AFS{{$app->empCode}}
                                                                        @endif -
                                                                        {{$app->name}}</td>
                                                                    <td>
                                                                        @if($app->type == 1)
                                                                            AGF Application
                                                                        @elseif($app->type == 2)
                                                                            Exit Pass Application
                                                                        @elseif($app->type == 3)
                                                                            Leave Application
                                                                        @else
                                                                            Travelling Allowance Application
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($app->type == 1)
                                                                            <a href="/empApplications/{{$app->empId}}/{{$app->startDate}}/1/AGFShow" class="btn btn-primary">Show</a>
                                                                        @elseif($app->type == 2)
                                                                            <a href="/empApplications/{{$app->empId}}/{{$app->startDate}}/2/exitPassShow" class="btn btn-primary">Show</a>
                                                                        @elseif($app->type == 3)
                                                                            <a href="/empApplications/{{$app->empId}}/{{$app->startDate}}/3/leaveShow" class="btn btn-primary">Show</a>
                                                                        @else
                                                                            <a href="/empApplications/{{$app->empId}}/{{$app->startDate}}/4/travellingTranspShow" class="btn btn-primary">Show</a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach                                        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <h4 style="color:red;">Not Found Records.</h4>
                                            @endif
                                        @endif
                                    </div>
                                </div>   
                            @else
                                <div class="tab-pane active" id="tab1">
                                    <div class="card-body">
                                    @if(isset($personalApplications))
                                            @if(count($personalApplications) >= 1)
                                                <div class="table-responsive">
                                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0 ">
                                                        <thead class="bg-primary text-white">
                                                            <tr>
                                                                <th class="text-white w-5">#</th>
                                                                <th class="text-white w-10">Date</th>
                                                                <th class="text-white">Employee Name</th>
                                                                <th class="text-white w-15">Application Type</th>
                                                                <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($personalApplications as $app)
                                                                <tr>
                                                                    <td>{{$i++}}</td>
                                                                    <td>{{date('d-m-Y H:i:s', strtotime($app->created_at))}}</td>
                                                                    <td>@if($app->firmType == 1)
                                                                            {{$app->empCode}}
                                                                        @elseif($app->firmType == 2)
                                                                            AFF{{$app->empCode}}
                                                                        @else
                                                                            AFS{{$app->empCode}}
                                                                        @endif -
                                                                        {{$app->name}}</td>
                                                                    <td>
                                                                        @if($app->type == 1)
                                                                            AGF Application
                                                                        @elseif($app->type == 2)
                                                                            Exit Pass Application
                                                                        @elseif($app->type == 3)
                                                                            Leave Application
                                                                        @else
                                                                            Travelling Allowance Application
                                                                        @endif
                                                                    </td>
                                                                    <td><a href="/empApplications/changeStatus/{{$app->id}}/{{$app->type}}" class="btn btn-primary">Show</a></td>
                                                                </tr>
                                                            @endforeach                                        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <h4 style="color:red;">Not Found Records.</h4>
                                            @endif
                                        @endif
                                    </div>
                                </div> 
                            @endif
                        </div>   
                    </div>   
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
