<?php
    use App\Helpers\Utility;
    $util = new Utility();
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
                    <h4 class="page-title">Holiday</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/holidays" class="btn btn-primary mr-3">Upcomming List</a>
                            <a href="/holidays/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/holidays/create" class="btn btn-primary mr-3">Add Holiday</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-10 col-lg-10">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Holiday Details &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @if($holiday->forDate > date('Y-m-d'))
                                    <a href="/holidays/{{$holiday->id}}/edit" class="btn btn-warning btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fa fa-edit" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Edit"></i>
                                    </a>
                                    <a href="/holidays/{{$holiday->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fa fa-times-circle" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                    </a>
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">                           
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Date : <b>{{date('d-m-Y', strtotime($holiday->forDate))}}</b></label>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Holiday Name : <b>{{$holiday->name}}</b></label>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Holiday Type : <b>{{($holiday->holidayType == '1')?'100% Paid':(($holiday->holidayType == '2')?'50% Paid':'Without Paid')}}</b></label>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Added At : <b>{{date('d-m-Y H:i', strtotime($holiday->created_at))}}</b></label>
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Updated By : <b>{{$holiday->updated_by}}</b></label>
                                    </div>
                                </div>                                      
                            </div> 
                            <hr>
                            <h3>Branch</h3>
                            <div class="row" id="branchDiv">
                                @foreach($branches as $branch)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input branchCheckClass" id="branchCheck{{$branch->id}}" name="branchOption[]" value="{{$branch->id}}" checked disabled>
                                            <label class="form-check-label" for="branchCheck{{$branch->id}}"><b style="color:blue;">{{$branch->branchName}}</b></label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <h3>Departments & Designations</h3>
                            <div class="row"  id="">
                                @foreach($designations as $designation)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input departmentCheckClass" id="departmentCheck{{$designation->id}}" name="departmentOption[]" value="{{$designation->id}}" checked disabled>
                                            <label class="form-check-label" for="departmentCheck{{$designation->id}}"><b style="color:red;">{{$designation->departmentName}}-{{$designation->name}}</b></label>
                                        </div>
                                    </div>
                                @endforeach
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
