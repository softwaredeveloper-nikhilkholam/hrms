@extends('layouts.master3')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <script>
        @if(session('status') === 'success')
            alert('Information saved and forwarded to HR!');
        @elseif(session('status') === 'error')
            alert('Failed to save data.');
        @endif
    </script>
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employees</h4>
                </div>
            </div>
            <div class="row">	
                <div class="col-xl-12 col-md-12 col-lg-12">
                    {!! Form::open(['action' => 'admin\employees\EmployeesController@storeDataForAddEmployee', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <ul class="nav panel-tabs">
                                    <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Personal</a></li>
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
                                                    <label class="form-label">First Name &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="firstName" value="" id="empName" placeholder="Employee First Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Middle Name &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="middleName" value="" id="empName" placeholder="Employee Middle Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Last Name &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="lastName" value="" id="empName" placeholder="Employee Last Name"  required>
                                                </div>
                                            </div>
                                          
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Select Gender &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], (isset($application)?$application->gender:NULL), ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'required'])}}
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Religion &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="region" placeholder="Religion"  value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Caste &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="cast" placeholder="Caste"  value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Sub Caste &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="type" placeholder="Sub Caste"  value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="date" name="DOB"  id="empDOB" class="form-control" value="" placeholder="select dates" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Marital Status &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed','Separated'=>'Separated','Divorced'=>'Divorced'], null, ['placeholder'=>'Select Marital Status','class'=>'maritalStatus form-control', 'id'=>'maritalStatus', 'required'])}}
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Phone No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" name="phoneNo" onkeypress="return isNumberKey(event)" maxlength="10"  value="" id="phoneNo" placeholder="Phone No"  required>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">WhatsApp No&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="whatsappNo" onkeypress="return isNumberKey(event)" maxlength="10"  value="" name="whatsappNo" placeholder="WhatsApp No">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Email ID&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="email" class="email form-control" value="" id="email" name="email" placeholder="Email">
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
                                                            <textarea class="comPresentAddress form-control" id="comPresentAddress" name="presentAddress"  required></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Permanent Address &nbsp;[ Same As Present <input type="checkbox" id="presentToPermanent" name="presentToPermanent" style="background-color:green;"> ] <span class="text-red" style="font-size:22px;">*</span>:</label>
                                                            <textarea class="permanentAddress form-control" id="permanentAddress" name="permanentAddress" ></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Qualification &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Qualification" value=""  required>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Experience / Fresher &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('workingStatus', ['1'=>'Fresher', '2'=>'Experience'],null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'workDet', 'required'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row experienceDetRow">
                                            <div class="col-md-12">
                                                @for($i=1; $i<=5;$i++)
                                                    <h4 class="mb-5 mt-3 font-weight-bold experienceDet">Previous Experience Details {{$i}}</h4>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Name Of the Organisation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experName{{$i}}" value="" placeholder="Name {{$i}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experDesignation{{$i}}" value="" placeholder="Designation {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Duration From &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="date" class="form-control" name="experFromDuration{{$i}}" value="" placeholder="Duration {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Duration To &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="date" class="form-control" name="experToDuration{{$i}}" value="" placeholder="Duration {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="experLastSalary{{$i}}" value="" placeholder="Last Salary {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experJobDesc{{$i}}" value="" placeholder="First Name {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experReasonLeaving{{$i}}" value="" placeholder="Reason for Leaving {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reporting Auth. Name &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experReportingAuth{{$i}}" value="" placeholder="Reason for Leaving {{$i}}" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reporting Auth. Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experReportingDesignation{{$i}}" value="" placeholder="Reason for Leaving {{$i}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                                <input type="text" class="form-control" name="experCompanyCont{{$i}}" onkeypress="return isNumberKey(event)" value="" placeholder="Company Contact No. {{$i}}" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 style="color:red;">Bank and other Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Aadhaar Card No. &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" maxlength="12" value="" id="aadhaarCardNo"  value="{{((isset($application))?$application->AADHARNo:'')}}" name="aadhaarCardNo"  required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">PAN No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" id="PANNo" style="text-transform:uppercase" name="PANNo" placeholder="PAN No"   value="{{((isset($application))?$application->PANNo:'')}}"  required>
                                                </div>
                                            </div>                                           
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Name&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="bankName" placeholder="bankName"  value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Branch&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankranch" name="bankBranch" placeholder="Bank Branch"  value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c Name.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankAccountName" name="bankAccountName" placeholder="Bank Account Name"   value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Bank A/c No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankAccountNo" name="bankAccountNo" onkeypress="return isNumberKey(event)" placeholder="Bank Account No"   value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">IFSC Code&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" id="bankIFSCCode" name="bankIFSCCode" placeholder="Bank IFSC Code"   value="">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 style="color:red;">Other Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Reference&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('reference', ['Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Whatsapp'=>'Whatsapp', 'Website'=>'Website', 'Friend to friend'=>'Friend to friend', 'Authority Relative'=>'Authority Relative', 'AWS School'=>'AWS School', 'Newspaper'=>'Newspaper', 'Other'=>'Other'], null, ['placeholder'=>'Select Source','class'=>'form-control', 'id'=>'reference', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Instagram id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="instagramId" placeholder="Instagram id" value="{{((isset($application))?$application->instagramId:'')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Facebook id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="facebookId" placeholder="Facebook Id" value="{{((isset($application))?$application->facebookId:'')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Twitter id&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" name="twitterId" placeholder="Twitter id" value="{{((isset($application))?$application->twitterId:'')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h4 class="font-weight-bold" style="color:Red;">Emergency Details</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name 1 &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="emergencyName1" value="{{((isset($application))?$application->emergencePersonName:'')}}"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relation &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class=" form-control" placeholder="Relation" name="emergencyRelation1" value="{{((isset($application))?$application->emergenceRelation:'')}}"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Place &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class=" form-control" placeholder="Place" name="emergencyPlace1" value="" required>
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contact No &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="text" class="form-control" placeholder="Contact No" onkeypress="return isNumberKey(event)" value="{{((isset($application))?$application->emergenceMob:'')}}" name="emergencyContactNo1" required>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Name 2&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" placeholder="Name" name="emergencyName2">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class=" form-control" placeholder="Relation" name="emergencyRelation2">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Place&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class=" form-control" placeholder="Place" name="emergencyPlace2">
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Contact No&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                    <input type="text" class="form-control" placeholder="Contact No" onkeypress="return isNumberKey(event)" name="emergencyContactNo2">
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-3 text-center">
                                                <button type="Submit" class="empAdd btn btn-success btn-lg">Save</button>
                                                <a href="/employees" class="btn btn-danger btn-lg">Cancel</a>
                                            </div>
                                            <div class="col-md-4 mb-2"></div>
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

