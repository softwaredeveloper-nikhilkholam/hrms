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
                <h4 class="page-title">Extra Working Day Report</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@extraWorkingReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                @if($userType == '61')
                                    <div class="col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label">Organisation:</label>
                                            {{Form::select('organisation', ['1'=>'Ellora Medicals and Educational foundation', '2'=>'Snayraa Agency', '3'=>'Tejasha Educational and research foundation'], null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation'])}}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Branches:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Department:</label>
                                        {{Form::select('departmentId', $departments, ((isset($departmentId))?$departmentId:null), ['placeholder'=>'Select Department','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Date(s)</label>
                                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                        <input type='hidden' value="{{$fromDate}}" name="fromDate" id="startDate"/>
                                        <input type='hidden' value="{{$toDate}}" name="toDate" id="endDate"/>
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($emps))
                            @if(count($emps))
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
										<thead  class="bg-success text-white">
                                            <tr>
                                                <th class="text-white border-bottom-0 w-5">Emp Code</th>
                                                <th class="text-white border-bottom-0 w-15">Employee Name</th>
                                                <th class="text-white border-bottom-0">Branch</th>
                                                <th class="text-white border-bottom-0">Department</th>
                                                @if($userType == '61')
                                                    <th class="text-white border-bottom-0 w-20">Organisation</th>
                                                @endif
                                                <th class="text-white border-bottom-0">Designation<?php $i=1; ?></th>
                                                <th class="text-white border-bottom-0 w-5">Total Days</th>
                                                <th class="text-white border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($emps as $row)
                                                <tr>
                                                    <td>
                                                        {{$row->empCode}}
                                                    </td>
                                                    <td>{{$row->empName}}</td>
                                                    <td>{{$row->branchName}}</td>
                                                    <td>{{$row->departmentName}}</td>
                                                    
                                                    @if($userType == '61')
                                                        <td>
                                                            @if($row->organisation == '1')
                                                                Ellora Medicals and Educational foundation
                                                            @elseif($row->organisation == '2')
                                                                Snayraa Agency
                                                            @else
                                                                Tejasha Educational and research foundation
                                                            @endif

                                                        </td>
                                                    @endif
                                                    <td>{{$row->designationName}}</td>
                                                    <td>{{$row->totalDays}}</td>
                                                    <td><a href="/reports/extraWorkingReportDet/{{$row->id}}/{{$fromDate}}/{{$toDate}}" class="btn btn-primary">Show</a></td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
                                </div>
                                <hr>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'></div>
                                    <!-- <div class='col-md-4'>
                                        <a href="/reports/PDFAGFReport/{{(isset($branchId))?$branchId:0}}/{{(isset($departmentId))?$departmentId:0}}/{{$fromDate}}/{{$toDate}}" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Download PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                        <a href="/reports/excelAGFReport/{{(isset($branchId))?$branchId:0}}/{{(isset($departmentId))?$departmentId:0}}/{{$fromDate}}/{{$toDate}}" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Download Excel <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                    </a> -->
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Records.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
