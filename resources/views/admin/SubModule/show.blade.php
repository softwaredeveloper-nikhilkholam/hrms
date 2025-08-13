<?php
    use App\Helpers\Utility;
    $util = new Utility();
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Sub Module</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/subModule/list" class="btn btn-primary mr-3">Active List</a>
                            <a href="/subModule/dList" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/subModule/create" class="btn btn-success mr-3">Add</a>
                            
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
                            <h4 class="card-title">Sub Module Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Project Name:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Project:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Module:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div> 
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Start Date:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div>
                                </div>        
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">End Date:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div>
                                </div>                                      
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Completed Date<span class="text-red">*</span>:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div>
                                </div> 
                            </div> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Requested By<span class="text-red">*</span>:</label>
                                        <div class="font-weight-bold"  style="font-size:16px !important;">#</div>
                                    </div>
                                </div>                                        
                            </div>                                
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Discription:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">#</div>
                                    </div>
                                </div>   
                            </div>                                   

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-lg-5"></div>
                                    <div class="col-md-12 col-lg-3">
                                        <a href="/subModule/create" class="btn btn-warning btn-lg">
                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>&nbsp;&nbsp;Edit</a>
                                    </div>
                                    <div class="col-md-12 col-lg-4"></div>
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
