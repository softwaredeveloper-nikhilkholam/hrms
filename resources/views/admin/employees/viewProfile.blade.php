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
                    <h4 class="page-title">View Profile Update Request</h4>
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
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="phoneNo"  value="{{$employee->name}}" maxlength="10" id="phoneNo" placeholder="Phone No">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Emp Code&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="phoneNo"  value="{{$employee->empCode}}" maxlength="10" id="phoneNo" placeholder="Phone No">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Phone No&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" maxlength="10" id="phoneNo" placeholder="Phone No">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">WhatsApp No &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control"  value="{{$employee->whatsappNo}}" id="whatsappNo" maxlength="10" name="whatsappNo" placeholder="WhatsApp No">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="date" name="DOB"  id="empDOB" value="{{$employee->DOB}}" class="form-control" placeholder="select dates"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Cast  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="cast" value="{{$employee->cast}}" placeholder="Cast">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Type  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->type}}" name="type" placeholder="Cast Type">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Marital Status&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], $employee->maritalStatus, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus'])}}
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Email ID &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="email" class="email form-control"  value="{{$employee->email}}" id="personalEmail" name="personalEmail" placeholder="Email">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control"  value="{{$employee->bankAccountNo}}" id="bankAccountNo" name="bankAccountNo" placeholder="Cast Type">
                                                </div>
                                            </div>  
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="bankName" value="{{$employee->bankName}}" placeholder="bankName">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Branch Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control"  value="{{$employee->bankIFSCCode}}" id="bankIFSCCode" name="bankIFSCCode" placeholder="Bank IFSC No.">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank IFSC No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control"  value="{{$employee->bankIFSCCode}}" id="bankIFSCCode" name="bankIFSCCode" placeholder="Bank IFSC No.">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Document Copy &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <img src="/admin/employees/{{$employee->}}" height="300px" width="300px">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Aadhaar Card No.&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" maxlength="12" value="{{$employee->AADHARNo}}" id="aadhaarCardNo" name="aadhaarCardNo">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Upload Aadhaar Photo &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="file" class="form-control"  value="" id="aadhaarCardPhoto" name="aadhaarCardPhoto" placeholder="Aadhar Card Photo">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">PAN No&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control"  value="{{$employee->PANNo}}" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Upload PAN CARD Photo &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="file" class="form-control"  value="" id="pancardPhoto" name="pancardPhoto" placeholder="Upload only Photo">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Instagram id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->instagramId}}" name="instagramId" placeholder="Insta id">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Twitter id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->twitterId}}" name="twitterId" placeholder="Twitter id">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Facebook id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" value="{{$employee->facebookId}}" name="facebookId" placeholder="Facebook Id">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="font-weight-bold" style="color:Red;">Present Address</h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Address&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            <input type="text" class="comPresentAddress form-control"  value="{{$employee->presentAddress}}" id="comPresentAddress" name="presentAddress" placeholder="Address">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">State&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            {{Form::select('presentRegionId', $states, $employee->presentRegionId, ['placeholder'=>'Select State','class'=>'presentStateId form-control', 'id'=>'presentStateId'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">City&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            {{Form::select('presentCityId', $cities, $employee->presentCityId, ['placeholder'=>'Select City','class'=>'presentCityId form-control', 'id'=>'presentCityId'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">PIN Code&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            <input type="text" class="presentPINCode form-control"  value="{{$employee->presentPINCode}}" id="presentPINCode" name="presentPINCode" placeholder="PIN Code">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="font-weight-bold" style="color:Red;">Permanent Address&nbsp;&nbsp;
                                                <b style="color:blue;font-size:14px;">Same As Present <input type="checkbox" id="presentToPermanent" name="presentToPermanent" style="background-color:green;"></b> </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Address&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            <input type="text" class="permanentAddress form-control"  value="{{$employee->permanentAddress}}" id="permanentAddress" name="permanentAddress" placeholder="Address">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">State&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            {{Form::select('permanentRegionId', $states, $employee->permanentRegionId, ['placeholder'=>'Select State','class'=>'form-control', 'id'=>'permanentStateId'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">City&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            {{Form::select('permanentCityId', $cities, $employee->permanentCityId, ['placeholder'=>'Select State','class'=>'form-control', 'id'=>'permanentCityId'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">PIN Code&nbsp;&nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                            <input type="text" class="permanentPINNo form-control"  value="{{$employee->permanentPINCode}}" id="permanentPINNo" name="permanentPINCode" placeholder="PIN No">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Upload Present Address Prof Photo &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="file" class="form-control"  value="" id="presentAddressProf" name="presentAddProf" placeholder="Upload only Photo">
                                                </div>
                                            </div>
                                     
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Upload Permanent Address Prof Photo &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="file" class="form-control"  value="" id="permanentAddressProf" name="permanentAddProf" placeholder="Upload only Photo">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h5 style="color:red;">Education Qualification Details</h5>    
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table card-table table-vcenter text-nowrap table-primary mb-0">
                                                    <thead  class="bg-primary text-white">
                                                        <tr >
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Education</th>
                                                            <th class="text-white">Board / Universtity</th>
                                                            <th class="text-white">Year Of passing</th>
                                                            <th class="text-white">Percentage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Std 10</td>
                                                            <td><input type="text" class="form-control" name="board10Th" value="" placeholder="Board /Universtity"></td>
                                                            <td><input type="text" class="form-control" name="yearPass10Th" value="" placeholder="Year Of passing"></td>
                                                            <td><input type="text" class="form-control" name="percent10Th" value="" placeholder="Percentage"></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Std 12</td>
                                                            <td><input type="text" class="form-control" name="board12Th" value="" placeholder="Board /Universtity"></td>
                                                            <td><input type="text" class="form-control" name="yearPass12Th" value="" placeholder="Year Of passing"></td>
                                                            <td><input type="text" class="form-control" name="percent12Th" value="" placeholder="Percentage"></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Graduate</td>
                                                            <td><input type="text" class="form-control" name="boardGrad" value="" placeholder="Board /Universtity"></td>
                                                            <td><input type="text" class="form-control" name="yearPassGrad" value="" placeholder="Year Of passing"></td>
                                                            <td><input type="text" class="form-control" name="percentGrad" value="" placeholder="Percentage"></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Post Graduate</td>
                                                            <td><input type="text" class="form-control" name="boardPostG" value="" placeholder="Board /Universtity"></td>
                                                            <td><input type="text" class="form-control" name="yearPassPostG" value="" placeholder="Year Of passing"></td>
                                                            <td><input type="text" class="form-control" name="percentPostG" value="" placeholder="Percentage"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 class="font-weight-bold" style="color:Red;">Family Members Details</h4>
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
                                                    <input type="text" class="familyContactNo form-control" placeholder="Contact No" maxlength="10">
                                                </div>
                                            </div> 
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="form-label"></label>
                                                    <button type="button" class="btn btn-primary" style="margin-top:32px;"  value="{{$employee->userRoleId}}" id="addFamilyTableTBody">Add Family Member</button>
                                                </div>
                                            </div>  
                                        </div>
                                        <input type="hidden" value="{{count($empFamilyDet)}}" id="familyCt">
                                        <div class="row familyTableRow">
                                            <div class="table-responsive">
                                                <table id="familyTable" class="table familyTable table-bordered card-table table-vcenter border-top text-nowrap mb-0">
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
                                                    </tbody>
                                                </table>
                                            </div>
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
                        
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

