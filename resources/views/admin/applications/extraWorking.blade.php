<?php
    $user = Auth::user();
    $language = $user->language;
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
                    <h4 class="page-title">Extra Working Day</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Extra Working Day</a>
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
                            <h4 class="card-title">
                                Extra Working Day
                            </h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@updateExtraWorking', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{($language == 1)?'Date':'दिनांक'}}<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="forDate" placeholder="Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Emp Code<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="empCode" placeholder="Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">In Time<span class="text-red">*</span>:</label>
                                            <input type="time" class="form-control" name="inTime" placeholder="In Time">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Out Time<span class="text-red">*</span>:</label>
                                            <input type="time" class="form-control" name="outTime" placeholder="Out Time">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">{{($language == 1)?'Description':'डिस्क्रिप्शन'}}<span class="text-red">*</span>:</label>
                                            <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="AGFDescription" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">{{($language == 1)?'Save':'सेव'}}</button>
                                            <a href="/" class="btn btn-danger btn-lg">{{($language == 1)?'Cancel':'कॅन्सल'}}</a>
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
