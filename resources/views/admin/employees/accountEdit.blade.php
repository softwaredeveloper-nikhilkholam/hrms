<?php $userType = Session()->get('userType'); ?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employees</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employees" class="btn btn-primary mr-3">Active List</a>
                            <a href="/employees/dlist" class="btn btn-primary mr-3">In-Active List</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">	
                <div class="col-xl-12 col-md-12 col-lg-12">
                    {!! Form::open(['action' => ['admin\employees\EmployeesController@update', $employee->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li class="ml-4"><a href="#tab3" class="active" data-toggle="tab">Account Details</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                  <div class="tab-pane active" id="tab3">
                                        <div class="card-body">
                                            <h5 style="color:blue;">Personal Details</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="empName"  value="{{$employee->name}}" id="empName" placeholder="Employee Name" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="empName"  value="{{$employee->empCode}}" id="empName" placeholder="Employee Name" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="designation"  value="{{$employee->designationName}}" id="empName" placeholder="Designation" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Joining Date<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="jobJoingDate" value="{{date('d-m-Y', strtotime($employee->jobJoingDate))}}" id="" placeholder="Job Joing Date" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Organisation<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('organisation', ['Ellora'=>'Ellora Medicals and Educational foundation', 'Snayraa'=>'Snayraa Agency', 'Tejasha'=>'Tejasha Educational and research foundation', 'Akshara'=>'Akshara Food Court', 'Aaryans Edutainment'=>'Aaryans Edutainment', 'ADF'=>'Aaryans Dairy Farm', 'AFF'=>'Aaryans Farming Society', 'YB'=>'Yo Bhajiwala'], $employee->organisation, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation', 'required'])}}
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Basic Salary<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;" required></span>:</label>
                                                        <input type="text" class="form-control" name="salaryScale" placeholder="Gross Salary" value="{{$employee->salaryScale}}">
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Retention Amount &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="retention" placeholder="Retention Amount" value="{{$employee->retentionAmount}}">
                                                    </div>
                                                </div>  
                                            </div>

                                            <hr>
                                            <h5 style="color:blue;">Bank Details</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Account Number &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="bankAccountNo" placeholder="Account Number" value="{{$employee->bankAccountNo}}">
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">IFSC Code &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="bankIFSCCode" placeholder="Gross Salary" value="{{$employee->bankIFSCCode}}">
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="bankName" placeholder="Bank Name" value="{{$employee->bankName}}">
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Branch Name&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="branchName" placeholder="Branch Name" value="{{$employee->branchName}}">
                                                    </div>
                                                </div>   
                                            </div> 

                                            <hr>
                                            <h5 style="color:blue;">Deduction Details</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Provident Fund &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('PF', ['Yes'=>'Yes', 'No'=>'No'],  ($employee->organisation == 'Snayraa')?'Yes':'No', ['placeholder'=>'Select Provident Fund','class'=>'form-control', 'id'=>'PF', 'required'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Professional Tax &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('PT', ['Yes'=>'Yes', 'No'=>'No'], ($employee->organisation == 'Snayraa')?'Yes':'No', ['placeholder'=>'Select Professional Tax','class'=>'form-control', 'id'=>'PT', 'required'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">ESIC &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('ESIC', ['Yes'=>'Yes', 'No'=>'No'], ($employee->organisation == 'Snayraa')?'Yes':'No', ['placeholder'=>'Select ESIC','class'=>'form-control', 'id'=>'ESIC', 'required'])}}
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">MLWF &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('MLWF', ['Yes'=>'Yes', 'No'=>'No'], ($employee->organisation == 'Snayraa')?'Yes':'No', ['placeholder'=>'Select MLWF','class'=>'form-control', 'id'=>'MLWF', 'required'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">TDS &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('TDS', ['Yes'=>'Yes', 'No'=>'No'], ($employee->organisation == 'Snayraa')?'Yes':'No', ['placeholder'=>'Select TDS','class'=>'form-control', 'id'=>'TDS', 'required'])}}
                                                    </div>
                                                </div>  
                                            </div>
                                            <hr>
                                        </div>
                                    </div> 
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-5"></div>
                            <div class="col-md-3">
                                {{Form::hidden('_method', 'PUT')}}
                                <button type="Submit" class="empAdd btn btn-success btn-lg">Update</button>
                                <a href="/employees" class="btn btn-danger btn-lg">Cancel</a>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    {!! Form::close() !!}     
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

