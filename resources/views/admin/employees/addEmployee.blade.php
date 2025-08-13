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
            </div>
            <div class="row">	
                @if($searchAadharCardNo == '')
                    <div class="col-xl-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header  border-0">
                                <h4 class="card-title" style="color:red;">Search AADHAR Card No for Adding New Employee</h4>
                            </div>
                            <div class="card-body">
                                {!! Form::open(['action' => 'admin\employees\EmployeesController@create', 'method' => 'GET', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">AADHAR Card No&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                <input type="text" class="form-control" id="searchAadharCardNo" minlength="12" maxlength="12" name="searchAadharCardNo" placeholder="Aadhar Card No" required>
                                            </div>
                                        </div>       
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="submit" id="btnSearchPANNO" class="btn btn-primary" style="margin-top:38px;">Search.....</button>                                            
                                            </div>
                                        </div>                                
                                    </div>   
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @else	
                    <div class="col-xl-12 col-md-12 col-lg-12">
                        {!! Form::open(['action' => 'admin\employees\EmployeesController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                            <div class="tab-menu-heading hremp-tabs p-0 ">
                                <div class="tabs-menu1">
                                    <ul class="nav panel-tabs">
                                        <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Personal</a></li>
                                        <li><a href="#tab4" data-toggle="tab">History</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">
                                        <div class="card-body">
                                            <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details (<b id="lastEmpCode" style="color:blue;"></b>)</h4>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Employee Role in HRMS&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('userRoleId', $userRoles, NULL, ['placeholder'=>'Select Role in HRMS ','class'=>'form-control', 'id'=>'userRoleId', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Venture Type&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('firmType', ['1'=>'AWS', '2'=>'ADF', '3'=>'YB', '4'=>'SNAYRAA', '5'=>'AFS'], null , ['placeholder'=>'Select Firm Type ','class'=>'form-control', 'id'=>'firmType', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Organisation &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('organisation', ['Ellora'=>'Ellora', 'Tejasha'=>'Tejasha'], null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">First Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="firstName" value="{{(isset($application)?($application->firstName):'')}}" id="empName" placeholder="Employee First Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Middle Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="middleName" value="{{(isset($application)?($application->middleName):'')}}" id="empName" placeholder="Employee Middle Name">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Last Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="lastName" value="{{(isset($application)?($application->lastName):'')}}" id="empName" placeholder="Employee Last Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Profile Photo &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="file" class="form-control" name="profPhoto" id="profPhoto" placeholder="Profile Photo">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="phoneNo" value="{{(isset($application)?$application->mobileNo:'')}}" id="phoneNo" placeholder="Phone No" required>
                                                    </div>
                                                </div>
                                          
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">WhatsApp No &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" id="whatsappNo" value="{{(isset($application)?$application->whatsappNo:'')}}" name="whatsappNo" placeholder="WhatsApp No">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Date of Birth&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" name="DOB"  id="empDOB" class="form-control" value="{{(isset($application)?$application->DOB:'')}}" placeholder="select dates"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Gender&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], (isset($application)?$application->gender:NULL), ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'required'])}}
                                                    </div>
                                                </div> 
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Transport Allowance&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('transAllowed', ['1'=>'Yes', '0'=>'No'], null, ['placeholder'=>'Select Transport','class'=>'form-control', 'id'=>'transport', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Cast  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="cast" placeholder="Cast"  value="{{(isset($application)?$application->cast:'')}}">
                                                    </div>
                                                </div>
                                           
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Type  &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="type" placeholder="Cast Type"  value="{{(isset($application)?$application->type:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        @if(isset($branches))
                                                            <select name="branchId" id="branchId" class="branchId form-control" required>
                                                                <option value="">Select Option</option>
                                                                    @foreach($branches as $branch)
                                                                        <option value="{{$branch->id}}" <?php (isset($application)?(($application->branchId == $branch->id)?'selected':''):'') ?> >{{$branch->branchName}}</option>
                                                                    @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Section&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('sectionId', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], (isset($application)?$application->section:''), ['placeholder'=>'Select Section','class'=>'sectionId form-control', 'id'=>'sectionId', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Department&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('departmentId', $departments, (isset($application)?$application->departmentId:''), ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'departmentId', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('designationId', [], (isset($application)?$application->designationId:''), ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Reporting Authority&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        @if(isset($empReportings))
                                                            <select name="reportingId" id="" class=" form-control">
                                                                <option value="">Select Option</option>
                                                                    @foreach($empReportings as $emp)
                                                                        <option value="{{$emp->id}}">{{$emp->name}}</option>
                                                                    @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Select Buddy&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('buddyName', $buddyNames, null, ['placeholder'=>'Select Buddy Name','class'=>'buddyName form-control', 'id'=>'buddyName', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Contract Start Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" class="form-control" name="contractStartDate" placeholder="contract Start Date">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Contract End Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" class="form-control" name="contractEndDate" placeholder="contract End Date">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Joining Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="date" name="empJobJoingDate"  id="empJobJoingDate" value="{{((isset($application))?$application->jobJoingDate:'')}}" class="form-control" placeholder="select jobJoiningDate"/>
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
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Shift&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('shift', ['Day Shift'=>'Day Shift','Night Shift'=>'Night Shift'],'Day Shift', ['placeholder'=>'Select Shift','class'=>'form-control', 'id'=>'shift', 'required'])}}
                                                    </div>
                                                </div>
                                                

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Marital Status&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], ((isset($application))?$application->maritalStatus:''), ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Salary Scale &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="salaryScale" value="{{((isset($application))?$application->salaryScale:'')}}" placeholder="Salary Scale">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Email ID &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="email" class="email form-control" value="{{((isset($application))?$application->email:'')}}" id="personalEmail" name="personalEmail" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="teachingSubject">
                                                    <div class="form-group">
                                                        <label class="form-label">Teaching Subject &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="teachingSubject" value="{{((isset($application))?$application->teachingSubject:'')}}" placeholder="Teaching Subject">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Reference &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="reference" placeholder="Reference" value="{{((isset($application))?$application->reference:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank IFSC No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" id="bankIFSCNo" name="bankIFSCNo" placeholder="Bank IFSC No."  value="{{((isset($application))?$application->bankIFSCCode:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank Name &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="bankName" placeholder="bankName"  value="{{((isset($application))?$application->bankName:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Bank A/c No. &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" id="bankAccountNo" name="bankAccountNo" placeholder="Bank Account No"   value="{{((isset($application))?$application->bankAccountNo:'')}}">
                                                    </div>
                                                </div>                                       
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Qualification&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" value="{{((isset($application))?$application->qualification:'')}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Aadhaar Card No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" maxlength="12" value="{{$searchAadharCardNo}}" id="aadhaarCardNo"  value="{{((isset($application))?$application->AADHARNo:'')}}" name="aadhaarCardNo" required>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">PAN No&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No"   value="{{((isset($application))?$application->PANNo:'')}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">PF Number :</label>
                                                        <input type="text" class="form-control" id="pfNumber" style="text-transform:uppercase" name="pfNumber" placeholder="PF Number"   value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">UID Number :</label>
                                                        <input type="text" class="form-control" id="uIdNumber" style="text-transform:uppercase" name="uIdNumber" placeholder="UID Number"   value="">
                                                    </div>
                                                </div>                                               
                                               
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Instagram id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="instagramId" placeholder="Insta id" value="{{((isset($application))?$application->instagramId:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Twitter id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="twitterId" placeholder="Twitter id" value="{{((isset($application))?$application->twitterId:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Facebook id &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        <input type="text" class="form-control" name="facebookId" placeholder="Facebook Id" value="{{((isset($application))?$application->facebookId:'')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">I-Card assigned&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('iCardAss', ['Yes'=>'Yes', 'No'=>'No'], null , ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'iCardAss', 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Visiting cards assigned&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('visCardAss', ['Yes'=>'Yes', 'No'=>'No'], null , ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'visCardAss', 'required'])}}
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
                                                                <textarea class="comPresentAddress form-control" id="comPresentAddress" name="presentAddress" required>{{((isset($application))?$application->presentAddress:'')}}</textarea>
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
                                                                <textarea class="permanentAddress form-control" id="permanentAddress" name="comPermanentAddress" required>{{((isset($application))?$application->permanentAddress:'')}}</textarea>
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
                                                <input type="hidden" value="1" id="familyCt">
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
                                                            @if(isset($empFamilyDet))
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
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Experience / Fresher&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                        {{Form::select('workDet', ['1'=>'Fresher', '2'=>'Experience'], ((isset($application))?$application->workDet:null), ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'workDet', 'required'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details</h4>
                                            <div class="row experienceDetRow">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Name &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experName" value="{{((isset($application))?$application->experName:'')}}" placeholder="Name">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experDesignation" value="{{((isset($application))?$application->experDesignation:'')}}" placeholder="Designation" >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experLastSalary" value="{{((isset($application))?$application->experLastSalary:'')}}" placeholder="Last Salary" >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Duration &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experDuration" value="{{((isset($application))?$application->experDuration:'')}}" placeholder="Duration" >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experJobDesc" value="{{((isset($application))?$application->experJobDesc:'')}}" placeholder="First Name" >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experReasonLeaving" value="{{((isset($application))?$application->experReasonLeaving:'')}}" placeholder="Reason for Leaving" >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                        <input type="text" class="form-control" name="experCompanyCont" value="{{((isset($application))?$application->experCompanyCont:'')}}" placeholder="Company Contact No." >
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
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
                                                                <td>Addhar Card</td>
                                                                <td><input type="file" multiple name="uploadAddharCard[]" class="form-control"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>PAN Card</td>
                                                                <td><input type="file" multiple name="uploadPanCard[]" class="form-control"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Bank Details</td>
                                                                <td><input type="file" multiple name="uploadBankDetails[]" class="form-control"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td colspan="2"><b style="color:red;">Testimonials / Marksheet</b>
                                                                    <table width="100%" style="border:0px;"> 
                                                                        <tr style="border:0px;">
                                                                            <td style="border:0px;">10th :<input type="file"  name="uploadTestimonials10th" class="form-control"></td>
                                                                            <td style="border:0px;">12th :<input type="file"  name="uploadTestimonials12th" class="form-control"></td>
                                                                            <td style="border:0px;">Graduation :<input type="file"  name="uploadTestimonialsGrad" class="form-control"></td>
                                                                        </tr>
                                                                        <tr style="border:0px;">
                                                                            <td style="border:0px;">Post Graduation :<input type="file"  name="uploadTestimonialsPostGrad" class="form-control"></td>
                                                                            <td style="border:0px;">Other :<input type="file"  name="uploadTestimonialsOther" class="form-control"></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td>Driving License</td>
                                                                <td><input type="file" multiple name="uploadDrivingLicense[]" class="form-control"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>RTO Batch</td>
                                                                <td><input type="file" multiple name="uploadRtoBatch[]" class="form-control"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>7</td>
                                                                <td>Electricity Bill</td>
                                                                <td><input type="file" multiple name="uploadElectricityBill[]" class="form-control"></td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>8</td>
                                                                <td>Policy Document</td>
                                                                <td><input type="file" multiple name="uploadEmployeeContract[]" class="form-control"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>9</td>
                                                                <td>Other Documents</td>
                                                                <td><input type="file" multiple name="uploadOtherDocuments[]" class="form-control"></td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab4">
                                        <div class="card-body">
                                            <h4>Not Found Any History</h4>
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
                            </div>
                           
                        {!! Form::close() !!}     
                    </div>
                @endif
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

