<?php
    use App\Helpers\Utility;
    $util = new Utility();
    $userType = Session()->get('userType');
    $empId = Session()->get('empId');
    $user = Auth::user();
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
                    <h4 class="page-title">Employee Profile</h4>
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
                <div class="col-xl-5 col-md-5 col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-lg-6">
                                <div class="ribbon"></div>
                                    <div class="pro-user mt-3  text-center">
                                        <h5 class="pro-user-username text-dark mb-1 fs-18">{{$employee->name}}&nbsp;&nbsp;
                                            @if($employee->verifyStatus == 1)
                                                <i class="fa fa-check-circle" style="font-size:28px;color:green"></i>
                                            @else
                                                <i class="fa fa-times-circle" style="font-size:28px;color:red"></i>
                                            @endif
                                        </h5>
                                        <h4 class="pro-user-desc text-muted fs-15">{{$employee->departmentName}} Dept.</h4>
                                        <h4 class="pro-user-desc text-muted fs-15">{{$employee->designationName}}</h4>
                                        <h4 class="pro-user-desc text-muted fs-15">{{$employee->branchName}}</h4>
                                        <h4 class="pro-user-desc text-muted fs-15">Date of Join: {{date('d-M-Y', strtotime($employee->jobJoingDate)) }}</h4>
                                        <h5 class="pro-user-username text-dark mb-1 fs-16">Username : {{$employee->username}}</h5>
                                        @if($userType == '51')
                                                <h5 class="pro-user-username text-dark mb-1 fs-16">
                                                    <a class="btn btn-warning" href="/employees/{{$employee->id}}/edit"><i style="font-size:16px;" class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                                </h5>
                                                <h5 class="pro-user-username text-dark mb-1 fs-16">
                                                <a class="btn btn-danger" href="/employees/{{$employee->id}}/resetPassword" onclick="return confirm('Are you sure Reset Password?')"><i style="font-size:16px;" class="fa fa-lock" aria-hidden="true"></i> Reset Password</a>
                                            </h5>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-1 col-md-1 col-lg-1" style="border-left: 2px dashed green;"></div>
                                <div class="col-xl-5 col-md-5 col-lg-5">
                                    <div class="widget-user-image mx-auto text-center">
                                        <center>
                                            @if($employee->profilePhoto == '')
                                                @if($employee->gender == 'Male')
                                                    <img class="thumbnail zoom" height="170px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/admin/images/employees/boy.png">
                                                @else    
                                                    <img class="thumbnail zoom" height="170px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/admin/images/employees/girl.png">
                                                @endif
                                            @else
                                                <img class="thumbnail zoom" height="170px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/admin/profilePhotos/{{$employee->profilePhoto}}">
                                            @endif
                                            <h5 class="pro-user-username text-dark mb-1 fs-16">Employee ID : {{$employee->empCode}}</h5>
                                            @if($employee->active == 1)
                                                <h6 style="color:green;"><b>Active Employee</b></h6>
                                            @endif
                                            @if($employee->active == 0)
                                                <h6 style="color:red;"><b>Deactive Employee</b></h6>
                                            @endif
                                            @if($employee->active == 2)
                                                <h6 style="color:purple;"><b>In-Active Employee</b></h6>
                                            @endif
                                            @if($userType == '51')
                                                <button data-toggle="modal" data-target="#myModal" class="btn btn-primary">Change Status</button>
                                            @endif
                                            <div class="modal fade" id="myModal" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4>Change Status</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($employee->active == 1)
                                                            {!! Form::open(['action' => 'admin\employees\EmployeesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                        @else   
                                                            {!! Form::open(['action' => 'admin\employees\EmployeesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                        @endif
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Reason&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                        <input type="text" class="form-control" id="reason" name="reason" placeholder="Reason" required>
                                                                    </div>
                                                                </div>    
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Last Day&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                        <input type="date" class="form-control" id="lastDay" name="lastDay" placeholder="Reason">
                                                                    </div>
                                                                </div>       
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input type="hidden" readonly value="{{$employee->id}}" name="id">
                                                                        <input type="hidden" readonly value="{{$employee->section}}" name="section">
                                                                        @if($employee->active == '1')
                                                                            <button type="submit" class="btn btn-primary" style="margin-top:38px;">Deactivate</button>                                            
                                                                        @else    
                                                                            <button type="submit" class="btn btn-primary" style="margin-top:38px;">Activate</button>                                            
                                                                        @endif
                                                                    </div>
                                                                </div>                                
                                                            </div>   
                                                        {!! Form::close() !!} 
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="col-xl-7 col-md-7 col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label mb-0 mt-2">Phone</label>
                                            </div>
                                            <div class="col-md-3" style="margin-top: 8px;color:green;">
                                                {{$employee->phoneNo}}
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label mb-0 mt-2">Email</label>
                                            </div>
                                            <div class="col-md-3" style="margin-top: 8px;color:green;">
                                                {{($employee->email == '')?'NA':$employee->email}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label mb-0 mt-2">Birthday</label>
                                            </div>
                                            <div class="col-md-3" style="margin-top: 8px;color:green;">
                                                {{date('d-M-Y', strtotime($employee->DOB)) }}
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label mb-0 mt-2">Gender</label>
                                            </div>
                                            <div class="col-md-3" style="margin-top: 8px;color:green;">
                                                {{$employee->gender}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label mb-0">Present Address</label>
                                            </div>
                                            <div class="col-md-9" style="color:green;">
                                                {{$employee->presentAddress}}
                                            </div>
                                        </div>
                                    </div>
                                    @if($userType == '51' || $userType == '31' || $employee->id == $empId)
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label mb-0">Contract Start Date</label>
                                                </div>
                                                <div class="col-md-3" style="color:red;">
                                                    @if($employee->contractStartDate != '0')
                                                        {{date('d-M-Y', strtotime($employee->contractStartDate)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label mb-0">Contract End Date</label>
                                                </div>
                                                <div class="col-md-3" style="color:red;">
                                                    @if($employee->contractStartDate != '0')
                                                        {{date('d-M-Y', strtotime($employee->contractEndDate)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </div>                                    
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label mb-0">Reporting Authority</label>
                                            </div>
                                            <div class="col-md-3" style="color:red;">
                                                {{$repoName}}&nbsp;[{{$repoDesignation}}]
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label mb-0">Buddy Name</label>
                                            </div>
                                            <div class="col-md-3" style="color:red;">
                                                {{$buddyName}}
                                            </div>
                                        </div>
                                    </div>
                                    @if($changedPassword == 0)
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label mb-0">Reset New Password</label>
                                                </div>
                                                <div class="col-md-9" style="color:red;">
                                                    Welcome@1
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <br>
                                    @endif
                                </div>  
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <!-- Tabs -->
                                @if($userType == '81')
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab3" class="active" data-toggle="tab">Fees Concession</a></li>
                                    </ul>
                                @else
                                    <ul class="nav panel-tabs">
                                        <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Personal</a></li>
                                        <li><a href="#tab2" data-toggle="tab">Documents</a></li>
                                        <li><a href="#tab3" data-toggle="tab">Fees Concession</a></li>
                                        <li><a href="#tab4" data-toggle="tab">Assets</a></li>
                                        <li><a href="#tab6" data-toggle="tab">Employee Verification</a></li>
                                        <li><a href="#tab5" data-toggle="tab">History</a></li>
                                    </ul>
                                @endif
                               
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                            @if($userType != '81')
                                <div class="tab-pane active" id="tab1">
                            @else
                                <div class="tab-pane" id="tab1">
                            @endif
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Role in HRMS : &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('userRoleId', $userRoles, $employee->userRoleId, ['placeholder'=>'Select Role in HRMS ','class'=>'form-control', 'disabled','id'=>'userRoleId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Organisation  &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('organisation', ['Ellora'=>'Ellora', 'Tejasha'=>'Tejasha'], $employee->organisation, ['placeholder'=>'Select Organisation','class'=>'form-control', 'disabled','id'=>'organisation', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Venture Type &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('firmType', ['1'=>'AWS', '2'=>'ADF', '3'=>'YB', '4'=>'SNAYRAA', '5'=>'AFS'], $employee->firmType , ['placeholder'=>'Select Firm Type ','class'=>'form-control', 'disabled','id'=>'firmType', 'required'])}}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Religion  &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="region" placeholder="Religion "  readonly value="{{$employee->region}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Caste &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="cast" placeholder="Caste"  readonly value="{{$employee->cast}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Sub Caste &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="type" placeholder="Sub Caste"  readonly value="{{$employee->type}}">
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Marital Status &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], $employee->maritalStatus, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'disabled','id'=>'maritalStatus', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">WhatsApp No&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="whatsappNo" onkeypress="return isNumberKey(event)" maxlength="10"  readonly value="{{$employee->whatsappNo}}" name="whatsappNo" placeholder="WhatsApp No">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Permanent Address &nbsp;[ Same As Present <input type="checkbox" id="presentToPermanent" name="presentToPermanent" style="background-color:green;"> ] <span class="text-red" style="font-size:22px;">*</span>:</label>
                                                            <textarea class="permanentAddress form-control" id="permanentAddress" name="permanentAddress" disabled>{{$employee->permanentAddress}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Qualification &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" readonly value="{{$employee->qualification}}"  required>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Experience / Fresher &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('workingStatus', ['1'=>'Fresher', '2'=>'Experience'], $employee->workingStatus, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled','id'=>'workDet', 'required'])}}
                                                </div>
                                            </div>
                                        </div>
                                        @if($employee->workingStatus == 2)
                                            @for($i=0; $i < 5; $i++)
                                                @if(isset($empExperiences[$i]))
                                                    <hr>
                                                    <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details {{$i+1}}</h4>
                                                    <div class="row experienceDetRow">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Name Of the Organisation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experName[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experName:''}}" placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experDesignation[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experDesignation:''}}" placeholder="Designation" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Duration From &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="date" class="form-control" name="experFromDuration[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experFromDuration:''}}" placeholder="Duration" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Duration To &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="date" class="form-control" name="experToDuration[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experToDuration:''}}" placeholder="Duration" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="experLastSalary[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experLastSalary:''}}" placeholder="Last Salary" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experJobDesc[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experJobDesc:''}}" placeholder="First Name" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experReasonLeaving[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReasonLeaving:''}}" placeholder="Reason for Leaving" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reporting Auth. Name &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experReportingAuth[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingAuth:''}}" placeholder="Reason for Leaving" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reporting Auth. Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experReportingDesignation[]" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingDesignation:''}}" placeholder="Reason for Leaving">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experCompanyCont[]" onkeypress="return isNumberKey(event)" readonly value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experCompanyCont:''}}" placeholder="Company Contact No." >
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endfor                                         
                                        @endif
                                        <hr>
                                        <h4 style="color:red;">Work Profile Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Branch &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('branchId', $branches, $employee->branchId, ['placeholder'=>'Select Section','class'=>'branchId form-control', 'disabled','id'=>'branchId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Section &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('sectionId', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $employee->section, ['placeholder'=>'Select Section','class'=>'sectionId form-control', 'disabled','id'=>'sectionId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Department &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('departmentId', $departments, $employee->departmentId, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'disabled','id'=>'departmentId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('designationId', $designations, $employee->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'disabled','id'=>'designationId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="teachingSubject">
                                                <div class="form-group">
                                                    <label class="form-label">Teaching Subject&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="teachingSubject" readonly value="{{$employee->teachingSubject}}" placeholder="Teaching Subject">
                                                </div>
                                            </div>
                                            @if($userType == '61')
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Salary Offered &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                        <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="salaryScale" readonly value="{{$employee->salaryScale}}" placeholder="Salary Scale" required>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Buddy &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('buddyName', $buddyNames, $employee->buddyName, ['placeholder'=>'Select Buddy Name','class'=>'buddyName form-control', 'disabled','id'=>'buddyName' ,'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Job Joining Date &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="date" name="jobJoingDate"  id="empJobJoingDate" readonly value="{{$employee->jobJoingDate}}" class="form-control" placeholder="select jobJoiningDate" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Shift &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('shift', ['Day Shift'=>'Day Shift','Night Shift'=>'Night Shift'],$employee->shift, ['placeholder'=>'Select Shift','class'=>'form-control', 'disabled','id'=>'shift', 'required'])}}
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group ">
                                                    <label class="form-label">Office Time &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="time" class="form-control" readonly value="{{$employee->startTime}}" name="jobStartTime"  required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="time" class="form-control"  readonly value="{{$employee->endTime}}" name="jobEndTime"  required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contract Start Date&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="date" class="form-control" name="contractStartDate" readonly value="{{$employee->contractStartDate}}" placeholder="contract Start Date">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contract End Date&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="date" class="form-control" name="contractEndDate" readonly value="{{$employee->contractEndDate}}" placeholder="contract End Date">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Transport Allowance &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('transAllowed', ['1'=>'Yes', '0'=>'No'], $empUser->transAllowed, ['placeholder'=>'Select Transport','class'=>'form-control', 'disabled','id'=>'transport', 'required'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 style="color:red;">Bank and other Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Aadhaar Card No. &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" maxlength="12" readonly value="{{$employee->AADHARNo}}" id="aadhaarCardNo"  readonly value="{{((isset($application))?$application->AADHARNo:'')}}" name="aadhaarCardNo"  required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">PAN No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No"  readonly value="{{$employee->PANNo}}"  required>
                                                </div>
                                            </div>                                           
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Name&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="bankName" placeholder="bankName"  readonly value="{{$employee->bankName}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Branch&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankranch" name="branchName" placeholder="Bank Branch"  readonly value="{{$employee->bankBranchName}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c Name.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankAccountName" name="bankAccountName" placeholder="Bank Account Name"   readonly value="{{$employee->bankAccountName}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankAccountNo" name="bankAccountNo" onkeypress="return isNumberKey(event)" placeholder="Bank Account No"   readonly value="{{$employee->bankAccountNo}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">IFSC Code&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankIFSCCode" name="bankIFSCCode" placeholder="Bank IFSC Code"   readonly value="{{$employee->bankIFSCCode}}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">PF Number&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="pfNumber" style="text-transform:uppercase" name="pfNumber" placeholder="PF Number"   readonly value="{{$employee->pfNumber}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">UAN Number&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="uIdNumber" style="text-transform:uppercase" name="uIdNumber" placeholder="UID Number"   readonly value="{{$employee->uIdNumber}}"">
                                                </div>
                                            </div>                                             
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Reference&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('reference', ['Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Whatsapp'=>'Whatsapp', 'Website'=>'Website', 'Friend to friend'=>'Friend to friend', 'Authority Relative'=>'Authority Relative', 'AWS School'=>'AWS School', 'Newspaper'=>'Newspaper', 'Other'=>'Other'], $employee->reference, ['placeholder'=>'Select Source','class'=>'form-control', 'disabled','id'=>'reference', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Instagram id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="instagramId" placeholder="Insta id" readonly value="{{$employee->instagramId}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Facebook id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="facebookId" placeholder="Facebook Id" readonly value="{{$employee->facebookId}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Twitter id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="twitterId" placeholder="Twitter id" readonly value="{{$employee->twitterId}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 class="font-weight-bold" style="color:Red;">Emergency Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name 1 &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="emergencyName1" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->name:'')):''}}"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relation &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class=" form-control" placeholder="Relation" name="emergencyRelation1" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->relation:'')):''}}"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Place &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class=" form-control" placeholder="Place" name="emergencyPlace1" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->occupation:'')):''}}" required>
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contact No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" placeholder="Contact No" onkeypress="return isNumberKey(event)" name="emergencyContactNo1" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->contactNo:'')):''}}" required>
                                                </div>
                                            </div> 
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name 2&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="emergencyName2" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->name:'')):''}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class=" form-control" placeholder="Relation" name="emergencyRelation2" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->relation:'')):''}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Place&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class=" form-control" placeholder="Place" name="emergencyPlace2" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->occupation:'')):''}}">
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contact No&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" placeholder="Contact No" onkeypress="return isNumberKey(event)" name="emergencyContactNo2" readonly value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->contactNo:'')):''}}">
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab2">
                                    <div class="card-body">
                                    <hr style="height:3px;border-width:0;color:green;background-color:green;">
                                        <h4 class="font-weight-bold" style="color:Red;">Upload Documents</h4>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-bordered border-top card-table table-vcenter text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%">No.</th>
                                                            <th width="90%">Document Name</th>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td><h4>Aadhaar Card</h4>
                                                                @if($docs1)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs1->fileName}}" target="_blank">{{$docs1->fileName}}</a></b>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td><h4>PAN card</h4>
                                                                @if($docs2)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs2->fileName}}" target="_blank">{{$docs2->fileName}}</a></b>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td><h4>Bank Details</h4>
                                                                @if($docs7)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs7->fileName}}" target="_blank">{{$docs7->fileName}}</a></b>
                                                                @endif    
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td colspan="2"><b style="color:red;">Testimonials / Marksheet</b>
                                                                <table width="100%" style="border:0px;"> 
                                                                    <tr style="border:0px;">
                                                                        <td style="border:0px;">10th :
                                                                            @if(!empty($docs3))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs3->fileName}}" target="_blank">{{$docs3->fileName}}</a></b>
                                                                            @endif 
                                                                        </td>
                                                                        <td style="border:0px;">12th :
                                                                            @if(!empty($docs10))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs10->fileName}}" target="_blank">{{$docs10->fileName}}</a></b>
                                                                            @endif  
                                                                        </td>
                                                                        <td style="border:0px;">Graduation :
                                                                            @if(!empty($docs11))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs11->fileName}}" target="_blank">{{$docs11->fileName}}</a></b>
                                                                            @endif 
                                                                        </td>
                                                                    </tr>
                                                                    <tr style="border:0px;">
                                                                        <td style="border:0px;">Post Graduation :
                                                                            @if(!empty($docs12))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs12->fileName}}" target="_blank">{{$docs12->fileName}}</a></b>
                                                                            @endif 
                                                                        </td>
                                                                        <td style="border:0px;">Additional Document :
                                                                            @if(!empty($docs14))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs14->fileName}}" target="_blank">{{$docs14->fileName}}</a></b>
                                                                            @endif 
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td><h4>Driving License</h4>
                                                                @if($docs4)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs4->fileName}}" target="_blank">{{$docs4->fileName}}</a></b>
                                                                @endif    
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td><h4>RTO Batch</h4>
                                                                @if($docs5)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs5->fileName}}" target="_blank">{{$docs5->fileName}}</a></b>
                                                                @endif    
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td><h4>Electricity Bill</h4>
                                                                @if($docs6)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs6->fileName}}" target="_blank">{{$docs6->fileName}}</a></b>
                                                                @endif    
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>8</td>
                                                            <td><h4>Policy Document</h4>
                                                                @if($docs8)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs8->fileName}}" target="_blank">{{$docs8->fileName}}</a></b>
                                                                @endif  
                                                                @if($aggrementDoc)
                                                                    <b style="color:red;"><a href="/admin/empLetters/{{$aggrementDoc->uploadFile}}" target="_blank">{{$aggrementDoc->uploadFile}}</a></b>
                                                                @endif      
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>9</td>
                                                            <td><h4>Other Documents</h4>
                                                                @if($docs13)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs13->fileName}}" target="_blank">{{$docs13->fileName}}</a></b>
                                                                @endif    
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>                                   
                                    </div>
                                </div> 
                                @if($userType == '81')
                                    <div class="tab-pane active" id="tab3">
                                @else
                                    <div class="tab-pane" id="tab3">
                                @endif
                                    <div class="card-body">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Select Fees Concession&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('feesConcetionId', ['Yes'=>'Yes', 'No'=>'No'], $employee->feesConcession, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled','id'=>'feesConcetionId', 'disabled'])}}
                                                </div>
                                            </div>
                                        </div>
                                        @if($employee->feesConcession == 'Yes')
                                           @if($feesConcession) 
                                                <div class="table-responsive">
                                                    <table class="table table-bordered card-table table-vcenter border-top text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-right" width="40%">Academic Year&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                <th class="text-center" width="60%"><input type="text" class="form-control" value="{{$feesConcession->acadmicYear}}" name="acadmicYear" disabled></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-right" width="40%">Student Name&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                <th class="text-center" width="60%"><input type="text" class="form-control" value="{{$feesConcession->studentName}}" placeholder="Student Name" name="studentName" disabled></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-right" width="40%">Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                <th class="text-center" width="60%">{{Form::select('branchId', $branches, $feesConcession->branchId, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-right" width="40%">Class-Section&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                <th class="text-center" width="60%"><input type="text" class="form-control" value="{{$feesConcession->classSection}}" placeholder="Class-Section" name="classSection" disabled></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-right" width="40%">Under Category&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                <th>{{Form::select('category', ['Category 1 (A & B)'=>'Category 1 (A & B)', ' Category 2 (C & D)'=>' Category 2 (C & D)'], $feesConcession->category, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'category', 'disabled'])}}</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered card-table table-vcenter border-top text-nowrap mb-0">
                                                        <thead>
                                                            <tr  style="background-color:#b3e1b0;">
                                                                <th class="text-center" rowspan="2" width="15%">Fee Head</th>
                                                                <th class="text-center" colspan="6" width="85%">Installments</th>
                                                            </tr>
                                                            <tr style="background-color:#b3e1b0;">
                                                                <th class="text-center">1st Inst.</th>
                                                                <th class="text-center">Amt.</th>
                                                                <th class="text-center">2nd Inst.</th>
                                                                <th class="text-center">Amt.</th>
                                                                <th class="text-center">3rd Inst.</th>
                                                                <th class="text-center">Amt.</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">Tuition Fee</th>
                                                                <th class="text-center">{{Form::select('tuitionInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->tuitionInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->tuitionFees1}}" placeholder="0" name="tuitionFees1" disabled></th>
                                                                <th class="text-center">{{Form::select('tuitionInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->tuitionInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->tuitionFees2}}" placeholder="0" name="tuitionFees2" disabled></th>
                                                                <th class="text-center">{{Form::select('tuitionInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->tuitionInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->tuitionFees3}}" placeholder="0" name="tuitionFees3" disabled></th>
                                                            </tr>    
                                                            <tr>
                                                                <th class="text-center">Worksheet Fee</th>
                                                                <th class="text-center">{{Form::select('worksheetInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->worksheetInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->worksheetFees1}}" placeholder="0" name="worksheetFees1" disabled></th>
                                                                <th class="text-center">{{Form::select('worksheetInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->worksheetInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst2', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->worksheetFees2}}" placeholder="0" name="worksheetFees2" disabled></th>
                                                                <th class="text-center">{{Form::select('worksheetInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->worksheetInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst3', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->worksheetFees3}}" placeholder="0" name="worksheetFees3" disabled></th>
                                                            </tr>    
                                                            <tr>
                                                                <th class="text-center">Transport Fee</th>
                                                                <th class="text-center">{{Form::select('transportInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->transportInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->transportFees1}}" placeholder="0" name="transportFees1" disabled></th>
                                                                <th class="text-center">{{Form::select('transportInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->transportInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->transportFees2}}" placeholder="0" name="transportFees2" disabled></th>
                                                                <th class="text-center">{{Form::select('transportInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->transportInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->transportFees3}}" placeholder="0" name="transportFees3" disabled></th>
                                                            </tr>    
                                                            <tr>
                                                                <th class="text-center">GPS Charges</th>
                                                                <th class="text-center">{{Form::select('gpsInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->gpsInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->gpsCharge1}}" placeholder="0" name="gpsCharge1" disabled></th>
                                                                <th class="text-center">{{Form::select('gpsInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->gpsInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->gpsCharge2}}" placeholder="0" name="gpsCharge2" disabled></th>
                                                                <th class="text-center">{{Form::select('gpsInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->gpsInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->gpsCharge3}}" placeholder="0" name="gpsCharge3" disabled></th>
                                                            </tr>                                                               
                                                        </thead>
                                                    </table>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div> 

                                <div class="tab-pane" id="tab4">
                                    <div class="card-body">
                                        @if(isset($systemHistories1))
                                            @if(count($systemHistories1)>0)
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Assigned Laptop List</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered card-table table-vcenter mb-0 laptopTable">
                                                                <tbody>	
                                                                    <tr>	
                                                                        <td width="10%">Sr. No</td>										
                                                                        <td width="80%">Laptop Details</td>										
                                                                        <td width="10%"><b>Assign Date</b><?php $i=1; ?></td>										
                                                                    </tr>
                                                                    @foreach($systemHistories1 as $empH)
                                                                        @if($empH->type == 1)
                                                                            <tr>
                                                                                <td>{{$i++}}</td> 
                                                                                <td> 
                                                                                    <div class="table-responsive">
                                                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                                                            <tbody>	
                                                                                                <tr>	
                                                                                                    <td><b>MAC Id</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="MACId" class="form-control" readonly value="{{$empH->MACId}}" readonly></td>										
                                                                                                    <td><b>Motherboard</b></td>	
                                                                                                    <td colspan="3"><input type="text" id="motherboard" class="form-control" readonly value="{{$empH->motherboard}}" readonly></td>									
                                                                                                </tr>	
                                                                                                <tr>									
                                                                                                    <td><b>Make</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="make" class="form-control" readonly value="{{$empH->make}}" readonly></td>										
                                                                                                    <td><b>Serial Number</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="serialNo" class="form-control" readonly value="{{$empH->serialNo}}" readonly></td>	
                                                                                                </tr>	
                                                                                                <tr>	
                                                                                                    <td><b>Laptop Bag</b></td>										
                                                                                                    <td><input type="text" id="laptopBag" class="form-control" readonly value="{{$empH->lapBag}}" readonly></td>										
                                                                                                    <td><b>Keyboard</b></td>										
                                                                                                    <td><input type="text" id="keyboard" class="form-control" readonly value="{{$empH->keyboard}}" readonly></td>									
                                                                                                    <td><b>Mouse</b></td>										
                                                                                                    <td><input type="text" id="mouse" class="form-control" readonly value="{{$empH->mouse}}" readonly></td>									
                                                                                                    <td><b>Mouse Pad</b></td>										
                                                                                                    <td><input type="text" id="mousePad" class="form-control" readonly value="{{$empH->mousePad}}" readonly></td>		
                                                                                                </tr>	
                                                                                                <tr>	
                                                                                                    <td><b>Antivirus</b></td>										
                                                                                                    <td><input type="text" id="antivirus" class="form-control" readonly value="{{$empH->anti}}" readonly></td>									
                                                                                                    <td><b>Laptop Charger</b></td>										
                                                                                                    <td><input type="text" id="laptopCharger" class="form-control" readonly value="{{$empH->lapCharger}}" readonly></td>									
                                                                                                    <td><b>Extra Material</b></td>										
                                                                                                    <td><input type="text" id="extraMaterial" class="form-control" readonly value="{{$empH->extraMaterial}}" readonly></td>	
                                                                                                </tr>	
                                                                                                <tr>	
                                                                                                    <td><b>User Id</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="username" class="form-control" readonly value="{{$empH->username}}" readonly></td>								
                                                                                                    <td><b>Password</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="password" class="form-control" readonly value="{{$empH->password}}" readonly></td>								
                                                                                                </tr>	
                                                                                                <tr>    
                                                                                                    <td><b>Remarks</b></td>										
                                                                                                    <td colspan="7"><input type="text" id="remarks" class="form-control" readonly value="{{$empH->remarks}}" readonly></td>	
                                                                                                </tr>	
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </td>
                                                                                <td>{{($empH->forDate != '')?date('d-M-Y', strtotime($empH->forDate)):'NA'}}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        @if(isset($systemHistories2))
                                            @if(count($systemHistories2)>0)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Assigned Desktop List</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                                <tbody>	
                                                                    <tr>	
                                                                        <td width="10%">Sr. No</td>										
                                                                        <td width="80%">Desktop Details</td>										
                                                                        <td width="10%"><b>Assign Date</b><?php $i=1; ?></td>											
                                                                    </tr>	
                                                                    @foreach($systemHistories2 as $empH)
                                                                        @if($empH->type == 5)
                                                                            <tr>
                                                                                <td>{{$i++}}</td>
                                                                                <td>
                                                                                    <div class="table-responsive">
                                                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                                                            <tbody>	
                                                                                                <tr>	
                                                                                                    <td><b>MAC Id</b></td>										
                                                                                                    <td colspan="2"><input type="text" id="MACId5" class="form-control" readonly value="-" readonly></td>										
                                                                                                    <td><b>Motherboard</b></td>	
                                                                                                    <td colspan="2"><input type="text" id="motherboard5" class="form-control" readonly value="-" readonly></td>									
                                                                                                    <td><b>Serial Number</b></td>										
                                                                                                    <td><input type="text" id="serialNo5" class="form-control" readonly value="-" readonly></td>	
                                                                                                </tr>	
                                                                                                <tr>	
                                                                                                    <td><b>Keyboard</b></td>										
                                                                                                    <td><input type="text" id="keyboard5" class="form-control" readonly value="-" readonly></td>									
                                                                                                    <td><b>Mouse</b></td>										
                                                                                                    <td><input type="text" id="mouse5" class="form-control" readonly value="-" readonly></td>									
                                                                                                    <td><b>Mouse Pad</b></td>										
                                                                                                    <td><input type="text" id="mousePad5" class="form-control" readonly value="-" readonly></td>		
                                                                                                    <td><b>Antivirus</b></td>										
                                                                                                    <td><input type="text" id="antivirus5" class="form-control" readonly value="-" readonly></td>									
                                                                                                </tr>	
                                                                                                <tr>	
                                                                                                    <td><b>UPS</b></td>										
                                                                                                    <td><input type="text" id="ups5" class="form-control" readonly value="-" readonly></td>									
                                                                                                    <td><b>Extra Material</b></td>										
                                                                                                    <td><input type="text" id="extraMaterial5" class="form-control" readonly value="-" readonly></td>	
                                                                                                </tr>	
                                                                                                <tr>	
                                                                                                    <td><b>User Id</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="username5" class="form-control" readonly value="-" readonly></td>								
                                                                                                    <td><b>Password</b></td>										
                                                                                                    <td colspan="3"><input type="text" id="password5" class="form-control" readonly value="-" readonly></td>								
                                                                                                </tr>	
                                                                                                <tr>    
                                                                                                    <td><b>Remarks</b></td>										
                                                                                                    <td colspan="7"><input type="text" id="remarks5" class="form-control" readonly value="-" readonly></td>	
                                                                                                </tr>	
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </td>
                                                                                <td></td>
                                                                            </td>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="row" id="mobileRow">
                                            <div class="col-md-12">
                                                <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Assigned Mobile List</h5>
                                                <div class="table-responsive">
                                                    <table id="mobileTable1" class="mobileTable1 table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <tbody>	
                                                            <tr>	
                                                                <td width="25%"><b>Mobile Com.</b></td>										
                                                                <td width="25%"><b>Model No</b></td>										
                                                                <td width="25%"><b>IMEI 1</b></td>										
                                                                <td width="15%"><b>Assign Date</b></td>										
                                                                <td width="10%"><b>Action</b></td>										
                                                            </tr>
                                                            @if(isset($mobileHistories))
                                                                @if(count($mobileHistories)>0)
                                                                    <input type="hidden" readonly value="{{count($mobileHistories)}}" id="mobileCt">
                                                                    @foreach($mobileHistories as $empH)
                                                                        <tr>
                                                                            <td><input type="text" class="form-control" readonly value="{{$empH->companyName}}" readonly/></td>
                                                                            <td><input type="text" class="form-control" readonly value="{{$empH->modelNumber}}" readonly/></td>
                                                                            <td><input type="text" class="form-control" readonly value="{{$empH->IMEI1}}" readonly/></td>
                                                                            <td><input type="text" class="form-control" readonly value="{{$empH->forDate}}" name="mobileForDate[]" readonly/>
                                                                            <input type="hidden" class="form-control" readonly value="{{$empH->id}}" name="mobileId1[]"/></td>
                                                                            
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @endif	
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row" id="simcardRow">
                                            <div class="col-md-12">
                                                <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Assigned Simcard List</h5>
                                                <div class="table-responsive">
                                                    <table id="simcardTable" class="simcardTable table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <tbody>	
                                                            <tr>	
                                                                <td width="25%"><b>Operator</b></td>										
                                                                <td width="25%"><b>Mobile No</b></td>										
                                                                <td width="25%"><b>Extra Material</b></td>										
                                                                <td width="15%"><b>Assign Date</b></td>										
                                                                <td width="10%"><b>Action</b></td>										
                                                            </tr>
                                                            @if(isset($otherHistories))
                                                                @if(count($otherHistories)>0)
                                                                    @foreach($otherHistories as $empH)
                                                                        @if($empH->type == 3)
                                                                            <input type="hidden" readonly value="{{count($otherHistories)}}" id="simcardCt1">
                                                                            <tr>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->operatComName}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->mobNumber}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->extraMat}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->forDate}}" name="simcardForDate[]" readonly/>
                                                                                <input type="hidden" class="form-control" readonly value="{{$empH->id}}" name="simcardId1[]"/></td>
                                                                                
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endif	
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row" id="pendriveRow">
                                            <div class="col-md-12">
                                                <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Assigned Pendrive List</h5>
                                                <div class="table-responsive">
                                                    <table id="pendriveTable" class="pendriveTable table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <tbody>	
                                                            <tr>	
                                                                <td width="25%"><b>Company Name</b></td>										
                                                                <td width="25%"><b>Storage Size</b></td>										
                                                                <td width="15%"><b>Assign Date</b></td>										
                                                                <td width="10%"><b>Action</b></td>										
                                                            </tr>
                                                            @if(isset($otherHistories))
                                                                @if(count($otherHistories)>0)
                                                                    @foreach($otherHistories as $empH)
                                                                        @if($empH->type == 4)
                                                                            <input type="hidden" readonly value="{{count($otherHistories)}}" id="pendriveCt1">
                                                                            <tr>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->operatComName}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->storeageSize}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->forDate}}" name="pendriveForDate[]" readonly/>
                                                                                <input type="hidden" class="form-control" readonly value="{{$empH->id}}" name="pendriveId1[]"/></td>
                                                                                
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endif		
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row" id="hardDiskRow">
                                            <div class="col-md-12">
                                                <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Assigned Hard Disk List</h5>
                                                <div class="table-responsive">
                                                    <table id="hardDiskTable" class="hardDiskTable table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <tbody>	
                                                            <tr>	
                                                                <td width="25%"><b>Company Name</b></td>										
                                                                <td width="25%"><b>Storage Size</b></td>										
                                                                <td width="15%"><b>Assign Date</b></td>										
                                                                <td width="10%"><b>Action</b></td>										
                                                            </tr>
                                                            @if(isset($otherHistories))
                                                                @if(count($otherHistories)>0)
                                                                    @foreach($otherHistories as $empH)
                                                                        @if($empH->type == 6)
                                                                            <input type="hidden" readonly value="{{count($otherHistories)}}" id="hardDiskCt1">
                                                                            <tr>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->operatComName}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->storeageSize}}" readonly/></td>
                                                                                <td><input type="text" class="form-control" readonly value="{{$empH->forDate}}" name="hardDiskForDate[]" readonly/>
                                                                                <input type="hidden" class="form-control" readonly value="{{$empH->id}}" name="hardDiskId1[]"/></td>
                                                                                
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endif			
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="tab-pane" id="tab5">
                                    <div class="card-body">
                                        @if(isset($letters))
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">Particular</th>
                                                            <th class="border-bottom-0" width="20%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($letters as $row)
                                                            <tr>
                                                                <td>
                                                                    <h4>
                                                                        @if($row->letterType == '2')
                                                                            Appointment Letter
                                                                        @endif                                         
                                                                    </h4>
                                                                    <h6>{{($row->fromDate)?date('d-m-Y', strtotime($row->fromDate)):'NA'}} To {{($row->toDate)?date('d-m-Y', strtotime($row->toDate)):'NA'}}</h6>
                                                                </td>
                                                                <td>
                                                                    @if($row->letterType == '2')
                                                                        <a href="/employeeLetters/viewAppointmentLetter/{{$row->id}}/1" class="btn btn-primary">View</a>
                                                                    @endif 
                                                                    <!-- @if(date('Y-m-d') >= date('Y-m-d', strtotime('+3 month', strtotime($employee->jobJoingDate))))
                                                                        @if($row->letterType == '2')
                                                                            <a href="/employeeLetters/viewAppointmentLetter/{{$row->id}}/1" class="btn btn-primary">View</a>
                                                                        @endif 
                                                                    @else
                                                                        <div class="alert alert-warning mt-2">
                                                                            Appointment letter can be downloaded after 3 months of joining.
                                                                        </div>
                                                                    @endif -->
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>  
                                
                                <div class="tab-pane" id="tab6">
                                    <div class="card-body">
                                        <h4 style="color:red;">Employee Verification</h4>
                                        <hr>
                                        @if(isset($empVerfication))
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">Particular</th>
                                                            <th class="border-bottom-0" width="20%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Aadhaar Number Verification
                                                                <input type="text" name="verificationRemark5" class="form-control" value="{{$empVerfication->verificationRemark5}}" disabled>
                                                            </td>
                                                            <td>
                                                                {{Form::select('verificationStatus5', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus5, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Document Verification
                                                                <input type="text" name="verificationRemark1" class="form-control" value="{{$empVerfication->verificationRemark1}}" disabled>
                                                            </td>
                                                            <td>
                                                                {{Form::select('verificationStatus1', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus1, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Address Verification
                                                                <input type="text" name="verificationRemark2" class="form-control" value="{{$empVerfication->verificationRemark2}}" disabled>
                                                            </td>
                                                            <td>
                                                                {{Form::select('verificationStatus2', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus2, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Previous Job Verification
                                                                <input type="text" name="verificationRemark3" class="form-control" value="{{$empVerfication->verificationRemark3}}" disabled>
                                                            </td>
                                                            <td>
                                                                {{Form::select('verificationStatus3', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus3, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Reference Verification
                                                                <input type="text" name="verificationRemark4" class="form-control" value="{{$empVerfication->verificationRemark4}}" disabled>
                                                            </td>
                                                            <td>
                                                                {{Form::select('verificationStatus4', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus4, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                        <input type="text" class="form-control" name="reamkrs" placeholder="Remarks" value="{{$empVerfication->remarks}}" disabled>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Updated At<span class="text-red"></span>:</label>
                                                        <input type="text" class="form-control" name="reamkrs" placeholder="Updated At" value="{{date('d-m-Y H:i', strtotime($empVerfication->updated_at))}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Updated By<span class="text-red"></span>:</label>
                                                        <input type="text" class="form-control" name="reamkrs" placeholder="Updated By" value="{{$empVerfication->updated_by}}" disabled>
                                                    </div>
                                                </div>
                                            </div> 
                                        @else
                                            <h5> Employee Verification is Pending....</h5> 
                                        @endif
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
