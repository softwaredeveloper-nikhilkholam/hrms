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
                                    @if(isset($AGFApplications))
                                        @if(count($AGFApplications) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-white w-5">#</th>
                                                            <th class="text-white w-10">Date</th>
                                                            <th class="text-white w-30">Employee Name</th>
                                                            <th class="text-white w-15">Dept. Name</th>
                                                            <th class="text-white w-15">Designation Name</th>
                                                            <th class="text-white w-5">Status</th>
                                                            <th class="text-white w-10">Updated At</th>
                                                            <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($AGFApplications as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                                <td>{{$app->empCode}} - {{$app->empName}}</td>
                                                                <td>{{$app->departmentName}}</td>
                                                                <td>{{$app->designationName}}</td>
                                                                <td style="color:{{($app->status == 0)?'orange':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                                    <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                                <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                                <td><a href="/empApplications/changeStatus/{{$app->id}}/1" class="btn btn-primary">Show</a></td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            
                            <div class="tab-pane" id="tab2">
                                <div class="card-body">
                                    @if(isset($exitPassApplications))
                                        @if(count($exitPassApplications) >= 1)
                                        <div class="table-responsive">
                                            <table class="table table-striped card-table table-vcenter text-nowrap mb-0 "  id="example1">
                                                <thead class="bg-success text-white">
                                                    <tr>
                                                        <th class="text-white w-5">#</th>
                                                        <th class="text-white w-10">Date</th>
                                                        <th class="text-white w-30">Employee Name</th>
                                                        <th class="text-white w-15">Dept. Name</th>
                                                        <th class="text-white w-15">Designation Name</th>
                                                        <th class="text-white w-5">Status</th>
                                                        <th class="text-white w-10">Updated At</th>
                                                        <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($exitPassApplications as $app)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                            <td>{{$app->empCode}} - {{$app->empName}}</td>
                                                            <td>{{$app->departmentName}}</td>
                                                            <td>{{$app->designationName}}</td>
                                                            <td style="color:{{($app->status == 0)?'orange':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                                <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                            <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                            <td><a href="/empApplications/changeStatus/{{$app->id}}/2" class="btn btn-primary">Show</a></td>
                                                        </tr>
                                                    @endforeach                                        
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div> 

                            <div class="tab-pane" id="tab3"> 
                                <div class="card-body">
                                    @if(isset($leaveApplications))
                                        @if(count($leaveApplications) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example2">
                                                    <thead class="bg-warning text-white">
                                                        <tr>
                                                            <th class="text-white w-5">#</th>
                                                            <th class="text-white w-10">Date</th>
                                                            <th class="text-white w-30">Employee Name</th>
                                                            <th class="text-white w-15">Dept. Name</th>
                                                            <th class="text-white w-15">Designation Name</th>
                                                            <th class="text-white w-5">Status</th>
                                                            <th class="text-white w-10">Updated At</th>
                                                            <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($leaveApplications as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                                <td>
                                                                    {{$app->empCode}} - {{$app->empName}}
                                                                </td>
                                                                <td>{{$app->departmentName}}</td>
                                                                <td>{{$app->designationName}}</td>
                                                                <td style="color:{{($app->status == 0)?'orange':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                                    <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                                <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                                <td><a href="/empApplications/changeStatus/{{$app->id}}/3" class="btn btn-primary">Show</a></td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div> 

                            <div class="tab-pane" id="tab4"> 
                                <div class="card-body">
                                    @if(isset($travelApplications))
                                        @if(count($travelApplications) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example3">
                                                    <thead class="bg-danger text-white">
                                                        <tr>
                                                            <th class="text-white w-5">#</th>
                                                            <th class="text-white">Employee Name</th>
                                                            <th class="text-white w-20">Dept. Name</th>
                                                            <th class="text-white w-20">Designation Name</th>
                                                            <th class="text-white w-20">Pending</th>
                                                            <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($travelApplications as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>
                                                                    {{$app->empCode}} - {{$app->empName}}
                                                                </td>
                                                                <td>{{$app->departmentName}}</td>
                                                                <td>{{$app->designationName}}</td>
                                                                <td>{{$app->pendingCt}}</td>
                                                                <td>
                                                                    <a href="/empApplications/{{$app->empId}}/{{$app->forDateYear.'-'.$app->forDateMonth}}/4/viewMore" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
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
