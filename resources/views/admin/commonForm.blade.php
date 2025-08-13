@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">HRMS Changes</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">HRMS Change</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'HomeController@updateCommonChanges', 'method' => 'post', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">AGF Filling Cutoff Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="AGFLastDate" value="{{(isset($common))?$common->AGFLastDate:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">AGF Approval Cutoff Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="AGFAuthrityLastDate" value="{{(isset($common))?$common->AGFAuthrityLastDate:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Period From Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="AGFFromDate" value="{{(isset($common))?$common->AGFFromDate:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Period To Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="AGFToDate" value="{{(isset($common))?$common->AGFToDate:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Teaching Notice Period in Month<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="teachingNoticePer" maxlength="12" minlength="1" onkeypress="return isNumberKey(event)" value="{{(isset($common))?$common->teachingNoticePer:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Non Teaching Notice Period in Month<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="nonTeachingNoticePer" maxlength="12" minlength="1" onkeypress="return isNumberKey(event)" value="{{(isset($common))?$common->nonTeachingNoticePer:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Email Id 1<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="ccEmailId1" value="{{(isset($common))?$common->ccEmailId1:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Email Id 2<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="ccEmailId2" value="{{(isset($common))?$common->ccEmailId2:''}}" placeholder="" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Email Id 3:</label>
                                            <input type="text" class="form-control" name="ccEmailId3" value="{{(isset($common))?$common->ccEmailId3:''}}" placeholder="">
                                        </div>
                                    </div> 
                                </div> 
                                <!-- <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">AGF Activation (From Salary Arrears)<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="applyFrom" value="{{(isset($common))?$common->AGFFromDate:''}}" placeholder="" required>
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">AGF Activation (To Salary Arrears)<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="applyTo" value="{{(isset($common))?$common->AGFToDate:''}}" placeholder="" required>
                                        </div>
                                    </div>  
                                </div>     -->
                                <hr>                          
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/home" class="btn btn-danger btn-lg">Cancel</a>
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
