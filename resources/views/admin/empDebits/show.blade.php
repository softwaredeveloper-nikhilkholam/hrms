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
                            <a href="/empDebits" class="btn btn-primary mr-3">Active List</a>
                            <a href="/empDebits/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/empDebits/create" class="btn btn-primary mr-3">Add Deduction Entry</a>
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
                            <h4 class="card-title">Show Deduction Details</h4>
                        </div>
                        <div class="card-body">
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
                                        <input type="text" class="form-control" value="{{$debit->amount}}" name="amount" id="advAmount" placeholder="Deduction Amount" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Month Of Deduction&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        <input type="month" class="form-control" value="{{$debit->forMonth}}" name="forMonth" id="advDeduction" placeholder="Month Of Deduction" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Reference By&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        <input type="text" class="form-control" value="{{$debit->referenceBy}}" name="referenceBy" id="referenceBy" placeholder="Reference By" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Remarks&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        <input type="text" class="form-control" name="remarks" value="{{$debit->remark}}" id="remark" placeholder="Remarks" disabled>
                                    </div>
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
