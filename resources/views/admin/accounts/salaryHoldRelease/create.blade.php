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
                            <h4 class="card-title">Add Advance Entry</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmpAdvRsController@create', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="empCode" placeholder="Search Employee Code..." class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>   
                            @if(isset($empCode))
                                {!! Form::open(['action' => 'admin\employees\EmpAdvRsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" name="empCode"  value="{{$employee->empCode}}" id="empName" placeholder="Employee Name" readonly>
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
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Advance Amount&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" name="advAmount" id="advAmount" placeholder="Advance Amount" required>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Purpose &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" value="" name="purpose" id="" placeholder="Purpose" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Monthly Deduction&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" name="deduction" id="advDeduction" placeholder="Monthly Deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Start Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control" name="startDate" id="startDate" placeholder="Date From Deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">End Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="month" class="form-control" name="endDate" id="endDate" placeholder="Date From Deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Mode Of Payment &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('paymentMode', ['Cash'=>'Cash', 'Online'=>'Online', 'Cheque'=>'Cheque'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option'])}}
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-5"></div>
                                            <div class="col-md-12 col-lg-3">
                                                <input type="hidden" value="{{$employee->id}}" name="empId">
                                                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                                <a href="/departments" class="btn btn-danger btn-lg">Cancel</a>
                                            </div>
                                            <div class="col-md-12 col-lg-4"></div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
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
