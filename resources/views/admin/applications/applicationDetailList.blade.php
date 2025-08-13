@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container"> 
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Applications Of {{date('M-Y')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                            <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">AGF Applications&nbsp;&nbsp;<b style="color:Red;">{{$agfsCt}}</b></a></li>
                                <li><a href="#tab2" data-toggle="tab">Exit Pass Applications&nbsp;&nbsp;<b style="color:Red;">{{$exitPassesCt}}</b></a></li>
                                <li><a href="#tab3" data-toggle="tab">Leave Applications&nbsp;&nbsp;<b style="color:Red;">{{$leavesCt}}</b></a></li>
                                <li><a href="#tab4" data-toggle="tab">Travelling Allownces Applications&nbsp;&nbsp;<b style="color:Red;">{{$travellsCt}}</b></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-body">
                                    @if($type == 1)
                                        <div class="table-responsive">
                                            <table class="table table-striped card-table table-vcenter text-nowrap mb-0 ">
                                                <thead class="bg-danger text-white">
                                                    <tr>
                                                        <th class="text-white w-5">#</th>
                                                        <th class="text-white w-10">Date</th>
                                                        <th class="text-white w-5">Status</th>
                                                        <th class="text-white w-10">Updated At</th>
                                                        <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($travelApplications as $app)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                            <td>{{$app->empCode}} - {{$app->empName}}</td>
                                                            <td>{{$app->departmentName}}</td>
                                                            <td>{{$app->designationName}}</td>
                                                            <td style="color:{{($app->status == 0)?'orange':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                                <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                            <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                            <td><a href="/empApplications/changeStatus/{{$app->id}}/4" class="btn btn-primary">Show</a></td>
                                                        </tr>
                                                    @endforeach                                        
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>   
                        </div>   
                    </div>   
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
