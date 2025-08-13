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
                    <h4 class="page-title">Departments</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/departments" class="btn btn-primary mr-3">Active List</a>
                            <a href="/departments/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/departments/create" class="btn btn-success mr-3">Add</a>
                            
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
                            <h4 class="card-title">Department Details</h4>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Section<span class="text-red">*</span>:</label>
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $department->section, ['placeholder'=>'Select Section','class'=>'form-control', 'id'=>'section', 'disabled'])}}
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Department Name:</label>
                                            <input type="text" class="form-control" value="{{$department->name}}" name="name" placeholder="Branch Name" readonly>
                                        </div>
                                    </div>                                   
                                </div>     
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Added At:</label>
                                            <input type="text" class="form-control" value="{{$util->dateFormat($department->created_at,2)}}" name="name" readonly>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Updated By:</label>
                                            <input type="text" class="form-control" value="{{$department->updated_by}}" name="name" readonly>
                                        </div>
                                    </div>                                   
                                </div>                                  

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <a href="/departments/{{$department->id}}/edit" class="btn btn-warning btn-lg">
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
