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
                    <h4 class="page-title">Required Document</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/requiredDocuments" class="btn btn-primary mr-3">Active List</a>
                            <a href="/requiredDocuments/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/requiredDocuments/create" class="btn btn-success mr-3">Add Required Document</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-1 col-md-1 col-lg-1"></div>
                <div class="col-xl-10 col-md-10 col-lg-10">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Required Document Details</h4>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Required Document Name:</label>
                                            <input type="text" class="form-control" value="{{$requiredDocument->name}}" name="name" readonly>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Remarks:</label>
                                            <input type="text" class="form-control" value="{{$requiredDocument->remarks}}" name="name" readonly>
                                        </div>
                                    </div>                                   
                                </div>  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Added At:</label>
                                            <input type="text" class="form-control" value="{{$util->dateFormat($requiredDocument->created_at,2)}}" name="name" readonly>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Updated By:</label>
                                            <input type="text" class="form-control" value="{{$requiredDocument->updated_by}}" name="name" readonly>
                                        </div>
                                    </div>                                   
                                </div>                       

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <a href="/requiredDocuments/{{$requiredDocument->id}}/edit" class="btn btn-warning btn-lg">
                                            <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>&nbsp;&nbsp;Edit</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-xl-1 col-md-1 col-lg-1"></div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
