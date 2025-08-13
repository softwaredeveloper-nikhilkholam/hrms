@extends('layouts.master3')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">EMPLOYEE INFORMATION FORM</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmployeesController@storeCIF', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName" value="{{(isset($application)?($application->firstName.' '.$application->middleName.' '.$application->lastName):'')}}" id="empName" placeholder="Employee Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Profile Photo &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="file" class="form-control" name="profPhoto" id="profPhoto" placeholder="Profile Photo">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="phoneNo" value="{{(isset($application)?$application->mobileNo:'')}}" id="phoneNo" placeholder="Phone No" required>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">WhatsApp No &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" id="whatsappNo" name="whatsappNo" placeholder="WhatsApp No">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Date of Birth&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" name="DOB"  id="empDOB" class="form-control" value="{{(isset($application)?$application->DOB:'')}}" placeholder="select dates"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Gender&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], NULL, ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'required'])}}
                                        </div>
                                    </div> 
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Cast  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="cast" placeholder="Cast">
                                        </div>
                                    </div>
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Type  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="type" placeholder="Cast Type">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            @if(isset($branches))
                                                <select name="branchId" id="branchId" class="branchId form-control">
                                                    <option value="">Select Option</option>
                                                        @foreach($branches as $branch)
                                                            <option value="{{$branch->id}}">{{$branch->branchName}}</option>
                                                        @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Section&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('sectionId', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], (isset($application)?$application->section:''), ['placeholder'=>'Select Section','class'=>'sectionId form-control', 'id'=>'sectionId', 'required'])}}
                                        </div>
                                    </div>
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Job Joining Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" name="empJobJoingDate"  id="empJobJoingDate" class="form-control" placeholder="select jobJoiningDate"/>
                                        </div>
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group required">
                                            <label class="form-label">Office Time &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="time" class="form-control" value="{{date('09:00')}}" name="jobStartTime" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="time" class="form-control"  value="{{date('05:00')}}" name="jobEndTime" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Marital Status&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], NULL, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Salary Scale &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="salaryScale" placeholder="Salary Scale">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Email ID &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="email" class="email form-control" id="personalEmail" name="personalEmail" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="teachingSubject">
                                        <div class="form-group">
                                            <label class="form-label">Teaching Subject &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="teachingSubject" placeholder="Teaching Subject">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Reference &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="reference" placeholder="Reference">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Bank IFSC No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" id="bankIFSCNo" name="bankIFSCNo" placeholder="Bank IFSC No.">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Bank Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="bankName" placeholder="bankName">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Bank A/c No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" id="bankAccountNo" name="bankAccountNo" placeholder="Bank A/c No. ">
                                        </div>
                                    </div>                                       
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Qualification&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Aadhaar Card No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" maxlength="12" value="" id="aadhaarCardNo" name="aadhaarCardNo" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">PAN No&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Instagram id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="instagramId" placeholder="Insta id">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Twitter id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="twitterId" placeholder="Twitter id">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Facebook id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="facebookId" placeholder="Facebook Id">
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
                                                    <label class="form-label">Address&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="comPresentAddress form-control" id="comPresentAddress" name="presentAddress" placeholder="Address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">State&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('presentStateId', $states, NULL, ['placeholder'=>'Select State','class'=>'presentStateId form-control', 'id'=>'presentStateId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">City&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('presentCityId', [], NULL, ['placeholder'=>'Select City','class'=>'presentCityId form-control', 'id'=>'presentCityId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">PIN Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="presentPINCode form-control" id="presentPINCode" name="comPresentPINCode" placeholder="PIN Code" required>
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
                                                    <label class="form-label">Address&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="permanentAddress form-control" id="permanentAddress" name="comPermanentAddress" placeholder="Address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">State&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('permanentStateId', $states, NULL, ['placeholder'=>'Select State','class'=>'form-control', 'id'=>'permanentStateId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">City&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('permanentCityId', [], NULL, ['placeholder'=>'Select State','class'=>'form-control', 'id'=>'permanentCityId', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">PIN Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="permanentPINNo form-control" id="permanentPINNo" name="comPermanentPINNo" placeholder="PIN No" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="font-weight-bold" style="color:Red;">Family Members Details</h4>
                                <div class="row">
                                    <div class="col-md-2">
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
                                            <button type="button" class="btn btn-primary" style="margin-top:32px;" id="addFamilyTableTBody">Add Family Member</button>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row">
                                    <input type="hidden" value="0" id="familyCt">
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
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Experience / Fresher&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('workDet', ['1'=>'Fresher', '2'=>'Experience'], NULL, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'workDet', 'required'])}}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details</h4>
                                <div class="row experienceDetRow">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Name &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experDesignation" placeholder="Designation" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experLastSalary" placeholder="Last Salary" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Duration &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experDuration" placeholder="Duration" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experJobDesc" placeholder="First Name" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experReasonLeaving" placeholder="Reason for Leaving" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            <input type="text" class="form-control" name="experCompanyCont" placeholder="Company Contact No." >
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:10px;">
                                    <div class="col-md-5"></div>
                                    <div class="col-md-3">
                                        <input type="hidden" value="" id="reportingIdType" name="reportingIdType">
                                        <button type="Submit" class="empAdd btn btn-success btn-lg">Save</button>
                                        <a href="/employees" class="btn btn-danger btn-lg">Cancel</a>
                                    </div>
                                    <div class="col-md-4 mb-2"></div>
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
