<?php
    $user = Auth::user();
	$userType = $user->userType;
	$appointStatus = $user->appointStatus;

?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Appointment</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/" class="btn btn-success mr-3">Current List</a>
                            <a href="/appointments/archiveList" class="btn btn-primary mr-3">Archive List</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Archive Appointment List</h4>
                        </div> 
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\AppointmentsController@search', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row mt-5">
                                    <div class="col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="forDate" class="form-control" value="{{$forDate}}">
                                        </div>
                                    </div>
                                    @if($userType == "51")
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label">Appointment With</label>
                                                {{Form::select('requestTo', ['3'=>'MD Milind ladge', '4'=>'CEO Pratik Ladge', '5'=>'COO Pranav Ladge'], ((isset($requestTo))?$requestTo:'null'), ['placeholder'=>'All Option','class'=>'form-control','style'=>'color:red;'])}}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label">Priority</label>
                                            {{Form::select('priority', ['Urgent'=>'Urgent', 'General'=>'General'], ((isset($priority))?$priority:'null'), ['placeholder'=>'All Option','class'=>'form-control','style'=>'color:red;'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            {{Form::select('status', ['1'=>'Pending', '2'=>'Approved', '3'=>'Postpone', '4'=>'Rejected'], ((isset($status))?$status:'null'), ['placeholder'=>'All Option','class'=>'form-control',])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2  mt-5">
                                        <input type="submit" value="Search" class="btn btn-success">
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            <hr>
                            @if(isset($appointments))
                                @if(count($appointments))
                                    <div class="table-responsive">
                                        <table class="table table-striped card-table table-vcenter text-nowrap mb-0 ">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th class="border-bottom-0 text-white" width="10%">Date</th>
                                                    <th class="border-bottom-0 text-white" width="10%">Priority</th>
                                                    <th class="border-bottom-0 text-white" width="20%">Employee Name</th>
                                                    <th class="border-bottom-0 text-white" width="15%">Department Name</th>
                                                    <th class="border-bottom-0 text-white" >Agenda</th>
                                                    <th class="border-bottom-0 text-white" width="10%">Status</th>
                                                    <th class="border-bottom-0 text-white" width="10%">Meeting Update</th>
                                                    <th class="border-bottom-0 text-white" width="5%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($appointments as $appoint)
                                                    <tr>
                                                        <td>{{date('d-m-Y', strtotime($appoint->forDate))}}</td>
                                                        <td>{{$appoint->priority}}</td>
                                                        <td>{{$appoint->name}}</td>
                                                        <td>{{$appoint->departmentName}}</td>
                                                        <td>{{$appoint->reason}}</td>
                                                        <td>{{($appoint->status == 1)?"Pending":(($appoint->status == 2)?"Approved":(($appoint->status == 3)?"Postpone":"Rejected"))}}</td>
                                                        <td>@if($appoint->meetingStatus == "Completed")
                                                                <b style="color:green;">✔</b>
                                                            @else
                                                                <b style="color:red;">-</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="/appointments/{{$appoint->id}}" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View More"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                        <div class="row" style="margin-top:15px;">
                                            <div class='col-md-8'>{{$appointments->links()}}</div>
                                            <div class='col-md-2'></div>
                                            <div class='col-md-2'>
                                                <a href="/appointments/exportPDF/{{(isset($requestTo))?$requestTo:0}}/{{(isset($priority))?$priority:0}}/{{(isset($status))?$status:0}}/{{(isset($forDate))?$forDate:0}}" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Download PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <h4 style="color:red;">Records not found.</h4>
                                @endif
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
