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
                    <h4 class="page-title">Employees</h4>
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
                    {!! Form::open(['action' => ['admin\employees\EmployeesController@update', $employee->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li class="ml-4"><a href="#tab3" class="active" data-toggle="tab">Store Department</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab3">
                                    <div class="card-body">
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="empName"  value="{{$employee->empCode}}" id="empName" placeholder="Employee Name" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="empName"  value="{{$employee->name}}" id="empName" placeholder="Employee Name" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">i-card &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="iCard" placeholder="i-card" value="{{(isset($empStationaryDet)?$empStationaryDet->iCard:null)}}" >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Visiting card &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="visitingCard" placeholder="Visiting card"value="{{(isset($empStationaryDet)?$empStationaryDet->visitingCard:null)}}" >
                                                </div>
                                            </div>  
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Office Key &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="officeKeys" placeholder="Keys" value="{{(isset($empStationaryDet)?$empStationaryDet->officeKeys:null)}}" >
                                                </div>
                                            </div> 
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Uniform &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('uniform', ['Waist Coat'=>'Waist Coat', 'Modi Jacket'=>'Modi Jacket','Blazer'=>'Blazer','Driver - set '=>'Driver - set ','Maushi appron'=>'Maushi appron','T-shirt'=>'T-shirt','Sarees'=>'Sarees'], (isset($empStationaryDet)?$empStationaryDet->uniform:null), ['placeholder'=>'Select Uniform','class'=>'uniform form-control', 'id'=>'uniform'])}}
                                                </div>
                                            </div>                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-5"></div>
                            <div class="col-md-3">
                                {{Form::hidden('_method', 'PUT')}}
                                <button type="Submit" class="empAdd btn btn-success btn-lg">Update</button>
                                <a href="/employees" class="btn btn-danger btn-lg">Cancel</a>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    {!! Form::close() !!}     
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

