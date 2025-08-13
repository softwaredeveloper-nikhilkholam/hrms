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
                                    <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Personal</a></li>
                                    <li><a href="#tab2" data-toggle="tab">Education</a></li>
                                    <li><a href="#tab3" data-toggle="tab">IT Dept.</a></li>
                                    <li><a href="#tab5" data-toggle="tab">Account Dept.</a></li>
                                    <li><a href="#tab6" data-toggle="tab">ERP Dept.</a></li>
                                    <li><a href="#tab4" data-toggle="tab">History</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                <!--HR Department-->
                                @if($userType == '51')
                                    <div class="tab-pane active" id="tab1">
                                        <div class="card-body">
                                            <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Employee Role in HRMS&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('userRoleId', $userRoles, $employee->userRoleId , ['placeholder'=>'Select User Role in HRMS ','class'=>'form-control', 'id'=>'userRoleId', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="empName"  value="{{$employee->name}}" id="empName" placeholder="Employee Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Profile Photo &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" name="profPhoto"  value="{{$employee->profilePhoto}}" id="profPhoto" placeholder="Profile Photo">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">WhatsApp No &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->whatsappNo}}" id="whatsappNo" name="whatsappNo" placeholder="WhatsApp No">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Date of Birth&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" name="DOB"  id="empDOB" value="{{$employee->DOB}}" class="form-control" placeholder="select dates"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Gender&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('gender', ['1'=>'Male', '2'=>'Female'], $employee->gender, ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Cast  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="cast" value="{{$employee->cast}}" placeholder="Cast">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Type  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->type}}" name="type" placeholder="Cast Type">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        @if(isset($branches))
                                                            <select name="branchId" id="branchId" class="branchId form-control">
                                                                <option value="">Select Option</option>
                                                                    @foreach($branches as $branch)
                                                                        @if($employee->branchId == $branch->id)
                                                                            <option value="{{$branch->id}}" selected>{{$branch->branchName}}</option>
                                                                        @else
                                                                            <option value="{{$branch->id}}">{{$branch->branchName}}</option>
                                                                        @endif
                                                                    @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Department&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('departmentId', $departments, $employee->departmentId, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'departmentId', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('designationId', $designations, $employee->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId', 'required'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Joining Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" name="empJobJoingDate"   value="{{$employee->jobJoingDate}}" id="empJobJoingDate" class="form-control" placeholder="select jobJoiningDate"/>
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Office Time &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="time" class="form-control"  value="{{$employee->startTime}}" name="jobStartTime">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="time" class="form-control"  value="{{$employee->endTime}}" name="jobEndTime">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Marital Status&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], $employee->maritalStatus, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus', 'required'])}}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Email ID &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="email" class="email form-control"  value="{{$employee->email}}" id="personalEmail" name="personalEmail" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Teaching Subject &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->teachingSubject}}"  name="teachingSubject" placeholder="Teaching Subject">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Reference &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->reference}}"  name="reference" placeholder="Reference">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank IFSC No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->bankIFSCCode}}" id="bankIFSCNo" name="bankIFSCNo" placeholder="Bank IFSC No.">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="bankName" value="{{$employee->bankName}}" placeholder="bankName">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank A/c No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->bankAccountNo}}" id="bankAccountNo" name="bankAccountNo" placeholder="Cast Type">
                                                    </div>
                                                </div>                                       
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Qualification&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->qualification}}" id="qualification" name="qualification" placeholder="Qualification" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Aadhaar Card No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" maxlength="12" value="{{$employee->AADHARNo}}" id="aadhaarCardNo" name="aadhaarCardNo" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Upload Aadhaar Photo Copy.&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" accept="image/*" name="aadhaarCardNoPhoto">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">PAN No&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->PANNo}}" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Upload PAN No Photo Copy&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" value="{{$employee->PANNo}}" name="PANCardPhoto" accept="image/*" placeholder="PAN No">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Instagram id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->instagramId}}" name="instagramId" placeholder="Insta id">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Twitter id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->twitterId}}" name="twitterId" placeholder="Twitter id">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Facebook id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->facebookId}}" name="facebookId" placeholder="Facebook Id">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Present Address</h4>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Address&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                <input type="text" class="comPresentAddress form-control"  value="{{$employee->presentAddress}}" id="comPresentAddress" name="presentAddress" placeholder="Address" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Permanent Address&nbsp;&nbsp;
                                                    <b style="color:blue;font-size:14px;">Same As Present <input type="checkbox" id="presentToPermanent" name="presentToPermanent" style="background-color:green;"></b> </h4>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Address&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                <input type="text" class="permanentAddress form-control"  value="{{$employee->permanentAddress}}" id="permanentAddress" name="comPermanentAddress" placeholder="Address" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                            <hr>
                                            <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Family Members Details</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Name&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="familyName form-control" placeholder="Name">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label class="form-label">Age&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="familyAge form-control" placeholder="Age">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Relation&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="familyRelation form-control" placeholder="Relation">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Occupation&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="familyOccupation form-control" placeholder="Occupation">
                                                    </div>
                                                </div> 
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Contact No&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="familyContactNo form-control" placeholder="Contact No">
                                                    </div>
                                                </div> 
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label class="form-label"></label>
                                                        <button type="button" class="btn btn-primary" style="margin-top:32px;"  value="{{$employee->userRoleId}}" id="addFamilyTableTBody">Add Family Member</button>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="familyTable" class="table familyTable table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Age</th>
                                                                <th>Relation</th>
                                                                <th>Occupation</th>
                                                                <th>Contact No</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>	
                                                            @if($empFamilyDet)
                                                                <input type="hidden" value="1" id="editEmpFlag">
                                                                @foreach($empFamilyDet as $family)
                                                                    <tr>
                                                                        <td><input type="text" class="form-control" value="{{$family->name}}" name="familyName[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->age}}" name="familyAge[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->relation}}" name="familyRelation[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->occupation}}" name="familyOccupation[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->contactNo}}" name="familyContactNo[]{{$family->id}}"/></td>
                                                                        <td><input type="button" class="ibtnDel btn btn-md btn-danger"  value="Delete"></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif											
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Fees Concession&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('feesConcetionId', ['1'=>'Yes', '2'=>'No'], $employee->feesConcession, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'feesConcetionId', 'required'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <h4 class="mb-5 mt-3 font-weight-bold feesCon" style="color:Red;">Fees Concession Details</h4>
                                            <div class="row feesConRow">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Name of Student admitted in AW&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="studentName form-control" placeholder="Name of Student admitted in AW">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Student Branch&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        @if(isset($branches))
                                                            <select id="studBranch" class="studBranch form-control">
                                                                <option value="">Select Option</option>
                                                                    @foreach($branches as $branch)
                                                                        <option value="{{$branch->branchName}}">{{$branch->branchName}}</option>
                                                                    @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Std/Div&nbsp;<span class="text-red">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="std form-control" placeholder="Std/Div">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label"></label>
                                                        <button type="button" class="btn btn-primary" style="margin-top:32px;"  value="{{$employee->userRoleId}}" id="addStudent">Add Student</button>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row feesConRow2">
                                                <div class="table-responsive">
                                                    <table id="feesConTable" class="table feesConTable table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th width="3">Branch</th>
                                                                <th width="2">Std/Div</th>
                                                                <th>Tution(T1)</th>
                                                                <th>Bus(T1)</th>
                                                                <th>WorkS(T1)</th>
                                                                <th>Tution(T2)</th>
                                                                <th>Bus(T2)</th>
                                                                <th>WorkS(T2)</th>
                                                                <th>Tution(T3)</th>
                                                                <th>Bus(T3)</th>
                                                                <th>WorkS(T3)</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>		
                                                            @if(isset($feesConcession))
                                                                <input type="hidden" value="1" id="editFeesCon">
                                                                @foreach($feesConcession as $fees)
                                                                    <tr>
                                                                        <td><input type="text" class="form-control" value="{{$fees->studName}}" name="studName[]{{$fees->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$fees->studBranch}}" name="studBranch[]{{$fees->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$fees->studStdDiv}}" name="studStdDiv[]{{$fees->id}}"/></td>
                                                                        <td><input type="checkbox" name="TutionT1[]" class="form-control" {{($fees->TutionT1 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="BusT1[]" class="form-control" {{($fees->BusT1 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="WorkST1[]" class="form-control" {{($fees->WorkST1 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="TutionT2[]" class="form-control" {{($fees->TutionT2 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="BusT2[]" class="form-control" {{($fees->BusT2 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="WorkST2[]" class="form-control" {{($fees->WorkST2 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="TutionT3[]" class="form-control" {{($fees->TutionT3 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="BusT3[]" class="form-control" {{($fees->BusT3 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="WorkST3[]" class="form-control" {{($fees->WorkST3 == 'On')?'checked':''}}></td>
                                                                        <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="X"></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif										
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <hr>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Experience / Fresher&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('workDet', ['1'=>'Fresher', '2'=>'Experience'], $employee->workingStatus, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'workDet', 'required'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details</h4>
                                            <div class="row experienceDetRow">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Name &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experName"  value="{{$employee->experName}}" placeholder="Name">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experDesignation"  value="{{$employee->experDesignation}}" placeholder="Designation" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experLastSalary"  value="{{$employee->experLastSalary}}" placeholder="Last Salary" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Duration &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experDuration"  value="{{$employee->experDuration}}" placeholder="Duration" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experJobDesc"  value="{{$employee->experJobDesc}}" placeholder="First Name" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experReasonLeaving"  value="{{$employee->experReasonLeaving}}" placeholder="Reason for Leaving" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experCompanyCont"  value="{{$employee->experCompanyCont}}" placeholder="Company Contact No." >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane" id="tab2">
                                        <div class="card-body">
                                        <input type="hidden" value="{{$docCount}}" id="docCount">
                                        <h4 class="mb-5 mt-3 font-weight-bold">Education Details</h4>
                                        <h5 class="requiredDocMessage mb-5 mt-3 font-weight-bold" style="color:red;">Please Select First Department and Designation.</h5>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="requiredDocTable" class="requiredDocTable table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th width="50%">Exam / Document Name</th>
                                                                <th width="30%">Remarks</th>
                                                                <th width="10%">Original Copy</th>
                                                                <th width="10%">Photo Copy</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($requiredDocs))
                                                                @foreach($requiredDocs as $doc)
                                                                    <tr>
                                                                        <td>{{$doc->name}}</td>
                                                                        <td>{{$doc->remarks}}</td>
                                                                        <td><input type="checkbox" class="form-control" name="origCopy[]{{$doc->reqId}}" {{($doc->originalCopy == 'Yes')?'Checked':''}} /></td>
                                                                        <td><input type="checkbox" class="form-control" name="duplCopy[]{{$doc->reqId}}" {{($doc->photoCopy == 'Yes')?'Checked':''}} />
                                                                            <input type="hidden" name="reqDocId[]{{$doc->reqId}}" value="{{$doc->reqId}}">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="tab-pane active" id="tab1">
                                        <div class="card-body">
                                            <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Employee Role in HRMS&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('userRoleId', $userRoles, $employee->userRoleId , ['placeholder'=>'Select User Role in HRMS ','class'=>'form-control', 'id'=>'userRoleId', 'disabled'])}}
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
                                                        <label class="form-label">Profile Photo &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" name="profPhoto"  value="{{$employee->profilePhoto}}" id="profPhoto" placeholder="Profile Photo" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">WhatsApp No &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->whatsappNo}}" id="whatsappNo" name="whatsappNo" placeholder="WhatsApp No" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Date of Birth&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" name="DOB"  id="empDOB" value="{{$employee->DOB}}" class="form-control" placeholder="select dates" disabled/>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Gender&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('gender', ['1'=>'Male', '2'=>'Female'], $employee->gender, ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Cast  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="cast" value="{{$employee->cast}}" placeholder="Cast" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Type  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->type}}" name="type" placeholder="Cast Type" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        @if(isset($branches))
                                                            <select name="branchId" id="branchId" class="branchId form-control" disabled>
                                                                <option value="">Select Option</option>
                                                                    @foreach($branches as $branch)
                                                                        @if($employee->branchId == $branch->id)
                                                                            <option value="{{$branch->id}}" selected>{{$branch->branchName}}</option> 
                                                                        @else
                                                                            <option value="{{$branch->id}}">{{$branch->branchName}}</option>
                                                                        @endif
                                                                    @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Department&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('departmentId', $departments, $employee->departmentId, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'departmentId', 'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('designationId', $designations, $employee->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId', 'disabled'])}}
                                                    </div>
                                                </div>
                                                

                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Joining Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" name="empJobJoingDate"   value="{{$employee->jobJoingDate}}" id="empJobJoingDate" class="form-control" placeholder="select jobJoiningDate" disabled/>
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Office Time &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="time" class="form-control"  value="{{$employee->startTime}}" name="jobStartTime" disabled>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="time" class="form-control"  value="{{$employee->endTime}}" name="jobEndTime" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Marital Status&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], $employee->maritalStatus, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Salary Scale &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->salaryScale}}" name="salaryScale" placeholder="Salary Scale" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Email ID &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="email" class="email form-control"  value="{{$employee->email}}" id="personalEmail" name="personalEmail" placeholder="Email" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Teaching Subject &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->teachingSubject}}"  name="teachingSubject" placeholder="Teaching Subject" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Reference &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->reference}}"  name="reference" placeholder="Reference" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank IFSC No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->bankIFSCCode}}" id="bankIFSCNo" name="bankIFSCNo" placeholder="Bank IFSC No." disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="bankName" value="{{$employee->bankName}}" placeholder="bankName" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank A/c No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->bankAccountNo}}" id="bankAccountNo" name="bankAccountNo" placeholder="Cast Type" disabled>
                                                    </div>
                                                </div>                                       
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Qualification&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->qualification}}" id="qualification" name="qualification" placeholder="Qualification" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Aadhaar Card No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" maxlength="12" value="{{$employee->AADHARNo}}" id="aadhaarCardNo" name="aadhaarCardNo" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Upload Aadhaar Photo Copy.&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" accept="image/*" name="aadhaarCardNoPhoto" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">PAN No&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control"  value="{{$employee->PANNo}}" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Upload PAN No Photo Copy&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" value="{{$employee->PANNo}}" name="PANCardPhoto" accept="image/*" placeholder="PAN No" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Instagram id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->instagramId}}" name="instagramId" placeholder="Insta id" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Twitter id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->twitterId}}" name="twitterId" placeholder="Twitter id" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Facebook id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" value="{{$employee->facebookId}}" name="facebookId" placeholder="Facebook Id" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Present Address</h4>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Address&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                <input type="text" class="comPresentAddress form-control"  value="{{$employee->presentAddress}}" id="comPresentAddress" name="presentAddress" placeholder="Address" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Permanent Address&nbsp;&nbsp;
                                                    <b style="color:blue;font-size:14px;">Same As Present <input type="checkbox" id="presentToPermanent" name="presentToPermanent" style="background-color:green;"></b> </h4>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Address&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                <input type="text" class="permanentAddress form-control"  value="{{$employee->permanentAddress}}" id="permanentAddress" name="comPermanentAddress" placeholder="Address" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <hr>
                                            <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Family Members Details</h4>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="familyTable" class="table familyTable table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Age</th>
                                                                <th>Relation</th>
                                                                <th>Occupation</th>
                                                                <th>Contact No</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>	
                                                            @if($empFamilyDet)
                                                                <input type="hidden" value="1" id="editEmpFlag">
                                                                @foreach($empFamilyDet as $family)
                                                                    <tr>
                                                                        <td><input type="text" class="form-control" value="{{$family->name}}" name="familyName[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->age}}" name="familyAge[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->relation}}" name="familyRelation[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->occupation}}" name="familyOccupation[]{{$family->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$family->contactNo}}" name="familyContactNo[]{{$family->id}}"/></td>
                                                                        <td><input type="button" class="ibtnDel btn btn-md btn-danger"  value="Delete"></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif											
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Fees Concession&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('feesConcetionId', ['1'=>'Yes', '2'=>'No'], $employee->feesConcession, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'feesConcetionId', 'disabled'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <h4 class="mb-5 mt-3 font-weight-bold feesCon" style="color:Red;">Fees Concession Details</h4>
                                            <div class="row feesConRow2">
                                                <div class="table-responsive">
                                                    <table id="feesConTable" class="table feesConTable table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th width="3">Branch</th>
                                                                <th width="2">Std/Div</th>
                                                                <th>Tution(T1)</th>
                                                                <th>Bus(T1)</th>
                                                                <th>WorkS(T1)</th>
                                                                <th>Tution(T2)</th>
                                                                <th>Bus(T2)</th>
                                                                <th>WorkS(T2)</th>
                                                                <th>Tution(T3)</th>
                                                                <th>Bus(T3)</th>
                                                                <th>WorkS(T3)</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>		
                                                            @if(isset($feesConcession))
                                                                <input type="hidden" value="1" id="editFeesCon">
                                                                @foreach($feesConcession as $fees)
                                                                    <tr>
                                                                        <td><input type="text" class="form-control" value="{{$fees->studName}}" name="studName[]{{$fees->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$fees->studBranch}}" name="studBranch[]{{$fees->id}}"/></td>
                                                                        <td><input type="text" class="form-control" value="{{$fees->studStdDiv}}" name="studStdDiv[]{{$fees->id}}"/></td>
                                                                        <td><input type="checkbox" name="TutionT1[]" class="form-control" {{($fees->TutionT1 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="BusT1[]" class="form-control" {{($fees->BusT1 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="WorkST1[]" class="form-control" {{($fees->WorkST1 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="TutionT2[]" class="form-control" {{($fees->TutionT2 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="BusT2[]" class="form-control" {{($fees->BusT2 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="WorkST2[]" class="form-control" {{($fees->WorkST2 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="TutionT3[]" class="form-control" {{($fees->TutionT3 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="BusT3[]" class="form-control" {{($fees->BusT3 == 'On')?'checked':''}}></td>
                                                                        <td><input type="checkbox" name="WorkST3[]" class="form-control" {{($fees->WorkST3 == 'On')?'checked':''}}></td>
                                                                        <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="X"></td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif										
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <hr>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Experience / Fresher&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('workDet', ['1'=>'Fresher', '2'=>'Experience'], $employee->workingStatus, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'workDet', 'disabled'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details</h4>
                                            <div class="row experienceDetRow">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Name &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experName"  value="{{$employee->experName}}" placeholder="Name" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experDesignation"  value="{{$employee->experDesignation}}" placeholder="Designation" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experLastSalary"  value="{{$employee->experLastSalary}}" placeholder="Last Salary" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Duration &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experDuration"  value="{{$employee->experDuration}}" placeholder="Duration" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experJobDesc"  value="{{$employee->experJobDesc}}" placeholder="First Name" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experReasonLeaving"  value="{{$employee->experReasonLeaving}}" placeholder="Reason for Leaving" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experCompanyCont"  value="{{$employee->experCompanyCont}}" placeholder="Company Contact No." disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane" id="tab2">
                                        <div class="card-body">
                                        <input type="hidden" value="{{$docCount}}" id="docCount">
                                        <h4 class="mb-5 mt-3 font-weight-bold">Education Details</h4>
                                        <h5 class="requiredDocMessage mb-5 mt-3 font-weight-bold" style="color:red;">Please Select First Department and Designation.</h5>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table id="requiredDocTable" class="requiredDocTable table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th width="50%">Exam / Document Name</th>
                                                                <th width="30%">Remarks</th>
                                                                <th width="10%">Original Copy</th>
                                                                <th width="10%">Photo Copy</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($requiredDocs))
                                                                @foreach($requiredDocs as $doc)
                                                                    <tr>
                                                                        <td>{{$doc->name}}</td>
                                                                        <td>{{$doc->remarks}}</td>
                                                                        <td><input type="checkbox" class="form-control" name="origCopy[]{{$doc->reqId}}" {{($doc->originalCopy == 'Yes')?'Checked':''}} disabled/></td>
                                                                        <td><input type="checkbox" class="form-control" name="duplCopy[]{{$doc->reqId}}" {{($doc->photoCopy == 'Yes')?'Checked':''}} disabled/>
                                                                            <input type="hidden" name="reqDocId[]{{$doc->reqId}}" value="{{$doc->reqId}}">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($empStationaryDet))
                                    <div class="tab-pane" id="tab3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">EPF Number &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="EPFNumber" value="{{$empStationaryDet->EPFNumber}}" placeholder="EPF Number">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">ESI Number &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="ESINumber" placeholder="ESI" value="{{$empStationaryDet->ESI}}" >
                                                    </div>
                                                </div>                                       
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">i-card &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="iCard" placeholder="i-card" value="{{$empStationaryDet->iCard}}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Visiting card &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="visitingCard" placeholder="Visiting card"value="{{$empStationaryDet->visitingCard}}" >
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Office Key &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="officeKeys" placeholder="Keys" value="{{$empStationaryDet->officeKeys}}" >
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Email Id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="stationaryEmailId" placeholder="Email Id" value="{{$empStationaryDet->stationaryEmailId}}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Password &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="stationaryPassword" placeholder="Password" value="{{$empStationaryDet->stationaryPassword}}" >
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Uniform &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('uniform', $uniforms, $empStationaryDet->uniform, ['placeholder'=>'Select Uniform','class'=>'uniform form-control', 'id'=>'uniform'])}}
                                                    </div>
                                                </div>                                       
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Laptop &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('stationaryLaptopId', $laptops, $empStationaryDet->stationaryLaptopId, ['placeholder'=>'Select Laptop','class'=>'stationaryLaptopId form-control', 'id'=>'stationaryLaptopId'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"  value="{{$employee->userRoleId}}" id="laptopDet">
                                                <div class="col-md-12">
                                                    <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Laptop Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                            <tbody>	
                                                                <tr>	
                                                                    <td><b>MAC Id</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="MACId" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Motherboard</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="motherboard" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Motherboard Id</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="motherboardId" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Serial Number</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="motherboardSN" class="form-control" value="-" readonly></td>	
                                                                </tr>	
                                                                <tr>	
                                                                    <td><b>Keyboard</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="keyboard" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Keyboard serial number</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="keyboardSN" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Mouse Pad</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="mousePad" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Monitor</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="monitor" class="form-control" value="-" readonly></td>		
                                                                </tr>	
                                                                <tr>	
                                                                    <td><b>Monitor serial number</td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="monitorSN" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Antivirus</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="antivirus" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Antivirus key</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="antiviruskey" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Antivirus Expiry</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="antivirusExpiry" class="form-control" value="-" readonly></td>	
                                                                </tr>	
                                                                <tr>	
                                                                    <td><b>User Id</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="userId" class="form-control" value="-" readonly></td>								
                                                                    <td><b>Password</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="password" class="form-control" value="-" readonly></td>								
                                                                    <td><b>Extra Materials</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="extraMaterial" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Remarks</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="remarks" class="form-control" value="-" readonly></td>	
                                                                </tr>	
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Mobile &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('stationaryMobileId', $mobiles, $empStationaryDet->stationaryMobileId, ['placeholder'=>'Select Mobile','class'=>'stationaryMobileId form-control', 'id'=>'stationaryMobileId'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"  value="{{$employee->userRoleId}}" id="mobileDet">
                                                <div class="col-md-12">
                                                    <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Mobile Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                            <tbody>	
                                                                <tr>	
                                                                    <td><b>Model No</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="modelNo" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Charger</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="mobileCharger" class="form-control" value="-" readonly></td>									
                                                                </tr>	
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Simcard &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('stationarySimcardId', $simcards, $empStationaryDet->stationarySimcardId, ['placeholder'=>'Select Simcard','class'=>'stationarySimcardId form-control', 'id'=>'stationarySimcardId'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"  value="{{$employee->userRoleId}}" id="simcardDet">
                                                <div class="col-md-12">
                                                    <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Simcard Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                            <tbody>	
                                                                <tr>	
                                                                    <td><b>Phone No</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="simPhoneNo" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Company Name</b></td>										
                                                                    <td><input type="text"  value="{{$employee->userRoleId}}" id="companyName" class="form-control" value="-" readonly></td>									
                                                                </tr>	
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    <div class="tab-pane" id="tab3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">EPF Number &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="EPFNumber" placeholder="EPF Number">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">ESI Number &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="ESINumber" placeholder="ESI">
                                                    </div>
                                                </div>                                       
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">i-card &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="iCard" placeholder="i-card">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Visiting card &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="visitingCard" placeholder="Visiting card">
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="officeKeys" placeholder="Keys">
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Email Id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="stationaryEmailId" placeholder="Email Id">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Password &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="stationaryPassword" placeholder="Password">
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Uniform &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('uniform', $uniforms, NULL, ['placeholder'=>'Select Uniform','class'=>'uniform form-control', 'id'=>'uniform'])}}
                                                    </div>
                                                </div>                                       
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Laptop &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('stationaryLaptopId', $laptops, NULL, ['placeholder'=>'Select Laptop','class'=>'stationaryLaptopId form-control', 'id'=>'stationaryLaptopId'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="laptopDet">
                                                <div class="col-md-12">
                                                    <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Laptop Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                            <tbody>	
                                                                <tr>	
                                                                    <td><b>MAC Id</b></td>										
                                                                    <td><input type="text" id="MACId" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Motherboard</b></td>										
                                                                    <td><input type="text" id="motherboard" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Motherboard Id</b></td>										
                                                                    <td><input type="text" id="motherboardId" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Serial Number</b></td>										
                                                                    <td><input type="text" id="motherboardSN" class="form-control" value="-" readonly></td>	
                                                                </tr>	
                                                                <tr>	
                                                                    <td><b>Keyboard</b></td>										
                                                                    <td><input type="text" id="keyboard" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Keyboard serial number</b></td>										
                                                                    <td><input type="text" id="keyboardSN" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Mouse Pad</b></td>										
                                                                    <td><input type="text" id="mousePad" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Monitor</b></td>										
                                                                    <td><input type="text" id="monitor" class="form-control" value="-" readonly></td>		
                                                                </tr>	
                                                                <tr>	
                                                                    <td><b>Monitor serial number</td>										
                                                                    <td><input type="text" id="monitorSN" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Antivirus</b></td>										
                                                                    <td><input type="text" id="antivirus" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Antivirus key</b></td>										
                                                                    <td><input type="text" id="antiviruskey" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Antivirus Expiry</b></td>										
                                                                    <td><input type="text" id="antivirusExpiry" class="form-control" value="-" readonly></td>	
                                                                </tr>	
                                                                <tr>	
                                                                    <td><b>User Id</b></td>										
                                                                    <td><input type="text" id="userId" class="form-control" value="-" readonly></td>								
                                                                    <td><b>Password</b></td>										
                                                                    <td><input type="text" id="password" class="form-control" value="-" readonly></td>								
                                                                    <td><b>Extra Materials</b></td>										
                                                                    <td><input type="text" id="extraMaterial" class="form-control" value="-" readonly></td>									
                                                                    <td><b>Remarks</b></td>										
                                                                    <td><input type="text" id="remarks" class="form-control" value="-" readonly></td>	
                                                                </tr>	
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Mobile &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('stationaryMobileId', $mobiles, NULL, ['placeholder'=>'Select Mobile','class'=>'stationaryMobileId form-control', 'id'=>'stationaryMobileId'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="mobileDet">
                                                <div class="col-md-12">
                                                    <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Mobile Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                            <tbody>	
                                                                <tr>	
                                                                    <td><b>Model No</b></td>										
                                                                    <td><input type="text" id="modelNo" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Charger</b></td>										
                                                                    <td><input type="text" id="mobileCharger" class="form-control" value="-" readonly></td>									
                                                                </tr>	
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Simcard &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('stationarySimcardId', $simcards, NULL, ['placeholder'=>'Select Simcard','class'=>'stationarySimcardId form-control', 'id'=>'stationarySimcardId'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="simcardDet">
                                                <div class="col-md-12">
                                                    <h5 class="mb-5 mt-3 font-weight-bold" style="color:green;">Simcard Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                            <tbody>	
                                                                <tr>	
                                                                    <td><b>Phone No</b></td>										
                                                                    <td><input type="text" id="simPhoneNo" class="form-control" value="-" readonly></td>										
                                                                    <td><b>Company Name</b></td>										
                                                                    <td><input type="text" id="companyName" class="form-control" value="-" readonly></td>									
                                                                </tr>	
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div> 
                                @endif
                                <div class="tab-pane" id="tab4">
                                    <div class="card-body">
                                            <h4>Not Found Any History</h4>
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

