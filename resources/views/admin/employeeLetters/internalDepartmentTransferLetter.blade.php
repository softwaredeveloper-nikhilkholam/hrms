@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Transfer Letter (Internal Department) Letter</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employeeLetters/list/2" class="btn btn-success mr-3">Letter List</a>
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
                            <h4 class="card-title">Employee Transfer Letter (Internal Department) Letter</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmployeeLettersController@getInternalDepartmentTransferLetter', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
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
                                {!! Form::open(['action' => 'admin\EmployeeLettersController@generateInternalDepartmentTransferLetter', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-5">
                                        <b style="color:blue;">Note: If you Want to change disabled Information <a href="/employees/{{$empDet->id}}/edit" target="_blank">Please click here</a></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empCode}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empDet->name}}" id="empName" placeholder="Employee Code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Previous Department&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('departmentId', $departments, $empDet->departmentId, ['placeholder'=>'Select Department','class'=>'form-control', 'id'=>'departmentId', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employee Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('desigantionId', $designations, $empDet->designationId, ['placeholder'=>'Select Desigantion','class'=>'form-control', 'id'=>'desigantionId', 'disabled'])}}
                                        </div>
                                    </div>                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Department &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                            {{Form::select('newDepartmentId', $departments, null, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'departmentId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                            {{Form::select('newDesignationId', [], null, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label mt-3">Revised Salary &nbsp;<span class="text-red" style="font-size:12px;">(Optional)</span>:</label>
                                            <input type="text" onkeypress="return /[0-9]/i.test(event.key)" class="form-control" name="revSalary"  value="" id="" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Signature By<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('signBy', $signFiles, null, ['placeholder'=>'Select Signature By','class'=>'form-control', 'id'=>'signBy', 'required'])}}
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="forDate"  value="{{date('Y-m-d')}}" id="forDate" placeholder="Joining Date" required>
                                        </div>
                                    </div>
                                    
                                </div>
                                <hr>
                                <div class="row" style="margin-top:10px;">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <input type="hidden" value="{{$empDet->id}}" name="empId">
                                        <input type="hidden" value="{{$empDet->departmentId}}" name="departmentId">
                                        <input type="hidden" value="{{$empDet->designationId}}" name="designationId">
                                        <input type="Submit" name="submit" value="Download Transfer Letter" class="empAdd btn btn-primary btn-lg">
                                        <input type="Submit" name="submit" value="Save Transfer Letter" style="background-color:seagreen;color:white;" class="btn btn-lg">
                                        <a href="/employeeLetters/list/7" class="btn btn-danger btn-lg">Cancel</a>
                                    </div>
                                    <div class="col-md-2"></div>
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
