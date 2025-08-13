@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Warning Letter</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employeeLetters/list/5" class="btn btn-success mr-3">Letter List</a>
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
                            <h4 class="card-title">Employee Warning Letter</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmployeeLettersController@getWarningLetter', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
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
                                            <label class="form-label">Employee Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('desigantionId', $designations, $empDet->designationId, ['placeholder'=>'Select Desigantion','class'=>'form-control', 'id'=>'desigantionId', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employee Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('branchId', $branches, $empDet->branchId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">DOJ &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="DOJ"  value="{{date('Y-m-d', strtotime($empDet->jobJoingDate))}}" id="" placeholder="Joining Date" disabled>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::open(['action' => 'admin\EmployeeLettersController@generateWarningLetter', 'method' => 'POST', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Warning Date &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="date" class="form-control" name="forDate"  value="{{date('Y-m-d')}}" id="" placeholder="Warning Letter Date" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Signature By<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                {{Form::select('signBy', $signFiles,null, ['placeholder'=>'Select Signature By','class'=>'form-control', 'id'=>'signBy', 'required'])}}
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Upload File &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="file" class="form-control" name="uploadFile"  value="" id="" placeholder="Upload Warning File" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                                <input type="hidden" value="{{$empDet->id}}" name="empId">
                                                <input type="Submit" name="submit" value="Save Warning Letter" style="background-color:seagreen;color:white;" class="btn btn-lg">
                                            <a href="/employeeLetters/list/5" class="btn btn-danger btn-lg">Cancel</a>
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
