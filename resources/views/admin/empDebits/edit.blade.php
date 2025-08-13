<?php

use App\Helpers\Utility;
$util = new Utility();   

$user = Auth::user();
$userType = $user->userType;

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
                    <h4 class="page-title">Employee Deduction</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empDebits" class="btn btn-primary mr-3">List</a>
                            <a href="/empDebits/create" class="btn btn-success mr-3">Add Deduction</a>
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
                            <h4 class="card-title">Edit Deduction</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => ['admin\employees\EmpDebitsController@update', $debit->id], 'method' => 'POST']) !!}
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$employee->empCode}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$employee->name}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$employee->designationName}}" placeholder="Employee Designation" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Deduction Details</h4>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Deduction Amount&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$debit->amount}}" name="amount" id="advAmount" placeholder="Deduction Amount" {{($debit->managementStatus == '1' && $userType == '51')?'disabled':'required'}}>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Month Of Deduction&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="month" class="form-control" value="{{$debit->forMonth}}" name="forMonth" id="advDeduction" placeholder="Month Of Deduction" {{($debit->managementStatus == '1' && $userType == '51')?'disabled':'required'}}>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Reference By&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$debit->referenceBy}}" name="referenceBy" id="referenceBy" placeholder="Reference By" {{($debit->managementStatus == '1' && $userType == '51')?'disabled':'required'}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Reason&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="reason" value="{{$debit->reason}}" id="reason" placeholder="Reason" {{($debit->managementStatus == '1' && $userType == '51')?'disabled':'required'}}>
                                        </div>
                                    </div>
                                </div>  

                                @if($userType == '51') 
                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">For COO</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">COO Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('managementStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $debit->managementStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">COO Remark&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$debit->managementRemark}}" name="managementRemark" id="" placeholder="COO Remark" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    @if($debit->managementStatus == '1')
                                        <hr>
                                        <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">For Accounts</h4>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Accounts Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('accountStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $debit->accountStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label class="form-label">Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" disabled></span>:</label>
                                                    <input type="text" class="form-control" value="{{$debit->accountRemark}}" name="accountRemark" id="" placeholder="Remark" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if($userType == '61') 
                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">For COO</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">COO Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('managementStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $debit->managementStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">COO Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$debit->managementRemark}}" name="managementRemark" id="" placeholder="COO Remark" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">Accounts Details</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('accountStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $debit->accountStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', ($debit->managementStatus == '1')?'required':'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Accounts Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" disabled></span>:</label>
                                                <input type="text" class="form-control" value="{{$debit->accountRemark}}" name="accountRemark" id="" placeholder="Accounts Remark" {{($debit->managementStatus == '1')?'required':'disabled'}}>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($userType == '501') 
                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">For COO</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">COO Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('managementStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $debit->managementStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">COO Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$debit->managementRemark}}" name="managementRemark" id="" placeholder="COO Remark" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">Accounts Details</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('accountStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $debit->accountStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Accounts Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" disabled></span>:</label>
                                                <input type="text" class="form-control" value="{{$debit->accountRemark}}" name="accountRemark" id="" placeholder="Purpose" disabled>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <input type="hidden" value="{{$employee->id}}" name="empId">

                                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure?')">Update</button>
                                            <a href="/empDebits" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
