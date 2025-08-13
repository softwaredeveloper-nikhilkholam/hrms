<?php
    $userType = Session()->get('userType');
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
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
            <div class="row">	
                <div class="col-xl-12 col-md-12 col-lg-12">
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <ul class="nav panel-tabs">
                                    <li class="ml-4"><a href="#tab3" class="active" data-toggle="tab">Asset Details</a></li>
                                </ul>
                            </div>
                        </div>
                    !! Form::open(['action' => ['admin\employees\EmployeesController@update', $employee->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab3">
                                    <div class="card-body">
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->empCode}}" id="empName" placeholder="Employee Name" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->name}}" id="empName" placeholder="Employee Name" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->designationName}}" id="empName" placeholder="Employee Name" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        
                                        <div class="table-responsive">
                                            <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0" width="10%">No.</th>
                                                        <th class="border-bottom-0" width="15%">Asset Name</th>
                                                        <th class="border-bottom-0" width="15%">Status</th>
                                                        <th class="border-bottom-0" width="10%">Count</th>
                                                        <th class="border-bottom-0">Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Laptop</td>
                                                        <td>{{Form::select('laptopStatus', ['1'=>'Yes', '2'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control laptopStatus', 'id'=>'laptopStatus'])}}</td>
                                                        <td><input type="text" class="form-control laptopCount" name="laptopCount" id="laptopCount" value="0"></td>
                                                        <td><input type="text" class="form-control laptopRemark" name="laptopRemark" id="laptopRemark" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Desktop</td>
                                                        <td>{{Form::select('desktopStatus', ['1'=>'Yes', '2'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'desktopStatus'])}}</td>
                                                        <td><input type="text" class="form-control" name="desktopCount" id="desktopCount" value="0"></td>
                                                        <td><input type="text" class="form-control" name="desktopRemark" id="desktopRemark" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Mobile</td>
                                                        <td>{{Form::select('mobileStatus', ['1'=>'Yes', '2'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'mobileStatus'])}}</td>
                                                        <td><input type="text" class="form-control" name="mobileCount" id="mobileCount" value="0"></td>
                                                        <td><input type="text" class="form-control" name="mobileRemark" id="mobileRemark" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Simcard</td>
                                                        <td>{{Form::select('simcardStatus', ['1'=>'Yes', '2'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'simcardStatus'])}}</td>
                                                        <td><input type="text" class="form-control" name="simcardCount" id="simcardCount" value="0"></td>
                                                        <td><input type="text" class="form-control" name="simcardRemark" id="simcardRemark" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>PenDrive</td>
                                                        <td>{{Form::select('pendriveStatus', ['1'=>'Yes', '2'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'pendriveStatus'])}}</td>
                                                        <td><input type="text" class="form-control" name="pendriveCount" id="pendriveCount" value="0"></td>
                                                        <td><input type="text" class="form-control" name="pendriveRemark" id="pendriveRemark" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Hard Disk</td>
                                                        <td>{{Form::select('hardDiskStatus', ['1'=>'Yes', '2'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'hardDiskStatus'])}}</td>
                                                        <td><input type="text" class="form-control" name="hardDiskCount" id="hardDiskCount" value="0"></td>
                                                        <td><input type="text" class="form-control" name="hardDiskRemark" id="hardDiskRemark" value=""></td>
                                                    </tr>
                                                </tbody>
                                            </table>
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

