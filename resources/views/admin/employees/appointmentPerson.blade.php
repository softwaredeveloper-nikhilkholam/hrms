@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Update Appointment</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employees/appointmentPerson" class="btn btn-success mr-3">Update Appointment</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Update Appointment</h4>
                            <h4 class="card-title" style="color:red;">&nbsp;&nbsp;&nbsp;If you want remove any employee code please enter '0' and Update it....</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmployeesController@saveAppointmentPerson', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">AWS MD :</label>
                                            <input type="text" class="form-control" name=""  value="Milind Ladge" id="" placeholder="Employee Code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code 1 :</label>
                                            <input type="text" class="form-control" name="AWSMDEmpCode1"  value="{{isset($MDUsers[0])?$MDUsers[0]->empCode:''}}" id="AWSMDEmpCode1" placeholder="Employee Code">
                                            <label class="form-label" style="color:red;">{{isset($MDUsers[0])?$MDUsers[0]->name:''}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code 2 :</label>
                                            <input type="text" class="form-control" name="AWSMDEmpCode2"  value="{{isset($MDUsers[1])?$MDUsers[1]->empCode:''}}" id="AWSMDEmpCode2" placeholder="Employee Code">
                                            <label class="form-label" style="color:red;">{{isset($MDUsers[1])?$MDUsers[1]->name:''}}</label>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">AWS CEO :</label>
                                            <input type="text" class="form-control" name=""  value="Pratik Ladge" id="" placeholder="Employee Code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code 1 :</label>
                                            <input type="text" class="form-control" name="AWSCEOEmpCode1"  value="{{isset($CEOUsers[0])?$CEOUsers[0]->empCode:''}}" id="AWSCEOEmpCode1" placeholder="Employee Code">
                                            <label class="form-label" style="color:red;">{{isset($CEOUsers[0])?$CEOUsers[0]->name:''}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code 2 :</label>
                                            <input type="text" class="form-control" name="AWSCEOEmpCode2"  value="{{isset($CEOUsers[1])?$CEOUsers[1]->empCode:''}}" id="AWSCEOEmpCode2" placeholder="Employee Code">
                                            <label class="form-label" style="color:red;">{{isset($CEOUsers[1])?$CEOUsers[1]->name:''}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">AWS COO :</label>
                                            <input type="text" class="form-control" name=""  value="Pranav Ladge" id="" placeholder="Employee Code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code 1 :</label>
                                            <input type="text" class="form-control" name="AWSCOOEmpCode1"  value="{{isset($COOUsers[0])?$COOUsers[0]->empCode:''}}" id="AWSCOOEmpCode1" placeholder="Employee Code" >
                                            <label class="form-label" style="color:red;">{{isset($COOUsers[0])?$COOUsers[0]->name:''}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code 2 :</label>
                                            <input type="text" class="form-control" name="AWSCOOEmpCode2"  value="{{isset($COOUsers[1])?$COOUsers[1]->empCode:''}}" id="AWSCOOEmpCode2" placeholder="Employee Code">
                                            <label class="form-label" style="color:red;">{{isset($COOUsers[1])?$COOUsers[1]->name:''}}</label>
                                        </div>
                                    </div>
                                </div>
                               
                                <hr>
                               
                                <div class="form-group mt-6">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
