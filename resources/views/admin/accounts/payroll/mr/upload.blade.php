<?php 
use App\Helpers\Utility;
$util = new Utility();
$empCode = session()->get('empCode');
$salary = Session()->get('salary');
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
                    <h4 class="page-title">MR Report</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/accounts" class="btn btn-primary mr-3">MR Report</a>
                            <a href="#" class="btn btn-success mr-3">Upload MR Sheet</a>
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
                            <h4>Upload MR Sheet</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\AccountsController@updateUploadMR', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Sample Template <a href="/admin/mrReportSample.xlsx" style="color:red;">Click here...</a></label>
                                        </div>
                                    </div>  
                                </div>    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Month-Year<span class="text-red">*</span>:</label>
                                            <input type="month" name="month" class="form-control" id="month" max="{{date('Y-m', strtotime('-1 month'))}}">
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Excel Sheet<span class="text-red">*</span>:</label>
                                            <input type="file" class="form-control" name="excelFile" placeholder="Upload File" required>
                                        </div>
                                    </div>                                    
                                </div>    
                                <hr>                           
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Upload</button>
                                            <a href="/accounts" class="btn btn-danger btn-lg">Cancel</a>
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
