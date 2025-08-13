<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">AGF Report</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@AGFReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Branches:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Department:</label>
                                        {{Form::select('departmentId', $departments, ((isset($departmentId))?$departmentId:null), ['placeholder'=>'Select Department','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Month</label>
                                        <input type='month' value="{{date('Y-m', strtotime($month))}}" class="form-control" name="month" id="month"/>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($applications))
                            @if(count($applications) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0" id="example">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-white w-5">#</th>
                                                <th class="text-white w-4">Date</th>
                                                <th class="text-white">Employee Name</th>
                                                <th class="text-white w-15">Issue In Brief</th>
                                                <th class="text-white w-15">Description</th>
                                                <th class="text-white w-5">Day Status</th>
                                                <th class="text-white w-10">Reporting Authority</th>
                                                <th class="text-white w-10">HR Dept.</th>
                                                <th class="text-white w-10">Accounts Dept.</th>
                                                <th class="text-white w-10">Reason<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $app)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                    <td>
                                                        {{$app->empCode}}-{{$app->name}}<br>
                                                        {{$app->branchName}}-{{$app->departmentName}}
                                                    </td>
                                                    <td>{{$app->reason}}</td>
                                                    <td>{{$app->description}}</td>
                                                    <td>{{$app->dayStatus}}</td>
                                                    <td>
                                                        {{($app->status1 == 1)?'Approved':(($app->status1 == 0)?'Pending':'Rejected')}}<br>
                                                        {{($app->updatedAt1 == '')?'-':date('d-m-Y H:i', strtotime($app->updatedAt1))}}<br>
                                                        {{($app->approvedBy1 == '')?'-':$app->approvedBy1}}<br>
                                                    </td>
                                                    <td>
                                                        {{($app->status2 == 1)?'Approved':(($app->status2 == 0)?'Pending':'Rejected')}}<br>
                                                        {{($app->updatedAt2 == '')?'-':date('d-m-Y H:i', strtotime($app->updatedAt2))}}<br>
                                                        {{($app->approvedBy2 == '')?'-':$app->approvedBy2}}<br>
                                                    </td>
                                                    <td>
                                                        {{($app->status == 1)?'Approved':(($app->status == 0)?'Pending':'Rejected')}}<br>
                                                        {{($app->updatedAt3 == '')?'-':date('d-m-Y H:i', strtotime($app->updatedAt3))}}<br>
                                                        {{($app->approvedBy == '')?'-':$app->approvedBy}}<br>
                                                    </td>
                                                    <td>{{$app->rejectReason}}</td>                                                    
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
                                </div>
                                <div class="row" style="margin-top:15px;margin-up:15px;">
                                    <div class='col-md-8'></div>
                                    <div class="col-md-3 text-left">
                                        <a class="btn btn-primary" href="/reports/excelAGFReport/{{($branchId == '')?'0':$branchId}}/{{($departmentId == '')?'0':$departmentId}}/{{date('Y-m', strtotime($month))}}/1" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Export Excel <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="col-md-1"></div>
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
@endsection
