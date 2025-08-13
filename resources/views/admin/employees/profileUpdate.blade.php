<?php

$userType = Session()->get('userType');

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
                    <h4 class="page-title">Update Profile</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Back To List</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">	
                <div class="col-xl-12 col-md-12 col-lg-12">
                    {!! Form::open(['action' => 'admin\employees\EmployeesController@updateProfileInfo', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <ul class="nav panel-tabs">
                                    <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Update Personal Information</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="card-body">
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Id card Status Update</h4>
                                        <h6 class="mb-5 mt-3 font-weight-bold" style="color:Red;">All employees are mandatory to update this form.</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                            @if ($errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <div><b style="color:red;">{{$error}}</b></div>
                                                @endforeach
                                            @endif
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                           <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="name"  value="{{$employee->name}}" maxlength="10" id="phoneNo" placeholder="Phone No" disabled>
                                                </div>
                                            </div>
                                            <!--  <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">WhatsApp No &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control"  value="{{$employee->whatsappNo}}" id="whatsappNo" maxlength="10" name="whatsappNo" placeholder="WhatsApp No" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="date" name="DOB"  id="empDOB" value="{{$employee->DOB}}" class="form-control" placeholder="select dates" disabled/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Cast  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="cast" value="{{$employee->cast}}" placeholder="Cast" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Type  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->type}}" name="type" placeholder="Cast Type" disabled>
                                                </div>
                                            </div>
                                           -->
                                            
                                            <!-- <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Email ID &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="email" class="email form-control"  value="{{$employee->email}}" id="personalEmail" name="personalEmail" disabled placeholder="Email">
                                                </div>
                                            </div> -->
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">ID Card Status&nbsp;&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('idCardStatus', ['No Issued'=>'Not Issued (मिळाले नाही)', 'Temporary ID Issued'=>'Temporary ID Issued (तात्पुरते आयडी आहे)', 'Permanent ID Issued'=>'Permanent ID Issued (कायमस्वरूपीचे आयडी आहे))', 'issuedOrlostDamage'=>'Issued but lost or damaged (मिळाले आहे परंतु हरवलेले/ खराब झालेले आहे)'], $employee->idCardStatus, ['placeholder'=>'Select Status','class'=>'idCardStatus form-control', 'id'=>'idCardStatus', 'required'])}}
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Profile Photo &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="file" class="form-control" name="profPhoto"  value="{{$employee->profilePhoto}}" id="profPhoto" placeholder="Profile Photo" required>
                                                </div>
                                            </div> -->
                                        </div>
                                        <hr>
                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-3">
                                                <input type="hidden" value="{{$employee->id}}" name="empId">
                                                <button type="Submit" class="empAdd btn btn-success btn-lg">Update</button>
                                                <a href="/dashboard" class="btn btn-danger btn-lg">Cancel</a>
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    {!! Form::close() !!}     
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

