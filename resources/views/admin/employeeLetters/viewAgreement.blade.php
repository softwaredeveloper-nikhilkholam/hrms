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
                            @if(isset($empDet))
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empDet->empCode}}" id="empName" placeholder="Employee Name" disabled>
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
                                            <label class="form-label">From Date :</label>
                                            <input type="date" class="form-control" name="fromDate"  value="{{$empDet->fromDate}}" id="" placeholder="0" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">To Date :</label>
                                            <input type="date" class="form-control" name="toDate"  value="{{$empDet->toDate}}" id="" placeholder="0" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">View Aggreement :</label>
                                            <a href="/admin/empLetters/{{$empDet->uploadFile}}" target="_blank"><b style="color:Red;"><i class="fa fa-file-pdf-o" style="font-size:35px;color:red"></i></b></a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Approved By :</label>
                                            {{Form::select('signBy', $signFiles,$empDet->signBy, ['placeholder'=>'Select Signature By','class'=>'form-control', 'id'=>'signBy', 'disabled'])}}
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Created At :</label>
                                            <input type="text" class="form-control" name="toDate"  value="{{date('d-m-Y H:i A', strtotime($empDet->created_at))}}" id="" placeholder="0" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Updated By :</label>
                                            <input type="text" class="form-control" name="toDate"  value="{{$empDet->updated_by}}" id="" placeholder="0" disabled>
                                        </div>
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
