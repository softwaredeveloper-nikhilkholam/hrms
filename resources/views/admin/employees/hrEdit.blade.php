<?php

$userType = Session()->get('userType');
$transAllowed=Auth::user()->transAllowed;

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
                        <style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
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
                                    <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Personal</a></li>
                                    <li class="ml-4"><a href="#tab2" class="" data-toggle="tab">History</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="card-body">
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Role in HRMS &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('userRoleId', $userRoles, $employee->userRoleId, ['placeholder'=>'Select Role in HRMS ','class'=>'form-control', 'id'=>'userRoleId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Organisation  &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('organisation', $organisations, $employee->organisationId, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation', 'required'])}}
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Venture Type &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('firmType', ['1'=>'AWS', '2'=>'ADF', '3'=>'YB', '4'=>'SNAYRAA', '5'=>'AFS', '6'=>'Aaryans Edutainment'], $employee->firmType , ['placeholder'=>'Select Firm Type ','class'=>'form-control', 'id'=>'firmType', 'required'])}}
                                                </div>
                                            </div> -->
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Employee Code &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="" value="{{$employee->empCode}}" id="empName" placeholder="Employee Code" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">First Name &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="firstName" value="{{$employee->firstName}}" id="empName" placeholder="Employee First Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Middle Name &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="middleName" value="{{$employee->middleName}}" id="empName" placeholder="Employee Middle Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Last Name &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="lastName" value="{{$employee->lastName}}" id="empName" placeholder="Employee Last Name"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Profile Photo  &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="file" class="form-control" name="profilePhoto" id="profilePhoto" placeholder="Profile Photo" {{($employee->profilePhoto != '')?'':'required'}}>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Gender &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], $employee->gender, ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'required'])}}
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Religion &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="region" placeholder="Region"  value="{{$employee->region}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Caste &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="cast" placeholder="Caste"  value="{{$employee->cast}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Sub Caste &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="type" placeholder="Sub Caste"  value="{{$employee->type}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="date" name="DOB"  id="empDOB" class="form-control" value="{{$employee->DOB}}" placeholder="select dates" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Marital Status &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], $employee->maritalStatus, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus', 'required'])}}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Phone No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="phoneNo" onkeypress="return isNumberKey(event)" maxlength="10"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No"  required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">WhatsApp No&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="whatsappNo" onkeypress="return isNumberKey(event)" maxlength="10"  value="{{$employee->whatsappNo}}" name="whatsappNo" placeholder="WhatsApp No">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Email ID&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="email" class="email form-control" value="{{$employee->email}}" id="personalEmail" name="email" placeholder="Email">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Present Address &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                            <textarea class="comPresentAddress form-control" id="comPresentAddress" name="presentAddress"  required>{{$employee->presentAddress}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Permanent Address &nbsp;[ Same As Present <input type="checkbox" id="presentToPermanent" name="presentToPermanent" style="background-color:green;"> ] <span class="text-red" style="font-size:22px;">*</span>:</label>
                                                            <textarea class="permanentAddress form-control" id="permanentAddress" name="permanentAddress" >{{$employee->permanentAddress}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Qualification &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" value="{{$employee->qualification}}"  required>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Experienced / Fresher &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('workingStatus', ['1'=>'Fresher', '2'=>'Experienced'], $employee->workingStatus, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'workDet', 'required'])}}
                                                </div>
                                            </div>
                                        </div>

                                        @if($employee->workingStatus == 2)
                                            @for($i=0; $i < 5; $i++)
                                                <hr>
                                                <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details {{$i+1}}</h4>
                                                <div class="row experienceDetRow">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Name Of the Organisation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experName[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experName:''}}" placeholder="Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experDesignation[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experDesignation:''}}" placeholder="Designation" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Duration From &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="date" class="form-control" name="experFromDuration[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experFromDuration:''}}" placeholder="Duration" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Duration To &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="date" class="form-control" name="experToDuration[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experToDuration:''}}" placeholder="Duration" >
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="experLastSalary[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experLastSalary:''}}" placeholder="Last Salary" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experJobDesc[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experJobDesc:''}}" placeholder="First Name" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experReasonLeaving[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReasonLeaving:''}}" placeholder="Reason for Leaving" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Reporting Auth. Name &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experReportingAuth[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingAuth:''}}" placeholder="Reason for Leaving" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Reporting Auth. Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experReportingDesignation[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingDesignation:''}}" placeholder="Reason for Leaving">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experCompanyCont[]" onkeypress="return isNumberKey(event)" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experCompanyCont:''}}" placeholder="Company Contact No." >
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="row experienceDetRow">
                                                <div class="col-md-12">
                                                    @for($i=0; $i < 5; $i++)
                                                        <hr>
                                                        <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details {{$i+1}}</h4>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Name Of the Organisation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experName[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experName:''}}" placeholder="Name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experDesignation[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experDesignation:''}}" placeholder="Designation" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Duration From &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="date" class="form-control" name="experFromDuration[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experFromDuration:''}}" placeholder="Duration" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Duration To &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="date" class="form-control" name="experToDuration[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experToDuration:''}}" placeholder="Duration" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="experLastSalary[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experLastSalary:''}}" placeholder="Last Salary" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experJobDesc[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experJobDesc:''}}" placeholder="First Name" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experReasonLeaving[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReasonLeaving:''}}" placeholder="Reason for Leaving" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Reporting Auth. Name &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experReportingAuth[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingAuth:''}}" placeholder="Reason for Leaving" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Reporting Auth. Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experReportingDesignation[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingDesignation:''}}" placeholder="Reason for Leaving">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                    <input type="text" class="form-control" name="experCompanyCont[]" onkeypress="return isNumberKey(event)" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experCompanyCont:''}}" placeholder="Company Contact No." >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>                                           
                                        @endif
                                        <hr>
                                        
                                        <h4 style="color:red;">Work Profile Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Branch &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('branchId', $branches, $employee->branchId, ['placeholder'=>'Select Section','class'=>'branchId form-control', 'id'=>'branchId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Section &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('sectionId', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $employee->section, ['placeholder'=>'Select Section','class'=>'sectionId form-control', 'id'=>'sectionId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Department &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('departmentId', $departments, $employee->departmentId, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'departmentId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('designationId', $designations, $employee->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId1 form-control', 'id'=>'designationId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="teachingSubject">
                                                <div class="form-group">
                                                    <label class="form-label">Teaching Subject&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="teachingSubject" value="{{$employee->teachingSubject}}" placeholder="Teaching Subject">
                                                </div>
                                            </div>
                                            @if($userType == '61')
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Salary Offered &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="salaryScale" value="{{$employee->salaryScale}}" placeholder="Salary Scale" required>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Reporting Authority &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    @if(isset($empReportings))
                                                        <select name="reportingId" id="empReportingId" class=" form-control" required>
                                                            <option value="">Select Option</option>
                                                                @foreach($empReportings as $emp)
                                                                    <option value="{{$emp->id}}" <?php echo ($employee->reportingId == $emp->id)?'selected':''?>>{{$emp->name}}</option>
                                                                @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Reporting Auth. Designation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" id="reportingAuthDesignation" value="{{$desRepoAuth}}" class="form-control" disabled/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Buddy &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('buddyName', $buddyNames, $employee->buddyName, ['placeholder'=>'Select Buddy Name','class'=>'buddyName form-control', 'id'=>'buddyName' ,'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Job Joining Date &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="date" name="jobJoingDate"  id="empJobJoingDate" value="{{$employee->jobJoingDate}}" class="form-control" placeholder="select jobJoiningDate" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Shift &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('shift', ['Day Shift'=>'Day Shift','Night Shift'=>'Night Shift'],$employee->shift, ['placeholder'=>'Select Shift','class'=>'form-control', 'id'=>'shift', 'required'])}}
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">ID Card&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('idCardStatus', ['No Issued'=>'No Issued','Temporary ID Issued'=>'Temporary ID Issued','Permanent ID Issued'=>'Permanent ID Issued'],$employee->idCardStatus, ['placeholder'=>'Select ID Card Status','class'=>'form-control', 'id'=>'idCardStatus'])}}
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group ">
                                                    <label class="form-label">Office Time &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="time" class="form-control" value="{{$employee->startTime}}" name="jobStartTime"  required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="time" class="form-control"  value="{{$employee->endTime}}" name="jobEndTime"  required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contract Start Date&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="date" class="form-control" name="contractStartDate" value="{{$employee->contractStartDate}}" placeholder="contract Start Date">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contract End Date &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span>:</label>
                                                    <input type="date" class="form-control" name="contractEndDate" value="{{$employee->contractEndDate}}" placeholder="contract End Date">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Transport Allowance &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('transAllowed', ['1'=>'Yes', '0'=>'No'], $employee->transAllowed, ['placeholder'=>'Select Transport','class'=>'form-control', 'id'=>'transport', 'required'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 style="color:red;">Bank and other Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Aadhaar Card No. &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" maxlength="12" value="{{$employee->AADHARNo}}" id="aadhaarCardNo"  value="{{((isset($application))?$application->AADHARNo:'')}}" name="aadhaarCardNo"  required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">PAN No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No"  value="{{$employee->PANNo}}"  required>
                                                </div>
                                            </div>                                           
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Name&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="bankName" placeholder="bankName"  value="{{$employee->bankName}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Branch&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankranch" name="branchName" placeholder="Bank Branch"  value="{{$employee->branchName}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c Name.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankAccountName" name="bankAccountName" placeholder="Bank Account Name"   value="{{$employee->bankAccountName}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankAccountNo" name="bankAccountNo" onkeypress="return isNumberKey(event)" placeholder="Bank Account No"   value="{{$employee->bankAccountNo}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">IFSC Code&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankIFSCCode" name="bankIFSCCode" placeholder="Bank IFSC Code"   value="{{$employee->bankIFSCCode}}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">PF Number&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="pfNumber" style="text-transform:uppercase" name="pfNumber" placeholder="PF Number"   value="{{$employee->pfNumber}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">UID Number&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="uIdNumber" style="text-transform:uppercase" name="uIdNumber" placeholder="UID Number"   value="{{$employee->uIdNumber}}"">
                                                </div>
                                            </div>                                             
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Reference&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('reference', ['Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Whatsapp'=>'Whatsapp', 'Website'=>'Website', 'Friend to friend'=>'Friend to friend', 'Authority Relative'=>'Authority Relative', 'AWS School'=>'AWS School', 'Newspaper'=>'Newspaper', 'Other'=>'Other'], $employee->reference, ['placeholder'=>'Select Source','class'=>'form-control', 'id'=>'reference', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Instagram id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="instagramId" placeholder="Insta id" value="{{$employee->instagramId}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Facebook id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="facebookId" placeholder="Facebook Id" value="{{$employee->facebookId}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Twitter id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="twitterId" placeholder="Twitter id" value="{{$employee->twitterId}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 class="font-weight-bold" style="color:Red;">Emergency Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name 1 &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="emergencyName1" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->name:'')):''}}"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relation &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class=" form-control" placeholder="Relation" name="emergencyRelation1" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->relation:'')):''}}"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Place &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class=" form-control" placeholder="Place" name="emergencyPlace1" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->occupation:'')):''}}" required>
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contact No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" placeholder="Contact No" onkeypress="return isNumberKey(event)" name="emergencyContactNo1" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[0])?$empFamilyDet[0]->contactNo:'')):''}}" required>
                                                </div>
                                            </div> 
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name 2&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="emergencyName2" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->name:'')):''}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class=" form-control" placeholder="Relation" name="emergencyRelation2" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->relation:'')):''}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Place&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class=" form-control" placeholder="Place" name="emergencyPlace2" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->occupation:'')):''}}">
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contact No&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" placeholder="Contact No" onkeypress="return isNumberKey(event)" name="emergencyContactNo2" value="{{(isset($empFamilyDet))?((isset($empFamilyDet[1])?$empFamilyDet[1]->contactNo:'')):''}}">
                                                </div>
                                            </div> 
                                        </div>
                                        <h4 style="color:red;">Attendance Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Type&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('attendanceType', ['Manual'=>'Manual', 'Automatic'=>'Automatic'], $employee->attendanceType, ['placeholder'=>'Select Type','class'=>'form-control', 'id'=>'attendanceType', 'required'])}}
                                                </div>
                                            </div>
                                        </div>

                                        <hr style="height:3px;border-width:0;color:green;background-color:green;">
                                        <h4 class="font-weight-bold" style="color:Red;">Upload Documents</h4>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-bordered border-top card-table table-vcenter text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%">No.</th>
                                                            <th width="70%">Document Name</th>
                                                            <th width="20%">Action</th>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td><h4>Aadhaar Card</h4>
                                                                @if($docs1)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs1->fileName}}" target="_blank">{{$docs1->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs1->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif
                                                            </td>
                                                            <td><input type="file" name="uploadAddharCard" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td><h4>PAN card</h4>
                                                                @if($docs2)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs2->fileName}}" target="_blank">{{$docs2->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs2->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif
                                                            </td>
                                                            <td><input type="file" name="uploadPanCard" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td><h4>Bank Details</h4>
                                                                @if($docs7)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs7->fileName}}" target="_blank">{{$docs7->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs7->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif    
                                                            </td>
                                                            <td><input type="file" name="uploadBankDetails" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td colspan="2"><b style="color:red;">Testimonials / Marksheet</b>
                                                                <table width="100%" style="border:0px;"> 
                                                                    <tr style="border:0px;">
                                                                        <td style="border:0px;">10th :<input type="file"  name="uploadTestimonials10th" class="form-control">
                                                                            @if(!empty($docs3))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs3->fileName}}" target="_blank">{{$docs3->fileName}}</a>
                                                                                <a href="/employees/removeUploadDocs/{{$docs3->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                            @endif 
                                                                        </td>
                                                                        <td style="border:0px;">12th :<input type="file"  name="uploadTestimonials12th" class="form-control">
                                                                            @if(!empty($docs10))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs10->fileName}}" target="_blank">{{$docs10->fileName}}</a>
                                                                                <a href="/employees/removeUploadDocs/{{$docs10->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                            @endif  
                                                                        </td>
                                                                        <td style="border:0px;">Graduation :<input type="file"  name="uploadTestimonialsGrad" class="form-control">
                                                                            @if(!empty($docs11))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs11->fileName}}" target="_blank">{{$docs11->fileName}}</a>
                                                                                <a href="/employees/removeUploadDocs/{{$docs11->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                            @endif 
                                                                        </td>
                                                                    </tr>
                                                                    <tr style="border:0px;">
                                                                        <td style="border:0px;">Post Graduation :<input type="file"  name="uploadTestimonialsPostGrad" class="form-control">
                                                                            @if(!empty($docs12))
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs12->fileName}}" target="_blank">{{$docs12->fileName}}</a>
                                                                                <a href="/employees/removeUploadDocs/{{$docs12->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                            @endif 
                                                                        </td>
                                                                        <td style="border:0px;">Additional Document :<input type="file"  name="uploadTestimonialsOtherEducation" class="form-control">
                                                                            @if($docs14)
                                                                                <br>
                                                                                <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs14->fileName}}" target="_blank">{{$docs14->fileName}}</a>
                                                                                <a href="/employees/removeUploadDocs/{{$docs14->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
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
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs4->fileName}}" target="_blank">{{$docs4->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs4->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif    
                                                            </td>
                                                            <td><input type="file"  name="uploadDrivingLicense" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td><h4>RTO Batch</h4>
                                                                @if($docs5)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs5->fileName}}" target="_blank">{{$docs5->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs5->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif    
                                                            </td>
                                                            <td><input type="file"  name="uploadRtoBatch" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td><h4>Electricity Bill</h4>
                                                                @if($docs6)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs6->fileName}}" target="_blank">{{$docs6->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs6->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif    
                                                            </td>
                                                            <td><input type="file"  name="uploadElectricityBill" class="form-control"></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>8</td>
                                                            <td><h4>Policy Document</h4>
                                                                @if($docs8)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs8->fileName}}" target="_blank">{{$docs8->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs8->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif    
                                                            </td>
                                                            <td><input type="file"  name="uploadEmployeeContract" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>9</td>
                                                            <td><h4>Other Documents</h4>
                                                                @if($docs13)
                                                                    <b style="color:red;"><a href="/admin/images/empDocs/{{$employee->empCode}}/{{$docs13->fileName}}" target="_blank">{{$docs13->fileName}}</a>
                                                                    <a href="/employees/removeUploadDocs/{{$docs13->id}}" class="access-icon role"><span class="feather feather-x text-danger icon-style-circle bg-danger-transparent"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                                                @endif    
                                                            </td>
                                                            <td><input type="file"  name="uploadTestimonialsOther" class="form-control"></td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="card-body">
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">History Details</h4>
                                        <h5>Previous Contracts</h>
                                        <div class="table-responsive">
                                            <table style="width:100%">
                                                <tr>
                                                    <th>Contract No</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>File</th>
                                                    <th>Action<?php $i=1;?></th>
                                                </tr>
                                                <tr>
                                                    <td>Contract No</td>
                                                    <td>From</td>
                                                    <td>To</td>
                                                    <td>File</td>
                                                    <td>Action</td>
                                                </tr>
                                                {!! Form::open(['action' => 'admin\employees\EmployeesController@create', 'method' => 'GET', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
                                                    <tr>
                                                        <td>Contract No {{$i++}}</td>
                                                        <td><input type="date" class="form-control" value="{{date('Y-m-d')}}" name="contractFrom" placeholder="" required></td>
                                                        <td><input type="date" class="form-control" value="{{date('Y-m-d')}}" name="contractTo" placeholder="" required></td>
                                                        <td><input type="file" class="form-control" value="{{date('Y-m-d')}}" name="file" placeholder=""></td>
                                                        <td><button type="submit" id=" " class="btn btn-primary" style="margin-top:20px;">Save</button></td>
                                                    </tr>
                                                {!! Form::close() !!}
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">All Information Verified &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        {{Form::select('verifyStatus', ['1'=>'Yes', '0'=>'No'], $employee->verifyStatus , ['placeholder'=>'Select Verfiy Status','class'=>'form-control', 'id'=>'verifyStatus', 'required'])}}
                                    </div>
                                </div>
                            <div class="col-md-3">
                                {{Form::hidden('_method', 'PUT')}}
                                <input type="hidden" value="" id="reportingIdType" name="reportingIdType">
                                
                                <input type="Submit" class=" btn btn-success btn-lg mt-5" value="Update">
                                <a href="/employees" class="btn btn-danger btn-lg mt-5">Cancel</a>
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

