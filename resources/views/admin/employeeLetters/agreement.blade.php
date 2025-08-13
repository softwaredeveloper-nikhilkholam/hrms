<?php
use App\Helpers\Utility;
$util = new Utility();
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
                    <h4 class="page-title">Employee Agreement</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employeeLetters/list/3" class="btn btn-success mr-3">Letter List</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">Employee Agreement</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmployeeLettersController@getAgreement', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="empCode" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Search Employee Code..." class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <b style="color:red;">Note: Enter only Emp Code without (AWS, AFF, ADF...)</b>
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>
                            @if(isset($empDet))
                                {!! Form::open(['action' => 'admin\EmployeeLettersController@generateAgreement', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-5">
                                        <b style="color:blue;">Note: If you Want to change disabled Information <a href="/employees/{{$empDet->id}}/edit" target="_blank">Please click here</a></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empCode}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empDet->name}}" id="empName" placeholder="Employee Code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">DOJ :</label>
                                            <input type="date" class="form-control" name="DOJ"  value="{{date('Y-m-d', strtotime($empDet->jobJoingDate))}}" id="" placeholder="Joining Date" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Years of Service</label>
                                            <input type="text" class="form-control" name="service"  value="{{$util->calculateExperience($empDet->jobJoingDate)}}" id="service" placeholder="Phone No" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Department:</label>
                                            {{Form::select('departmentId', $departments, $empDet->departmentId, ['placeholder'=>'Select Department','class'=>'form-control', 'id'=>'desigantionId', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Designation:</label>
                                            {{Form::select('desigantionId', $designations, $empDet->designationId, ['placeholder'=>'Select Desigantion','class'=>'form-control', 'id'=>'desigantionId', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Branch:</label>
                                            {{Form::select('branchId', $branches, $empDet->branchId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">From Date<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="date" class="form-control" name="fromDate"  value="{{$empDet->fromDate}}" id="" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">To Date<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="date" class="form-control" name="toDate"  value="{{$empDet->toDate}}" id="" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Upload Agreement PDF<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="file" class="form-control" name="uploadFile"  value="{{$empDet->salaryScale}}" id="" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Approved By<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            {{Form::select('signBy', $signFiles,null, ['placeholder'=>'Select Signature By','class'=>'form-control', 'id'=>'signBy', 'required'])}}
                                        </div>
                                    </div> 
                                </div>
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <input type="hidden" value="{{$empDet->id}}" name="empId">
                                            <input type="Submit" name="submit" value="Save" style="background-color:seagreen;color:white;" class="btn btn-lg">
                                        </div>
                                        <div class="col-md-2"></div>
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
