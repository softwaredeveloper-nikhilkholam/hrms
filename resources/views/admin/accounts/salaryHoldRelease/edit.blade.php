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
                    <h4 class="page-title">Employee Advance</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empAdvRs" class="btn btn-primary mr-3">List</a>
                            <a href="/empAdvRs/create" class="btn btn-success mr-3">Add Advance Entry</a>
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
                            <h4 class="card-title">Edit Advance</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => ['admin\employees\EmpAdvRsController@update', $payment->id], 'method' => 'POST']) !!}
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
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Advance Details</h4>
                                @if($userType == '51') 
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Advance Amount&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$payment->advAmount}}" name="advAmount" id="advAmount" placeholder="Advance Amount" {{($payment->managementStatus == 1 || $payment->managementStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Purpose &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control"  value="{{$payment->purpose}}" name="purpose" id="" placeholder="Purpose" {{($payment->managementStatus == 1 || $payment->managementStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Monthly Deduction&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$payment->deduction}}" name="deduction" id="advDeduction" placeholder="Monthly Deduction" {{($payment->managementStatus == 1 || $payment->managementStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Start Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control" value="{{$payment->startDate}}" name="startDate" id="" placeholder="Date From Deduction" {{($payment->managementStatus == 1 || $payment->managementStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">End Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control"  value="{{$payment->endDate}}" name="endDate" id="" placeholder="Date From Deduction" {{($payment->managementStatus == 1 || $payment->managementStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Mode Of Payment &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('paymentMode', ['Cash'=>'Cash', 'Online'=>'Online', 'Cheque'=>'Cheque'], $payment->paymentMode, ['class'=>'form-control', 'placeholder'=>'Select a Option', ($payment->managementStatus == 1 || $payment->managementStatus == 2)?'disabled':'required'])}}
                                            </div>
                                        </div>
                                    </div> 
                                @endif

                                @if($userType == '501') 
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Advance Amount&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$payment->advAmount}}" name="advAmount" id="advAmount" placeholder="Advance Amount" {{($payment->accountStatus == 1 || $payment->accountStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Purpose &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control"  value="{{$payment->purpose}}" name="purpose" id="" placeholder="Purpose" {{($payment->accountStatus == 1 || $payment->accountStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Monthly Deduction&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$payment->deduction}}" name="deduction" id="advDeduction" placeholder="Monthly Deduction" {{($payment->accountStatus == 1 || $payment->accountStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Start Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control" value="{{$payment->startDate}}" name="startDate" id="" placeholder="Date From Deduction" {{($payment->accountStatus == 1 || $payment->accountStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">End Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control"  value="{{$payment->endDate}}" name="endDate" id="" placeholder="Date From Deduction" {{($payment->accountStatus == 1 || $payment->accountStatus == 2)?'disabled':'required'}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Mode Of Payment &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('paymentMode', ['Cash'=>'Cash', 'Online'=>'Online', 'Cheque'=>'Cheque'], $payment->paymentMode, ['class'=>'form-control', 'placeholder'=>'Select a Option', ($payment->accountStatus == 1 || $payment->accountStatus == 2)?'disabled':'required'])}}
                                            </div>
                                        </div>
                                    </div> 
                                @endif

                                @if($userType == '61') 
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Advance Amount&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$payment->advAmount}}" name="advAmount" id="advAmount" placeholder="Advance Amount" >
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Purpose &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control"  value="{{$payment->purpose}}" name="purpose" id="" placeholder="Purpose" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Monthly Deduction&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="{{$payment->deduction}}" name="deduction" id="advDeduction" placeholder="Monthly Deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Start Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control" value="{{$payment->startDate}}" name="startDate" id="" placeholder="Date From Deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">End Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control"  value="{{$payment->endDate}}" name="endDate" id="" placeholder="Date From Deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Mode Of Payment &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('paymentMode', ['Cash'=>'Cash', 'Online'=>'Online', 'Cheque'=>'Cheque'], $payment->paymentMode, ['class'=>'form-control', 'placeholder'=>'Select a Option'])}}
                                            </div>
                                        </div>
                                    </div> 
                                @endif

                                @if(count($paymentHistory) >= 2)
                                    <?php $i=1; ?>
                                    <div class="row">
                                        @foreach($paymentHistory as $history)
                                            <div class="col-md-6">
                                                <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" style="background-color:seagreen;color:white;"><h5 class="text-center">Raise Request {{$i++}}</h5></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td width="50%">Advance Amount</td>
                                                            <td width="50%">{{$util->numberFormatRound($history->advAmount)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">Purpose</td>
                                                            <td width="50%">{{$history->purpose}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">Monthly Deduction</td>
                                                            <td width="50%">{{$util->numberFormatRound($history->deduction)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">Start Month</td>
                                                            <td width="50%">{{$history->startDate}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">End Month</td>
                                                            <td width="50%">{{$history->endDate}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">Mode Of Payment</td>
                                                            <td width="50%">{{$history->paymentMode}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">Updated By</td>
                                                            <td width="50%">{{$history->updated_by}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%">Updated At</td>
                                                            <td width="50%">{{date('d-m-Y H:i', strtotime($history->updated_at))}}</td>
                                                        </tr>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($userType == '51') 
                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">For COO</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">COO Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('managementStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $payment->managementStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">COO Remar k&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="" name="managementRemark" id="" placeholder="COO Remark" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    @if($payment->managementStatus == '1')
                                        <hr>
                                        <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">For Accounts</h4>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Accounts Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('accountStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $payment->accountStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label class="form-label">Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" disabled></span>:</label>
                                                    <input type="text" class="form-control" value="" name="month" id="" placeholder="Purpose" disabled>
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
                                                {{Form::select('managementStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $payment->managementStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">COO Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="" name="managementRemark" id="" placeholder="COO Remark" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">Accounts Details</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('accountStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $payment->accountStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Accounts Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" disabled></span>:</label>
                                                <input type="text" class="form-control" value="" name="month" id="" placeholder="Purpose" required>
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
                                                {{Form::select('managementStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $payment->managementStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">COO Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="" name="managementRemark" id="" placeholder="COO Remark" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold text-center" style="color:Red;">Accounts Details</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Status &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('accountStatus', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $payment->accountStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Accounts Remark &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" disabled></span>:</label>
                                                <input type="text" class="form-control" value="" name="month" id="" placeholder="Purpose" disabled>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure?')">Update</button>
                                            <a href="/empAdvRs" class="btn btn-danger btn-lg">Cancel</a>
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
