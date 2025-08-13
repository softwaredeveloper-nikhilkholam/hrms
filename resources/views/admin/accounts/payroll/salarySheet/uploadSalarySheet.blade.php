<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Salary Sheet</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/accounts/salarySheet" class="btn btn-primary mr-3">Salary Sheet List</a>
                            <a href="#" class="btn btn-success mr-3">Upload Salary Sheet</a>
                            <a href="/employees/uploadEmpExcel" class="btn btn-primary mr-3">Upload Manual Attendance</a>
                        </div>
                    </div>
                </div>
            </div>
        <!--End Page header-->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Active Employees</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\AccountsController@updateSalarySheet', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Sample File Download<span class="text-red">*</span>:</label>
                                            <a href="/admin/sampleTemplate.csv" download>Please Click Here</a>
                                        </div>
                                    </div>  
                                </div>  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Month-Year<span class="text-red">*</span>:</label>
                                            <input type="month" name="month" class="form-control" id="month">
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Excel Sheet<span class="text-red">*</span>:</label>
                                            <input type="file" class="form-control" name="excelFile" placeholder="Upload File" required>
                                            @if($errors->has('excelFile'))
                                                <div class="error">{{ $errors->first('excelFile') }}</div>
                                            @endif
                                        </div>
                                    </div>                                    
                                </div>    
                                <hr>                           
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Upload</button>
                                            <a href="/departments" class="btn btn-danger btn-lg">Cancel</a>
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
