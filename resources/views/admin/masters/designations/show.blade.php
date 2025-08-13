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
                    <h4 class="page-title">Designation</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/designations" class="btn btn-primary mr-3">Active List</a>
                            <a href="/designations/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/designations/create" class="btn btn-success mr-3">Add</a>
                            
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
                            <h4 class="card-title">Designation Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Department:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">{{$designation->departmentName}}</div>
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Category:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">{{$designation->category}}</div>
                                    </div>
                                </div>        
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Designation Name:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">{{$designation->name}}</div>
                                    </div>
                                </div>                                      
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Job Opening<span class="text-red">*</span>:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">{{($designation->interviewStatus == 0)?'No':'Yes'}}</div>
                                    </div>
                                </div> 
                            </div> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Profile Details<span class="text-red">*</span>:</label>
                                        <div class="font-weight-bold"  style="font-size:16px !important;">{{$designation->profile}}</div>
                                    </div>
                                </div>                                        
                            </div>                               
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Added At:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">{{$util->dateFormat($designation->created_at,2)}}</div>
                                    </div>
                                </div>   
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:14px !important;">Updated By:</label>
                                        <div class="font-weight-bold"  style="font-size:14px !important;">{{$designation->updated_by}}</div>
                                    </div>
                                </div>                                   
                            </div>                                   

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 col-lg-5"></div>
                                    <div class="col-md-12 col-lg-3">
                                        <a href="/designations/{{$designation->id}}/edit" class="btn btn-warning btn-lg">
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
