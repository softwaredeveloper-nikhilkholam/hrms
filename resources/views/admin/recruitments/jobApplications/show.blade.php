<?php
    $forInterviewer = Auth::user()->forInterviewer;
    $userType = Auth::user()->userType;
    $user = Auth::user();
    $empId = $user->empId;
    $allRecuritView = $user->allRecuritView;
?>

@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content" id="GFG">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Candidate Application</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">Back To Home</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Recruitement No <span class="text-red" style="font-size:22px;">*</span>:</label>
                                        <input type="text" class="form-control" name="forDate" value="{{$application->id}}" placeholder="" disabled>
                                    </div>                            
                                </div>
                                <div class="col-md-4"><img src="/admin/images/recPhotos/{{$application->profilePhoto}}" height="200" width="150"></div>  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Date<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="forDate" value="{{date('d-M-Y', strtotime($application->forDate))}}" disabled placeholder="" disabled>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Section<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $application->section, ['placeholder'=>'Select Section','class'=>'form-control', 'disabled', 'id'=>'jobSection'])}}
                                    </div>
                                </div>
                              
                                
                                <div class="col-md-4"></div>
                                <div class="col-md-4"></div>
                            </div> 
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Post Applied for<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('designationId', $designations, $application->designationId, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId',''])}}
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    
                                </div>  
                            </div>
                            <hr>
                            <h5 style="color:red;">Personal Details</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">First Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="firstName" value="{{$application->firstName}}" disabled placeholder="First Name" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Middle Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="middleName" value="{{$application->middleName}}" disabled placeholder="Middle Name" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Last Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="lastName" value="{{$application->lastName}}" disabled placeholder="Last Name" >
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Mother's Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="motherName" value="{{$application->motherName}}" disabled placeholder="Mother's Name" >
                                    </div>
                                </div>
                            </div> 
                            <h5 style="color:red;">Name as on Adhar Card</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">First Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="adharFirstName" value="{{$application->adharFirstName}}" disabled placeholder="First Name" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Middle Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="adharMiddleName" value="{{$application->adharMiddleName}}" disabled placeholder="Middle Name" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Last Name<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="adharLastName" value="{{$application->adharLastName}}" disabled placeholder="Last Name" >
                                    </div>
                                </div>  
                            </div>
                           
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Birth Date<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="date" class="form-control" name="DOB" value="{{$application->DOB}}" disabled placeholder="Birth Date" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Gender<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('gender', ['Male'=>'Male','Female'=>'Female'], $application->gender, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Religion<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="religion" value="{{$application->religion}}" disabled placeholder="Religion">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Caste<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="caste" value="{{$application->caste}}" disabled placeholder="Caste">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Category<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="category" value="{{$application->category}}" disabled placeholder="Category">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Marital status<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Divorcee'=>'Divorcee','Seperated'=>'Seperated','Widow'=>'Widow'], $application->maritalStatus, ['placeholder'=>'Select Marital status','class'=>'form-control', 'disabled'])}}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Present Address in detail<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="presentAddress" value="{{$application->presentAddress}}" disabled placeholder="Present Address in detail" >
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Permenant Address in detail<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="permanentAddress" value="{{$application->permanentAddress}}" disabled placeholder="Permanent Address in detail" >
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Mobile No.<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="mobileNo" onkeypress="return isNumberKey(event)" maxlength="10" value="{{$application->mobileNo}}" disabled placeholder="Mobile No." >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">W.A. No.<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="whatsMobileNo" onkeypress="return isNumberKey(event)" maxlength="10" value="{{$application->whatsMobileNo}}" disabled placeholder="W.A. No." >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Email Id<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{$application->email}}" disabled placeholder="Email Id">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 style="color:red;">Available on</h5>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Facebook" name="facebook" value="1" {{($application->facebook == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Facebook">Facebook</label>
                                      </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Instagram" name="instagram" value="1" {{($application->instagram == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Instagram">Instagram</label>
                                      </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="LinkedIn" name="linkedIn" value="1" {{($application->linkedIn == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="LinkedIn">LinkedIn</label>
                                      </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="check1" name="twitter" value="1" {{($application->twitter == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="check1">Twitter</label>
                                      </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="YouTube" name="youTube" value="1" {{($application->youTube == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="YouTube">YouTube</label>
                                      </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Google" name="googlePlus" value="1" {{($application->googlePlus == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Google">Google +</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 style="color:red;">Languages known<span class="text-red" style="font-size:22px;">*</span></h5>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="English" name="english" value="1" {{($application->english == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="English">English</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Hindi" name="hindi" value="1" {{($application->hindi == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Hindi">Hindi</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Marathi" name="marathi" value="1" {{($application->marathi == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Marathi">Marathi</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Other</label>
                                        <input type="text" class="form-control" name="otherLanguage" value="{{$application->otherLanguage}}" placeholder="Other Language" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 style="color:red;">Emergency contact details</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Name of the person<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="emergencePersonName" value="{{$application->emergencePersonName}}" disabled placeholder="Name of the person" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Relation<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="emergenceRelation" value="{{$application->emergenceRelation}}" disabled placeholder="Relation">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Mob<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="emergenceMob" onkeypress="return isNumberKey(event)" maxlength="10" value="{{$application->emergenceMob}}" disabled placeholder="Mob">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 style="color:red;">Advertisement and Reference Source</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Advertisement Source<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('advSource', ['Newspaper Advertisement'=>'Newspaper Advertisement', 'Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Whatsapp'=>'Whatsapp', 'Website'=>'Website', 'Friend'=>'Friend', 'Current Employee'=>'Current Employee', 'Ex-Employee of Aaryans'=>'Ex-Employee of Aaryans', 'Authority Relative'=>'Authority Relative', 'Walk in'=>'Walk in', 'Other'=>'Other'], $application->advSource, ['placeholder'=>'Select Source','class'=>'form-control', 'disabled', 'id'=>'reference'])}}
                                    </div>
                                </div>
                            </div>
                            <h5 style="color:red;" id="">Reference from Aaryans World School (if any)</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="refName" value="{{$application->refName}}"  placeholder="Name" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Contact No.</label>
                                        <input type="text" class="form-control" name="refContactNo" disabled onkeypress="return isNumberKey(event)" maxlength="10" value="{{$application->refContactNo}}"  placeholder="Contact No.">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 style="color:red;">Educational Qualification Details</h5>    
                            <div class="row">
                                <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap table-primary mb-0">
                                        <thead  class="bg-primary text-white">
                                            <tr >
                                                <th class="text-white">Education</th>
                                                <th class="text-white">Degree / Stream / Qualification</th>
                                                <th class="text-white">Board / Universtity</th>
                                                <th class="text-white">Year Of passing</th>
                                                <th class="text-white">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Std. 10th</td>
                                                <td><input type="text" class="form-control" disabled name="degree1" value="{{$application->degree1}}" placeholder="Degree / Stream / Qualification" ></td>
                                                <td><input type="text" class="form-control" disabled name="board1" value="{{$application->board1}}" placeholder="Board /Universtity" ></td>
                                                <td><input type="text" class="form-control" disabled name="passingYear1" value="{{$application->passingYear1}}" placeholder="Year Of passing" ></td>
                                                <td><input type="text" class="form-control" disabled name="percent1" value="{{$application->percent1}}" placeholder="Percentage" ></td>
                                            </tr>
                                            <tr>
                                                <td>Std. 12th</td>
                                                <td><input type="text" class="form-control" disabled name="degree2" value="{{$application->degree2}}"  placeholder="Degree / Stream / Qualification"></td>
                                                <td><input type="text" class="form-control" disabled name="board2" value="{{$application->board2}}"  placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control" disabled name="passingYear2" value="{{$application->passingYear2}}"  placeholder="Year Of passing"></td>
                                                <td><input type="text" class="form-control" disabled name="percent2" value="{{$application->percent2}}"  placeholder="Percentage"></td>
                                            </tr>
                                            <tr>
                                                <td>Graduation</td>
                                                <td><input type="text" class="form-control" disabled name="degree3" value="{{$application->degree3}}"  placeholder="Degree / Stream / Qualification"></td>
                                                <td><input type="text" class="form-control" disabled name="board3" value="{{$application->board3}}" placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control" disabled name="passingYear3" value="{{$application->passingYear3}}" placeholder="Year Of passing"></td>
                                                <td><input type="text" class="form-control"  disabled name="percent3" value="{{$application->percent3}}" placeholder="Percentage"></td>
                                            </tr>
                                            <tr>
                                                <td>Post Graduation</td>
                                                <td><input type="text" class="form-control" disabled name="degree4" value="{{$application->degree4}}" placeholder="Degree / Stream / Qualification"></td>
                                                <td><input type="text" class="form-control" disabled name="board4" value="{{$application->board4}}" placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control" disabled  name="passingYear4" value="{{$application->passingYear4}}" placeholder="Year Of passing"></td>
                                                <td><input type="text" class="form-control" disabled name="percent4" value="{{$application->percent4}}" placeholder="Percentage"></td>
                                            </tr>
                                            <tr class="">
                                                <td>Trainee Degree</td>
                                                <td>{{Form::select('degree5', ['TTC'=>'TTC','NTC'=>'NTC','D.Ed. '=>'D.Ed.','B.Ed.'=>'B.Ed.','M.Ed.'=>'M.Ed.','PhD.'=>'PhD.'], $application->degree5, ['placeholder'=>'Select Degree / Stream / Qualification','class'=>'form-control', 'disabled'])}}</td>
                                                <td><input type="text" class="form-control" disabled name="board5" value="{{$application->board5}}" placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control" disabled name="passingYear5" value="{{$application->passingYear5}}" placeholder="Year Of passing"></td>
                                                <td><input type="text" class="form-control" disabled name="percent5" value="{{$application->percent5}}" placeholder="Percentage"></td>
                                            </tr>
                                            <tr class="">
                                                <td>Methods / Subjects / Topic</td>
                                                <td><input type="text" class="form-control"  disabled name="degree6" value="{{$application->degree6}}" placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control"  disabled name="board6" value="{{$application->board6}}" placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control" disabled name="passingYear6" value="{{$application->passingYear6}}" placeholder="Year Of passing"></td>
                                                <td><input type="text" class="form-control" disabled name="percent6" value="{{$application->percent6}}" placeholder="Percentage"></td>
                                            </tr>
                                            <tr class="">
                                                <td>Any other Special qualification (if any)</td>
                                                <td><input type="text" class="form-control" disabled name="degree7" value="{{$application->degree7}}" placeholder="Methods / Subjects / Topic"></td>
                                                <td><input type="text" class="form-control" disabled name="board7" value="{{$application->board7}}" placeholder="Board /Universtity"></td>
                                                <td><input type="text" class="form-control" disabled name="passingYear7" value="{{$application->passingYear7}}"  placeholder="Year Of passing"></td>
                                                <td><input type="text" class="form-control" disabled name="percent7" value="{{$application->percent7}}"  placeholder="Percentage"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 style="color:red;">Overall Computer Proficiency</h5>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="Beginner" name="overallComputerProficiency" value="1" {{($application->overallComputerProficiency == 1)?'checked':''}}  disabled>
                                                <label class="form-check-label" for="Beginner">Beginner</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="Medium" name="overallComputerProficiency" value="2" {{($application->overallComputerProficiency == 2)?'checked':''}}  disabled>
                                                <label class="form-check-label" for="Medium">Medium</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="Expert" name="overallComputerProficiency" value="3" {{($application->overallComputerProficiency == 3)?'checked':''}}  disabled>
                                                <label class="form-check-label" for="Expert">Expert</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 style="color:red;">Microsoft Office (Word, Excel, PPT)</h5>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="Beginner1" name="microsoftOffice" value="1" {{($application->microsoftOffice == 1)?'checked':''}}  disabled>
                                                <label class="form-check-label" for="Beginner1">Beginner</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="Medium1" name="microsoftOffice" value="2" {{($application->microsoftOffice == 2)?'checked':''}}  disabled>
                                                <label class="form-check-label" for="Medium1">Medium</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="Expert1" name="microsoftOffice" value="3" {{($application->microsoftOffice == 3)?'checked':''}}  disabled>
                                                <label class="form-check-label" for="Expert1">Expert</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Special Education / Certification in Ccomputer (if any)<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="specialEducation" value="{{$application->specialEducation}}" disabled placeholder="Special Education / Certification in Ccomputer (if any)" disabled>
                                    </div>
                                </div>
                            </div>
                             
                            <hr>
                            <h5 style="color:red;">Work Experience Details</h5>   
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="Fresher" name="workExperienceDetails" value="1" {{($application->workExperienceDetails == 1)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Fresher">Fresher<span class="text-red" style="font-size:22px;">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="Experienced" name="workExperienceDetails" value="2" {{($application->workExperienceDetails == 2)?'checked':''}} disabled>
                                        <label class="form-check-label" for="Experienced">Experienced<span class="text-red" style="font-size:22px;">*</span></label>
                                    </div>
                                </div>
                                @if($application->workExperienceDetails == 2)
                                <div class="col-md-2 ">
                                    <div class="form-check">
                                        <label class="form-label">Select Experience<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('experience', ['0to1 year'=>'0to1 year', '1to2 years'=>'1to2 years', '3to5 years '=>'3to5 years ', '6years & +'=>'6years & +'], $application->experience, ['placeholder'=>'Select experience','class'=>'form-control', 'disabled', 'id'=>'', ' disabled'])}}
                                    </div>
                                </div>
                                @endif
                            </div>
                            <hr>  
                            @if($application->workExperienceDetails == 2)
                                <div class="row ">
                                    <div class="col-md-12">
                                    <h5 style="color:red;">Work Experience (till date)(Please mention all the experiences whether it is relevant or not)</h5>    
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0">
                                            <thead  class="bg-warning text-black">
                                                <tr>
                                                    <th class="text-black">Sr.No.</th>
                                                    <th class="text-black">Name of the organizations</th>
                                                    <th class="text-black">Exp in yrs/mnths</th>
                                                    <th class="text-black">From (month and year)</th>
                                                    <th class="text-black">To (month and year)</th>
                                                    <th class="text-black">Post/Responsibility</th>
                                                    <th class="text-black stdSub">Std&Sub</th>
                                                    <th class="text-black">Reason for leaving</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">1st<span class="text-red" style="font-size:22px;">*</span></th>
                                                    <td><input type="text" class="form-control" name="organisation1" value="{{$application->organisation1}}" disabled placeholder="Name of the organistions"></td>
                                                    <td><input type="text" class="form-control" name="exp1" value="{{$application->exp1}}" disabled placeholder="Exp in yrs/mnths"></td>
                                                    <td><input type="text" class="form-control" name="from1" value="{{$application->from1}}" disabled placeholder="From (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="to1" value="{{$application->to1}}" disabled placeholder="To (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="post1" value="{{$application->post1}}" disabled placeholder="Post/Responsibility"></td>
                                                    <td class="stdSub1"><input type="text" class="form-control " name="std1" value="{{$application->std1}}" disabled placeholder="Std&Sub"></td>
                                                    <td><input type="text" class="form-control" name="reason1" value="{{$application->reason1}}" disabled placeholder="Reason for leaving"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2nd</th>
                                                    <td><input type="text" class="form-control" name="organisation2" value="{{$application->organisation2}}"  placeholder="Name of the organistions"></td>
                                                    <td><input type="text" class="form-control" name="exp2" value="{{$application->exp2}}" disabled  placeholder="Exp in yrs/mnths"></td>
                                                    <td><input type="text" class="form-control" name="from2" value="{{$application->from2}}" disabled  placeholder="From (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="to2" value="{{$application->to2}}" disabled  placeholder="To (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="post2" value="{{$application->post2}}" disabled  placeholder="Post/Responsibility"></td>
                                                    <td class="stdSub2"><input type="text" class="form-control " name="std2" value="{{$application->std2}}" disabled  placeholder="Std&Sub"></td>
                                                    <td><input type="text" class="form-control" name="reason2" value="{{$application->reason2}}" disabled  placeholder="Reason for leaving"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3rd</th>
                                                    <td><input type="text" class="form-control" name="organisation3" value="{{$application->organisation3}}" disabled  placeholder="Name of the organistions"></td>
                                                    <td><input type="text" class="form-control" name="exp3" value="{{$application->exp3}}" disabled  placeholder="Exp in yrs/mnths"></td>
                                                    <td><input type="text" class="form-control" name="from3" value="{{$application->from3}}"  disabled placeholder="From (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="to3" value="{{$application->to3}}"  disabled placeholder="To (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="post3" value="{{$application->post3}}"  disabled placeholder="Post/Responsibility"></td>
                                                    <td class="stdSub3"><input type="text" class="form-control " name="std3" value="{{$application->std3}}"  disabled  placeholder="Std&Sub"></td>
                                                    <td><input type="text" class="form-control" name="reason3" value="{{$application->reason3}}" disabled  placeholder="Reason for leaving"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">4th</th>
                                                    <td><input type="text" class="form-control" name="organisation4" value="{{$application->organisation4}}" disabled  placeholder="Name of the organistions"></td>
                                                    <td><input type="text" class="form-control" name="exp4" value="{{$application->exp4}}"  disabled placeholder="Exp in yrs/mnths"></td>
                                                    <td><input type="text" class="form-control" name="from4" value="{{$application->from4}}"  disabled placeholder="From (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="to4" value="{{$application->to4}}"  disabled placeholder="To (month and year)"></td>
                                                    <td><input type="text" class="form-control" name="post4" value="{{$application->post4}}"  disabled placeholder="Post/Responsibility"></td>
                                                    <td class="stdSub4"><input type="text" class="form-control " name="std4" value="{{$application->std4}}"  disabled placeholder="Std&Sub"></td>
                                                    <td><input type="text" class="form-control" name="reason4" value="{{$application->reason4}}"  disabled placeholder="Reason for leaving"></td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>  
                            
                                <h5 style="color:red;" class=" mt-3">Reference details of last two Organizations</h5>
                                <div class="row ">
                                    <hr>
                                    <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0">
                                            <thead  class="bg-primary text-white">
                                                <tr >
                                                    <th class="text-white">Sr.No.</th>
                                                    <th class="text-white">Name of the organization</th>
                                                    <th class="text-white">Name of reporting authority</th>
                                                    <th class="text-white">Post of reporting authority</th>
                                                    <th class="text-white">Contact No.</th>
                                                    <th class="text-white">Email id</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td><input type="text" class="form-control" name="refOrganization1" value="{{$application->refOrganization1}}" disabled placeholder="Name of the organization"></td>
                                                    <td><input type="text" class="form-control" name="refrepoAuth1" value="{{$application->refrepoAuth1}}" disabled placeholder="Name of reporting authority"></td>
                                                    <td><input type="text" class="form-control" name="refRepoAuthPost1" value="{{$application->refRepoAuthPost1}}" disabled placeholder="Post of reporting authority"></td>
                                                    <td><input type="text" class="form-control" name="refContctNo1" value="{{$application->refContctNo1}}" disabled placeholder="Contact No." onkeypress="return isNumberKey(event)" maxlength="10"></td>
                                                    <td><input type="email" class="form-control" name="refEmail1" value="{{$application->refEmail1}}" disabled placeholder="Email id"></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td><input type="text" class="form-control" name="refOrganization2" value="{{$application->refOrganization2}}" disabled  placeholder="Name of the organization"></td>
                                                    <td><input type="text" class="form-control" name="refRepoAuth2" value="{{$application->refRepoAuth2}}" disabled  placeholder="Name of reporting authority"></td>
                                                    <td><input type="text" class="form-control" name="refRepoAuthPost2" value="{{$application->refRepoAuthPost2}}" disabled  placeholder="Post of reporting authority"></td>
                                                    <td><input type="text" class="form-control" name="refContctNo2" value="{{$application->refContctNo2}}" disabled onkeypress="return isNumberKey(event)" maxlength="10" placeholder="Contact No."></td>
                                                    <td><input type="email" class="form-control" name="refEmail2" value="{{$application->refEmail2}}"  disabled placeholder="Email id"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                    <hr> 
                                </div>
                            @endif
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last drawn in-hand salary<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="lastSalary" onkeypress="return isNumberKey(event)" disabled value="{{$application->lastSalary}}" disabled placeholder="Last drawn in-hand salary">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Expected Salary<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="expectedSalary" onkeypress="return isNumberKey(event)" disabled value="{{$application->expectedSalary}}" placeholder="Expected Salary">
                                    </div>
                                </div>
                            </div>  

                            <hr>
                            <h5 style="color:red;">About you</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Your Strenghths</label>
                                        <input type="text" class="form-control" name="strenghths" value="{{$application->strenghths}}" disabled placeholder="Your Strenghths">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Hobbies<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="hobbies" value="{{$application->hobbies}}" disabled placeholder="Hobbies">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Extra-curricular activities and achievements (if any)</label>
                                        <input type="text" class="form-control" name="extraCurricular" disabled value="{{$application->extraCurricular}}" placeholder="Extra-curricular activities and achievements (if any)">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 style="color:red;">Medical History if any</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Previous<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="medicalPrevious" value="{{$application->medicalPrevious}}" disabled placeholder="Previous">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Current<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="medicalCurrent" value="{{$application->medicalCurrent}}" disabled placeholder="Current">
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Blood Group</label>
                                        <input type="text" class="form-control" name="bloodGp"  disabled value="{{$application->bloodGp}}" placeholder="Blood Group">
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Previously applied here<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('prevAppliedFor', ['Yes'=>'Yes', 'No'=>'No'], $application->prevAppliedFor, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled', 'id'=>'appliedFor'])}}
                                    </div>
                                </div>
                                <div class="col-md-3 showAppliedHere1">
                                    <div class="form-group">
                                        <label class="form-label">For Month<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="month" class="form-control" name="appliedForMonth" value="{{$application->appliedForMonth}}" disabled placeholder="For Month">
                                    </div>
                                </div>
                                <div class="col-md-3 showAppliedHere2">
                                    <div class="form-group">
                                        <label class="form-label">For which Post<span class="text-red" style="font-size:22px;">*</span></label>
                                        <input type="text" class="form-control" name="appliedForPost" value="{{$application->appliedForPost}}" disabled placeholder="For which Post">
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Ex-Employee of Aaryans World School<span class="text-red" style="font-size:22px;">*</span></label>
                                        {{Form::select('exEmployee', ['Yes'=>'Yes', 'No'=>'No'], $application->exEmployee, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled', 'id'=>'reference'])}}
                                    </div>
                                </div>
                            </div>  
                            @if($application->resume != '')  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Resume<span class="text-red"></span>:</label>
                                            <a href="/admin/candidatesDocuments/{{$application->resume}}" target="_blank"><b style="color:Red;">View Resume, please Click Here....</b></a>
                                        </div>
                                    </div>
                                </div>  
                            @endif                           
                            <hr>   
                            <h5 style="color:red;">Declaration</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="Declaration" name="option1" checked disabled style="color:red;">
                                        <label class="form-check-label" for="Declaration">I hereby declare that the above information is true & correct.</label>
                                    </div>
                                </div>
                            </div>  
                            
                            <hr>
                            @if($userType == '51' || $allRecuritView == 1)
                                <div class="row">
                                    @if($interview1)
                                        <div class="col-md-12">
                                            <table class="table">
                                                <tr>
                                                    <th class="text-center btn-success">
                                                        <h5 style="color:purple;">1<sup>st</sup> Round Details</h5> 
                                                        <h5 style="color:purple;">Taken By {{$interview1->name}}</h5> 
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>   
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>Eligibility</b></td>
                                                                        <td>
                                                                            @if($interview1->rating1 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating1 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating1 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating1 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating1 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview1->rating1}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Smartness</b></td>
                                                                        <td>
                                                                            @if($interview1->rating2 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating2 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating2 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating2 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating2 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview1->rating2}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Knowledge</b></td>
                                                                        <td>
                                                                            @if($interview1->rating3 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating3 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating3 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating3 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating3 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview1->rating3}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Appearance</b></td>
                                                                        <td>
                                                                            @if($interview1->rating4 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating4 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating4 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating4 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating4 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview1->rating4}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>English Fluency</b></td>
                                                                        <td>
                                                                            @if($interview1->rating5 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating5 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating5 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating5 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating5 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview1->rating5}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Confidence</b></td>
                                                                        <td>
                                                                            @if($interview1->rating6 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating6 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating6 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating6 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview1->rating6 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview1->rating6}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                        <textarea class="form-control" name="" cols="3" rows="7" disabled>{{$interview1->remarks}}</textarea>
                                                                    </div> 
                                                                </div> 
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                        <input type="text" value="{{$interview1->expectedSalary}}" name="expectedSalary" class="form-control" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                        {{Form::select('postOffered', $designations, $interview1->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                        <input type="text" value="{{$interview1->offeredSalary}}" name="offeredSalary" class="form-control" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                        {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview1->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    @endif                                        
                                </div>
                                <div class="row">
                                    @if($interview2)
                                        <div class="col-md-12">
                                            <table class="table">
                                                <tr>
                                                    <th class="text-center btn-success">
                                                        <h5 style="color:purple;">Demo Round Details</h5> 
                                                        <h5 style="color:purple;">Taken By {{$interview2->name}}</h5> 
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>   
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Date of Demo<span class="text-red">*</span>:</label>
                                                                    <input type="date" value="{{$interview2->demoDate}}" name="demoDate" class="form-control" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Branch<span class="text-red" style="font-size:22px;">*</span></label>
                                                                    {{Form::select('branchId', $branches, $interview2->branchId, ['placeholder'=>'Select Branch','class'=>'form-control branchId', 'id'=>'branchId','disabled'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Subject<span class="text-red">*</span>:</label>
                                                                    <input type="text" value="{{$interview2->subject}}" placeholder="Subject" name="subject" class="form-control" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Standard<span class="text-red">*</span>:</label>
                                                                    <input type="text" value="{{$interview2->standard}}" placeholder="Standard" name="standard" class="form-control" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Topic<span class="text-red">*</span>:</label>
                                                                    <input type="text" value="{{$interview2->topic}}" placeholder="Topic" name="topic" class="form-control" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Video Link<span class="text-red"></span>:</label>
                                                                    <input type="text" value="{{$interview2->videoLink}}" placeholder="Video Link" name="vodeLink" class="form-control" disabled>
                                                                </div> 
                                                            </div>
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Name of the observer<span class="text-red"></span>:</label>
                                                                    <input type="text" value="{{$interview2->nameOfObserver}}" placeholder="Name of the observer" name="nameOfObserver" class="form-control" disabled>
                                                                </div> 
                                                            </div> 
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Remark of the observer<span class="text-red"></span>:</label>
                                                                    <textarea class="form-control" name="remarks" cols="3" rows="3" disabled>{{$interview2->remarks}}</textarea>
                                                                </div> 
                                                            </div> 
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Recomandation<span class="text-red" style="font-size:22px;">*</span></label>
                                                                    {{Form::select('recomandation', ['Excellent'=>'Excellent','Very Good'=>'Very Good','Good'=>'Good','Average'=>'Average','Poor'=>'Poor'], $interview2->recomandation, ['placeholder'=>'Select Recomandation','class'=>'form-control branchId', 'id'=>'branchId','disabled'])}}
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Upload The File<span class="text-red">*</span>:</label>
                                                                    <b><a href="/admin/candidatesDocuments/{{$interview2->uploadFile}}">click Here..</a></b>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                    {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview2->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    @endif                                        
                                </div>
                                
                                <div class="row">
                                    @if($interview3)
                                        <div class="col-md-12">
                                            <table class="table">
                                                <tr>
                                                    <th class="text-center btn-success">
                                                        <h5 style="color:purple;">3<sup>rd</sup> Round Details</h5> 
                                                        <h5 style="color:purple;">Taken By {{$interview3->name}}</h5> 
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>   
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12">
                                                                <table>
                                                                    <tr>
                                                                        <td><b>Eligibility</b></td>
                                                                        <td>
                                                                            @if($interview3->rating1 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating1 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating1 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating1 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating1 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview3->rating1}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Smartness</b></td>
                                                                        <td>
                                                                            @if($interview3->rating2 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating2 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating2 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating2 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating2 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview3->rating2}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Knowledge</b></td>
                                                                        <td>
                                                                            @if($interview3->rating3 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating3 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating3 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating3 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating3 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview3->rating3}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Appearance</b></td>
                                                                        <td>
                                                                            @if($interview3->rating4 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating4 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating4 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating4 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating4 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview3->rating4}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>English Fluency</b></td>
                                                                        <td>
                                                                            @if($interview3->rating5 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating5 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating5 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating5 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating5 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else 
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview3->rating5}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Confidence</b></td>
                                                                        <td>
                                                                            @if($interview3->rating6 >= 1)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating6 >= 2)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating6 >= 3)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else    
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating6 >= 4)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif

                                                                            @if($interview3->rating6 >= 5)
                                                                                <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @else   
                                                                                <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                            @endif
                                                                            <input type="hidden" value="{{$interview3->rating6}}" name="" id="">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                        <textarea class="form-control" name="" cols="3" rows="7" disabled>{{$interview3->remarks}}</textarea>
                                                                    </div> 
                                                                </div> 
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                        <input type="text" value="{{$interview3->expectedSalary}}" name="expectedSalary" class="form-control" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                        {{Form::select('postOffered', $designations, $interview3->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                        <input type="text" value="{{$interview3->offeredSalary}}" name="offeredSalary" class="form-control" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                        {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview3->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @if($interview4)
                                        <div class="col-md-12">
                                            <table class="table">
                                                <tr>
                                                    <th class="text-center btn-success">
                                                        <h5 style="color:purple;">4<sup>th</sup> Round Details</h5> 
                                                        <h5 style="color:purple;">Taken By {{$interview4->name}}</h5> 
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>   
                                                       
                                                            <div class="row">
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Selected Branch</label>
                                                                    {{Form::select('branchId', $branches, $interview4->branchId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'newBranchId', 'disabled'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Post Offered</label>
                                                                    {{Form::select('postOffered', $designations, $interview4->postOffered, ['placeholder'=>'Select Post Offered','class'=>'form-control', 'id'=>'newBranchId', 'disabled'])}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Section Selected for:</label>
                                                                    <input type="text" class="form-control" name="sectionSelectedFor" placeholder="Section Selected for" value="{{$interview4->sectionSelectedFor}}" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Subject Selected for:</label>
                                                                    <input type="text" class="form-control" name="subjectSelectedFor" placeholder="Subject Selected for" value="{{$interview4->subjectSelectedFor}}" disabled>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Date of Joining:</label>
                                                                    <input type="date" class="form-control" name="dateOfJoining" placeholder="Date of Joining" value="{{$interview4->dateOfJoining}}" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Reporting Authority</label>
                                                                    <select class="form-control" name="reportingAuthId" disabled>
                                                                        <option value="">Select Reporting Authority</option>
                                                                        @foreach($empReportings as $option)
                                                                            <option value="{{$option->id}}" <?php echo ($option->id == $interview4->reportingAuthId)?'selected':''; ?>>{{$option->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Mentor / Buddy:</label>
                                                                    <input type="text" class="form-control" name="mentorBuddy" placeholder="Mentor / Buddy" value="{{$interview4->mentorBuddy}}" disabled>
                                                                </div>
                                                            </div>
                                                                <div class="col-md-4 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Timing<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" name="timing" placeholder="Time" value="{{$interview4->timing}}" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Final Salary<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" name="salary" placeholder="Salary" value="{{$interview4->salary}}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 col-lg-8">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Hike in Salary - Commitments if any<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" name="hikeComment" placeholder="Hike in Salary - Commitments if any" value="{{$interview4->hikeComment}}" disabled>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                        {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview4->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                        <textarea class="form-control" name="remarks" cols="3" rows="3" disabled>{{$interview4->remarks}}</textarea>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                            <!-- <div class="row">
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Signature<span class="text-red">*</span>:</label>
                                                                        {{Form::select('signs', $signFiles, null, ['placeholder'=>'Select Signature','class'=>'signs form-control', 'id'=>'signs', 'disabled'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-3">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Username<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{$interview4->username}}" disabled>
                                                                        <b style="color:Red;" id="showMessage"></b>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <a href="/jobApplications/applicationPrint/{{$application->id}}" class="btn btn-danger">Print <i class="fa fa-print"></i></a>
                                        @if($interview4)
                                            @if($interview4->appStatus == 'Selected')
                                                <a href="/employees/addCandidateToEmployee/{{$application->id}}" class="btn btn-success">Add Employee<i class="fa fa-user"></i></a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>

                            @else
                                @if($forInterviewer != 0)
                                    {!! Form::open(['action' => 'admin\recruitments\JobApplicationsController@updateStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                        @if($application->round == 1 && $interview1)
                                            <hr>
                                            <div class="col-md-12 col-lg-12">
                                                <table width="100%">
                                                    <tr>
                                                        <td><h5 style="color:red;">1<sup>St</sup> Round Details</h5>   
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12">
                                                        <table width="100%">
                                                            <tr>
                                                                <td><b>Eligibility</b></td>
                                                                <td>
                                                                    @if($interview1->rating1 >= 1)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s1" aria-hidden = "true" id = "s1"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s1" aria-hidden = "true" id = "s1"></i>
                                                                    @endif

                                                                    @if($interview1->rating1 >= 2)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s2" aria-hidden = "true" id = "s2"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s2" aria-hidden = "true" id = "s2"></i>
                                                                    @endif

                                                                    @if($interview1->rating1 >= 3)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s3" aria-hidden = "true" id = "s3"></i>
                                                                    @else    
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s3" aria-hidden = "true" id = "s3"></i>
                                                                    @endif

                                                                    @if($interview1->rating1 >= 4)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s4" aria-hidden = "true" id = "s4"></i>
                                                                    @else   
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s4" aria-hidden = "true" id = "s4"></i>
                                                                    @endif

                                                                    @if($interview1->rating1 >= 5)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s5" aria-hidden = "true" id = "s5"></i>
                                                                    @else 
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s5" aria-hidden = "true" id = "s5"></i>
                                                                    @endif
                                                                    <input type="hidden" value="{{$interview1->rating1}}" name="rating1" id="rating1">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Smartness</b></td>
                                                                <td>
                                                                    @if($interview1->rating2 >= 1)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s11" aria-hidden = "true" id = "s11"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s11" aria-hidden = "true" id = "s11"></i>
                                                                    @endif

                                                                    @if($interview1->rating2 >= 2)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s22" aria-hidden = "true" id = "s22"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s22" aria-hidden = "true" id = "s22"></i>
                                                                    @endif

                                                                    @if($interview1->rating2 >= 3)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  s33" aria-hidden = "true" id = "s33"></i>
                                                                    @else    
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s33" aria-hidden = "true" id = "s33"></i>
                                                                    @endif

                                                                    @if($interview1->rating2 >= 4)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s44" aria-hidden = "true" id = "s44"></i>
                                                                    @else   
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s44" aria-hidden = "true" id = "s44"></i>
                                                                    @endif

                                                                    @if($interview1->rating2 >= 5)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s55" aria-hidden = "true" id = "s55"></i>
                                                                    @else 
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s55" aria-hidden = "true" id = "s55"></i>
                                                                    @endif
                                                                    <input type="hidden" value="{{$interview1->rating2}}" name="rating22" id="rating22">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Knowledge</b></td>
                                                                <td>
                                                                    @if($interview1->rating3 >= 1)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s111" aria-hidden = "true" id = "s111"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s111" aria-hidden = "true" id = "s111"></i>
                                                                    @endif

                                                                    @if($interview1->rating3 >= 2)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s222" aria-hidden = "true" id = "s222"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s222" aria-hidden = "true" id = "s222"></i>
                                                                    @endif

                                                                    @if($interview1->rating3 >= 3)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s333" aria-hidden = "true" id = "s333"></i>
                                                                    @else    
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s333" aria-hidden = "true" id = "s333"></i>
                                                                    @endif

                                                                    @if($interview1->rating3 >= 4)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s444" aria-hidden = "true" id = "s444"></i>
                                                                    @else   
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s444" aria-hidden = "true" id = "s444"></i>
                                                                    @endif

                                                                    @if($interview1->rating3 >= 5)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s555" aria-hidden = "true" id = "s555"></i>
                                                                    @else 
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s555" aria-hidden = "true" id = "s555"></i>
                                                                    @endif
                                                                    <input type="hidden" value="{{$interview1->rating3}}" name="rating333" id="rating333">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Appearance</b></td>
                                                                <td>
                                                                    @if($interview1->rating4 >= 1)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s1111" aria-hidden = "true" id = "s1111"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s1111" aria-hidden = "true" id = "s1111"></i>
                                                                    @endif

                                                                    @if($interview1->rating4 >= 2)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s2222" aria-hidden = "true" id = "s2222"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s2222" aria-hidden = "true" id = "s2222"></i>
                                                                    @endif

                                                                    @if($interview1->rating4 >= 3)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s3333" aria-hidden = "true" id = "s3333"></i>
                                                                    @else    
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s3333" aria-hidden = "true" id = "s3333"></i>
                                                                    @endif

                                                                    @if($interview1->rating4 >= 4)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s4444" aria-hidden = "true" id = "s4444"></i>
                                                                    @else   
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s4444" aria-hidden = "true" id = "s4444"></i>
                                                                    @endif

                                                                    @if($interview1->rating4 >= 5)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s5555" aria-hidden = "true" id = "s5555"></i>
                                                                    @else 
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s5555" aria-hidden = "true" id = "s5555"></i>
                                                                    @endif
                                                                    <input type="hidden" value="{{$interview1->rating4}}" name="rating4444" id="rating4444">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>English Fluency</b></td>
                                                                <td>
                                                                    @if($interview1->rating5 >= 1)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s11111" aria-hidden = "true" id = "s11111"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s11111" aria-hidden = "true" id = "s11111"></i>
                                                                    @endif

                                                                    @if($interview1->rating5 >= 2)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s22222" aria-hidden = "true" id = "s22222"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s22222" aria-hidden = "true" id = "s22222"></i>
                                                                    @endif

                                                                    @if($interview1->rating5 >= 3)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s33333" aria-hidden = "true" id = "s33333"></i>
                                                                    @else    
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s33333" aria-hidden = "true" id = "s33333"></i>
                                                                    @endif

                                                                    @if($interview1->rating5 >= 4)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s44444" aria-hidden = "true" id = "s44444"></i>
                                                                    @else   
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s44444" aria-hidden = "true" id = "s44444"></i>
                                                                    @endif

                                                                    @if($interview1->rating5 >= 5)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s55555" aria-hidden = "true" id = "s55555"></i>
                                                                    @else 
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s55555" aria-hidden = "true" id = "s55555"></i>
                                                                    @endif
                                                                    <input type="hidden" value="{{$interview1->rating5}}" name="rating55555" id="rating55555">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Confidence</b></td>
                                                                <td>
                                                                    @if($interview1->rating6 >= 1)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s111111" aria-hidden = "true" id = "s111111"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s111111" aria-hidden = "true" id = "s111111"></i>
                                                                    @endif

                                                                    @if($interview1->rating6 >= 2)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s222222" aria-hidden = "true" id = "s222222"></i>
                                                                    @else
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s222222" aria-hidden = "true" id = "s222222"></i>
                                                                    @endif

                                                                    @if($interview1->rating6 >= 3)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s333333" aria-hidden = "true" id = "s333333"></i>
                                                                    @else    
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s333333" aria-hidden = "true" id = "s333333"></i>
                                                                    @endif

                                                                    @if($interview1->rating6 >= 4)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s444444" aria-hidden = "true" id = "s444444"></i>
                                                                    @else   
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s444444" aria-hidden = "true" id = "s444444"></i>
                                                                    @endif

                                                                    @if($interview1->rating6 >= 5)
                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s555555" aria-hidden = "true" id = "s555555"></i>
                                                                    @else 
                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star s555555" aria-hidden = "true" id = "s555555"></i>
                                                                    @endif
                                                                    <input type="hidden" value="{{$interview1->rating6}}" name="rating666666" id="rating666666">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                    <textarea class="form-control" name="remarks" cols="3" rows="3">{{$interview1->remarks}}</textarea>
                                                                </div> 
                                                            </div> 
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                    <input type="text" value="{{$interview1->expectedSalary}}" name="expectedSalary" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                    {{Form::select('postOffered', $designations, $application->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId',''])}}
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                    <input type="text" value="{{$interview1->offeredSalary}}" name="offeredSalary" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group"> 
                                                                    <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                    {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview1->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'required'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-4">
                                                        <input type="hidden" name="id" value="{{$application->id}}">
                                                        <input type="hidden" name="roundAssign" value="{{$application->round}}">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($application->round == 2 && $interview2)
                                            <div class="row">
                                                @if($interview1)
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th class="text-center btn-success">
                                                                    <h5 style="color:purple;">1<sup>st</sup> Round Details</h5> 
                                                                    <h5 style="color:purple;">Taken By {{$interview1->name}}</h5> 
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>   
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <table>
                                                                                <tr>
                                                                                    <td><b>Eligibility</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating1 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating1}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Smartness</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating2 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating2}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Knowledge</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating3 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating3}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Appearance</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating4 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating4}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>English Fluency</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating5 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating5}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Confidence</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating6 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating6}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                                    <textarea class="form-control" name="" cols="3" rows="7" disabled>{{$interview1->remarks}}</textarea>
                                                                                </div> 
                                                                            </div> 
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview1->expectedSalary}}" name="expectedSalary" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                    {{Form::select('postOffered', $designations, $interview1->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview1->offeredSalary}}" name="offeredSalary" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                                    {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview1->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endif                                        
                                            </div>
                                            <h5 style="color:red;"><center>Demo Round Details <h4 style="color:blue;">[ Interviewer : {{$interview2->name}}]</h4></center></h5> <br>  
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group"> 
                                                        <label class="form-label">Date of Demo<span class="text-red">*</span>:</label>
                                                        <input type="date" value="{{$interview2->demoDate}}" name="demoDate" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Branch<span class="text-red" style="font-size:22px;">*</span></label>
                                                        {{Form::select('branchId', $branches, $interview2->branchId, ['placeholder'=>'Select Branch','class'=>'form-control branchId', 'id'=>'branchId',''])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group"> 
                                                        <label class="form-label">Subject<span class="text-red">*</span>:</label>
                                                        <input type="text" value="{{$interview2->subject}}" placeholder="Subject" name="subject" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group"> 
                                                        <label class="form-label">Standard<span class="text-red">*</span>:</label>
                                                        <input type="text" value="{{$interview2->standard}}" placeholder="Standard" name="standard" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group"> 
                                                        <label class="form-label">Topic<span class="text-red">*</span>:</label>
                                                        <input type="text" value="{{$interview2->topic}}" placeholder="Topic" name="topic" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Name of the observer<span class="text-red"></span>:</label>
                                                        <input type="text" value="{{$interview2->nameOfObserver}}" placeholder="Name of the observer" name="nameOfObserver" class="form-control">
                                                    </div> 
                                                </div> 
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Video Link<span class="text-red"></span>:</label>
                                                        <input type="text" value="{{$interview2->videoLink}}" placeholder="Video Link" name="videoLink" class="form-control" required>
                                                    </div> 
                                                </div> 
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Remark of the observer<span class="text-red"></span>:</label>
                                                        <textarea class="form-control" name="remarks" cols="3" rows="3">{{$interview2->remarks}}</textarea>
                                                    </div> 
                                                </div> 
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Recomandation<span class="text-red" style="font-size:22px;">*</span></label>
                                                        {{Form::select('recomandation', ['Excellent'=>'Excellent','Very Good'=>'Very Good','Good'=>'Good','Average'=>'Average','Poor'=>'Poor'], $interview2->recomandation, ['placeholder'=>'Select Recomandation','class'=>'form-control branchId', 'id'=>'branchId',''])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-4">
                                                    <div class="form-group"> 
                                                        <label class="form-label">Upload The File<span class="text-red">*</span>:</label>
                                                        <input type="file" value="{{$interview2->uploadFile}}" name="uploadFile" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Video Link<span class="text-red"></span>:</label>
                                                        <input type="text" value="{{$interview2->vedioLink}}" placeholder="Video Link" name="videoLink" class="form-control">
                                                    </div> 
                                                </div> 
                                                <div class="col-md-12 col-lg-4">
                                                    <div class="form-group"> 
                                                        <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                        {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview2->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'required'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-4">
                                                    <input type="hidden" name="id" value="{{$application->id}}">
                                                    <input type="hidden" name="roundAssign" value="{{$application->round}}">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        @elseif($application->round == 3 && $interview3)
                                            <div class="row">
                                                @if($interview1)
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th class="text-center btn-success">
                                                                    <h5 style="color:purple;">1<sup>st</sup> Round Details</h5> 
                                                                    <h5 style="color:purple;">Taken By {{$interview1->name}}</h5> 
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>   
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <table>
                                                                                <tr>
                                                                                    <td><b>Eligibility</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating1 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating1 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating1}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Smartness</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating2 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating2 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating2}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Knowledge</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating3 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating3 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating3}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Appearance</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating4 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating4 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating4}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>English Fluency</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating5 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating5 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating5}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Confidence</b></td>
                                                                                    <td>
                                                                                        @if($interview1->rating6 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
            
                                                                                        @if($interview1->rating6 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview1->rating6}}" name="" id="">
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                                    <textarea class="form-control" name="" cols="3" rows="7" disabled>{{$interview1->remarks}}</textarea>
                                                                                </div> 
                                                                            </div> 
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview1->expectedSalary}}" name="expectedSalary" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                    {{Form::select('postOffered', $designations, $interview1->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview1->offeredSalary}}" name="offeredSalary" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                                    {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview1->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endif                                        
                                            </div>
                                            @if($application->section == 'Teaching')
                                                <div class="row">
                                                    @if($interview2)
                                                        <div class="col-md-12">
                                                            <table class="table">
                                                                <tr>
                                                                    <th class="text-center btn-success">
                                                                        <h5 style="color:purple;">Demo Round Details</h5> 
                                                                        <h5 style="color:purple;">Taken By {{$interview2->name}}</h5> 
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th>   
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Date of Demo<span class="text-red">*</span>:</label>
                                                                                    <input type="date" value="{{$interview2->demoDate}}" name="demoDate" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Branch<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                    {{Form::select('branchId', $branches, $interview2->branchId, ['placeholder'=>'Select Branch','class'=>'form-control branchId', 'id'=>'branchId','disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Subject<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview2->subject}}" placeholder="Subject" name="subject" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Standard<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview2->standard}}" placeholder="Standard" name="standard" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Topic<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview2->topic}}" placeholder="Topic" name="topic" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Name of the observer<span class="text-red"></span>:</label>
                                                                                    <input type="text" value="{{$interview2->nameOfObserver}}" placeholder="Name of the observer" name="nameOfObserver" class="form-control" disabled>
                                                                                </div> 
                                                                            </div> 
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Remark of the observer<span class="text-red"></span>:</label>
                                                                                    <textarea class="form-control" name="remarks" cols="3" rows="3" disabled>{{$interview2->remarks}}</textarea>
                                                                                </div> 
                                                                            </div> 
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Recomandation<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                    {{Form::select('recomandation', ['Excellent'=>'Excellent','Very Good'=>'Very Good','Good'=>'Good','Average'=>'Average','Poor'=>'Poor'], $interview2->recomandation, ['placeholder'=>'Select Recomandation','class'=>'form-control branchId', 'id'=>'branchId','disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-4">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Upload The File<span class="text-red">*</span>:</label>
                                                                                    <b><a href="/admin/candidatesDocuments/{{$interview2->uploadFile}}">click Here..</a></b>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-4">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                                    {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview2->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    @endif                                        
                                                </div>
                                            @endif
                                            <div class="row">
                                                @if($interview3)
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th class="text-center btn-success">
                                                                    <h5 style="color:purple;">3<sup>rd</sup> Round Details</h5> 
                                                                    <h5 style="color:purple;">Taken By {{$interview3->name}}</h5> 
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>   
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <table width="100%">
                                                                                <tr>
                                                                                    <td><b>Eligibility</b></td>
                                                                                    <td>
                                                                                        @if($interview3->rating1 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s1" aria-hidden = "true" id = "s1"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s1" aria-hidden = "true" id = "s1"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating1 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s2" aria-hidden = "true" id = "s2"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s2" aria-hidden = "true" id = "s2"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating1 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s3" aria-hidden = "true" id = "s3"></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s3" aria-hidden = "true" id = "s3"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating1 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s4" aria-hidden = "true" id = "s4"></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s4" aria-hidden = "true" id = "s4"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating1 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s5" aria-hidden = "true" id = "s5"></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s5" aria-hidden = "true" id = "s5"></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview3->rating1}}" name="rating1" id="rating1">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Smartness</b></td>
                                                                                    <td>
                                                                                        @if($interview3->rating2 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s11" aria-hidden = "true" id = "s11"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s11" aria-hidden = "true" id = "s11"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating2 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s22" aria-hidden = "true" id = "s22"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s22" aria-hidden = "true" id = "s22"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating2 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  s33" aria-hidden = "true" id = "s33"></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s33" aria-hidden = "true" id = "s33"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating2 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s44" aria-hidden = "true" id = "s44"></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s44" aria-hidden = "true" id = "s44"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating2 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s55" aria-hidden = "true" id = "s55"></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s55" aria-hidden = "true" id = "s55"></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview3->rating2}}" name="rating22" id="rating22">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Knowledge</b></td>
                                                                                    <td>
                                                                                        @if($interview3->rating3 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s111" aria-hidden = "true" id = "s111"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s111" aria-hidden = "true" id = "s111"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating3 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s222" aria-hidden = "true" id = "s222"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s222" aria-hidden = "true" id = "s222"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating3 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s333" aria-hidden = "true" id = "s333"></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s333" aria-hidden = "true" id = "s333"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating3 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s444" aria-hidden = "true" id = "s444"></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s444" aria-hidden = "true" id = "s444"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating3 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s555" aria-hidden = "true" id = "s555"></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s555" aria-hidden = "true" id = "s555"></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview3->rating3}}" name="rating333" id="rating333">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Appearance</b></td>
                                                                                    <td>
                                                                                        @if($interview3->rating4 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s1111" aria-hidden = "true" id = "s1111"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s1111" aria-hidden = "true" id = "s1111"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating4 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s2222" aria-hidden = "true" id = "s2222"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s2222" aria-hidden = "true" id = "s2222"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating4 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s3333" aria-hidden = "true" id = "s3333"></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s3333" aria-hidden = "true" id = "s3333"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating4 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s4444" aria-hidden = "true" id = "s4444"></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s4444" aria-hidden = "true" id = "s4444"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating4 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s5555" aria-hidden = "true" id = "s5555"></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s5555" aria-hidden = "true" id = "s5555"></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview3->rating4}}" name="rating4444" id="rating4444">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>English Fluency</b></td>
                                                                                    <td>
                                                                                        @if($interview3->rating5 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s11111" aria-hidden = "true" id = "s11111"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s11111" aria-hidden = "true" id = "s11111"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating5 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s22222" aria-hidden = "true" id = "s22222"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s22222" aria-hidden = "true" id = "s22222"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating5 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s33333" aria-hidden = "true" id = "s33333"></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s33333" aria-hidden = "true" id = "s33333"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating5 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s44444" aria-hidden = "true" id = "s44444"></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s44444" aria-hidden = "true" id = "s44444"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating5 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s55555" aria-hidden = "true" id = "s55555"></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s55555" aria-hidden = "true" id = "s55555"></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview3->rating5}}" name="rating55555" id="rating55555">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Confidence</b></td>
                                                                                    <td>
                                                                                        @if($interview3->rating6 >= 1)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s111111" aria-hidden = "true" id = "s111111"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s111111" aria-hidden = "true" id = "s111111"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating6 >= 2)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s222222" aria-hidden = "true" id = "s222222"></i>
                                                                                        @else
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s222222" aria-hidden = "true" id = "s222222"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating6 >= 3)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s333333" aria-hidden = "true" id = "s333333"></i>
                                                                                        @else    
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s333333" aria-hidden = "true" id = "s333333"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating6 >= 4)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s444444" aria-hidden = "true" id = "s444444"></i>
                                                                                        @else   
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s444444" aria-hidden = "true" id = "s444444"></i>
                                                                                        @endif
                    
                                                                                        @if($interview3->rating6 >= 5)
                                                                                            <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star s555555" aria-hidden = "true" id = "s555555"></i>
                                                                                        @else 
                                                                                            <i style="font-size : 25px;align-content: center;" class = "fa fa-star s555555" aria-hidden = "true" id = "s555555"></i>
                                                                                        @endif
                                                                                        <input type="hidden" value="{{$interview3->rating6}}" name="rating666666" id="rating666666">
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                                    <textarea class="form-control" name="remarks" cols="3" rows="7" required>{{$interview3->remarks}}</textarea>
                                                                                </div> 
                                                                            </div> 
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview3->expectedSalary}}" name="expectedSalary" class="form-control" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                    {{Form::select('postOffered', $designations, $interview3->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','required'])}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                                    <input type="text" value="{{$interview3->offeredSalary}}" name="offeredSalary" class="form-control" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <div class="form-group"> 
                                                                                    <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                                    {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview3->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'required'])}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endif                                        
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-3 mt-5">
                                                <input type="hidden" name="id" value="{{$application->id}}">
                                                    <input type="hidden" name="roundAssign" value="{{$application->round}}">
                                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>
                                                </div>
                                            </div>
                                        @elseif($application->round == 4 && $interview4)
                                        <div class="row">
                                            @if($interview1)
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tr>
                                                            <th class="text-center btn-success">
                                                                <h5 style="color:purple;">1<sup>st</sup> Round Details</h5> 
                                                                <h5 style="color:purple;">Taken By {{$interview1->name}}</h5> 
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>   
                                                                <div class="row">
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <table>
                                                                            <tr>
                                                                                <td><b>Eligibility</b></td>
                                                                                <td>
                                                                                    @if($interview1->rating1 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating1 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating1 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating1 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating1 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview1->rating1}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Smartness</b></td>
                                                                                <td>
                                                                                    @if($interview1->rating2 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating2 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating2 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating2 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating2 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview1->rating2}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Knowledge</b></td>
                                                                                <td>
                                                                                    @if($interview1->rating3 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating3 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating3 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating3 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating3 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview1->rating3}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Appearance</b></td>
                                                                                <td>
                                                                                    @if($interview1->rating4 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating4 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating4 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating4 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating4 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview1->rating4}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>English Fluency</b></td>
                                                                                <td>
                                                                                    @if($interview1->rating5 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating5 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating5 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating5 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating5 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview1->rating5}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Confidence</b></td>
                                                                                <td>
                                                                                    @if($interview1->rating6 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating6 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating6 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating6 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview1->rating6 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview1->rating6}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                                <textarea class="form-control" name="" cols="3" rows="7" disabled>{{$interview1->remarks}}</textarea>
                                                                            </div> 
                                                                        </div> 
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group"> 
                                                                                <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                                <input type="text" value="{{$interview1->expectedSalary}}" name="expectedSalary" class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                {{Form::select('postOffered', $designations, $interview1->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group"> 
                                                                                <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                                <input type="text" value="{{$interview1->offeredSalary}}" name="offeredSalary" class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group"> 
                                                                                <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                                {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview1->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif                                        
                                        </div>
                                        <div class="row">
                                            @if($interview2)
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tr>
                                                            <th class="text-center btn-success">
                                                                <h5 style="color:purple;">Demo Round Details</h5> 
                                                                <h5 style="color:purple;">Taken By {{$interview2->name}}</h5> 
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>   
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group"> 
                                                                            <label class="form-label">Date of Demo<span class="text-red">*</span>:</label>
                                                                            <input type="date" value="{{$interview2->demoDate}}" name="demoDate" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Branch<span class="text-red" style="font-size:22px;">*</span></label>
                                                                            {{Form::select('branchId', $branches, $interview2->branchId, ['placeholder'=>'Select Branch','class'=>'form-control branchId', 'id'=>'branchId','disabled'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group"> 
                                                                            <label class="form-label">Subject<span class="text-red">*</span>:</label>
                                                                            <input type="text" value="{{$interview2->subject}}" placeholder="Subject" name="subject" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group"> 
                                                                            <label class="form-label">Standard<span class="text-red">*</span>:</label>
                                                                            <input type="text" value="{{$interview2->standard}}" placeholder="Standard" name="standard" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group"> 
                                                                            <label class="form-label">Topic<span class="text-red">*</span>:</label>
                                                                            <input type="text" value="{{$interview2->topic}}" placeholder="Topic" name="topic" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Video Link<span class="text-red"></span>:</label>
                                                                            <input type="text" value="{{$interview2->videoLink}}" placeholder="Video Link" name="vodeLink" class="form-control" disabled>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Name of the observer<span class="text-red"></span>:</label>
                                                                            <input type="text" value="{{$interview2->nameOfObserver}}" placeholder="Name of the observer" name="nameOfObserver" class="form-control" disabled>
                                                                        </div> 
                                                                    </div> 
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Remark of the observer<span class="text-red"></span>:</label>
                                                                            <textarea class="form-control" name="remarks" cols="3" rows="3" disabled>{{$interview2->remarks}}</textarea>
                                                                        </div> 
                                                                    </div> 
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Recomandation<span class="text-red" style="font-size:22px;">*</span></label>
                                                                            {{Form::select('recomandation', ['Excellent'=>'Excellent','Very Good'=>'Very Good','Good'=>'Good','Average'=>'Average','Poor'=>'Poor'], $interview2->recomandation, ['placeholder'=>'Select Recomandation','class'=>'form-control branchId', 'id'=>'branchId','disabled'])}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-lg-4">
                                                                        <div class="form-group"> 
                                                                            <label class="form-label">Upload The File<span class="text-red">*</span>:</label>
                                                                            <b><a href="/admin/candidatesDocuments/{{$interview2->uploadFile}}">click Here..</a></b>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-lg-4">
                                                                        <div class="form-group"> 
                                                                            <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                            {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview2->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif                                        
                                        </div>
                                        
                                        <div class="row">
                                            @if($interview3)
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tr>
                                                            <th class="text-center btn-success">
                                                                <h5 style="color:purple;">3<sup>rd</sup> Round Details</h5> 
                                                                <h5 style="color:purple;">Taken By {{$interview3->name}}</h5> 
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>   
                                                                <div class="row">
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <table>
                                                                            <tr>
                                                                                <td><b>Eligibility</b></td>
                                                                                <td>
                                                                                    @if($interview3->rating1 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating1 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating1 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating1 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating1 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview3->rating1}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Smartness</b></td>
                                                                                <td>
                                                                                    @if($interview3->rating2 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating2 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating2 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star  " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating2 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating2 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star" aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview3->rating2}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Knowledge</b></td>
                                                                                <td>
                                                                                    @if($interview3->rating3 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating3 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating3 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating3 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating3 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview3->rating3}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Appearance</b></td>
                                                                                <td>
                                                                                    @if($interview3->rating4 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating4 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating4 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating4 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating4 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview3->rating4}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>English Fluency</b></td>
                                                                                <td>
                                                                                    @if($interview3->rating5 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating5 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating5 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating5 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating5 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else 
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview3->rating5}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Confidence</b></td>
                                                                                <td>
                                                                                    @if($interview3->rating6 >= 1)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating6 >= 2)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating6 >= 3)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else    
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating6 >= 4)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif

                                                                                    @if($interview3->rating6 >= 5)
                                                                                        <i style="font-size : 25px;align-content: center;color:red;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @else   
                                                                                        <i style="font-size : 25px;align-content: center;" class = "fa fa-star " aria-hidden = "true" id = ""></i>
                                                                                    @endif
                                                                                    <input type="hidden" value="{{$interview3->rating6}}" name="" id="">
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-12 col-lg-12">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                                <textarea class="form-control" name="" cols="3" rows="7" disabled>{{$interview3->remarks}}</textarea>
                                                                            </div> 
                                                                        </div> 
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group"> 
                                                                                <label class="form-label">Expected Salary<span class="text-red">*</span>:</label>
                                                                                <input type="text" value="{{$interview3->expectedSalary}}" name="expectedSalary" class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Post Offered<span class="text-red" style="font-size:22px;">*</span></label>
                                                                                {{Form::select('postOffered', $designations, $interview3->postOffered, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group"> 
                                                                                <label class="form-label">Offered Salary<span class="text-red">*</span>:</label>
                                                                                <input type="text" value="{{$interview3->offeredSalary}}" name="offeredSalary" class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group"> 
                                                                                <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                                {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview3->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                           
                                            @if($interview4)
                                                <hr>
                                                <h5 style="color:red;">4<sup>th</sup>Round Details <h4 style="color:blue;">[ Interviewer : {{$interview4->name}}]</h4></h5>   
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <div class="row mt-5">
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Selected Branch</label>
                                                                        {{Form::select('branchId', $branches, $interview4->branchId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'newBranchId'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Post Offered</label>
                                                                        {{Form::select('postOffered', $designations, $interview4->postOffered, ['placeholder'=>'Select Post Offered','class'=>'form-control', 'id'=>'newBranchId'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Section Selected for:</label>
                                                                        <input type="text" class="form-control" name="sectionSelectedFor" placeholder="Section Selected for" value="{{$interview4->sectionSelectedFor}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Subject Selected for:</label>
                                                                        <input type="text" class="form-control" name="subjectSelectedFor" placeholder="Subject Selected for" value="{{$interview4->subjectSelectedFor}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Date of Joining:</label>
                                                                        <input type="date" class="form-control" name="dateOfJoining" placeholder="Date of Joining" value="{{$interview4->dateOfJoining}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Reporting Authority</label>
                                                                        <select class="form-control" name="reportingAuthId">
                                                                            <option value="">Select Reporting Authority</option>
                                                                            @foreach($empReportings as $option)
                                                                                <option value="{{$option->id}}" <?php echo ($option->id == $interview4->reportingAuthId)?'selected':'' ?>>{{$option->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Mentor / Buddy:</label>
                                                                        <input type="text" class="form-control" name="mentorBuddy" placeholder="Mentor / Buddy" value="{{$interview4->mentorBuddy}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Timing<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" name="timing" placeholder="Time" value="{{$interview4->timing}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Final Salary<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" name="finalSalary" placeholder="Salary" value="{{$interview4->salary}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-8">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Hike in Salary - Commitments if any<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" name="hikeComment" placeholder="Hike in Salary - Commitments if any" value="{{$interview4->hikeComment}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                                                        {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $interview4->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'required'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 col-lg-8">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                                                        <textarea class="form-control" name="remarks" cols="3" rows="3">{{$interview4->remarks}}</textarea>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                            <!-- <div class="row">
                                                                <div class="col-md-12 col-lg-4">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Signature<span class="text-red">*</span>:</label>
                                                                        {{Form::select('signs', $signFiles, null, ['placeholder'=>'Select Signature','class'=>'signs form-control', 'id'=>'signs', 'disabled'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-3">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Username<span class="text-red">*</span>:</label>
                                                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{$interview4->username}}">
                                                                        <b style="color:Red;" id="showMessage"></b>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-3">
                                                                    <div class="form-group"> 
                                                                        <label class="form-label">Password<span class="text-red">*</span>:</label>
                                                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-2">
                                                                    <div class="form-group">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input class="custom-control-input" type="checkbox" name="showPassword"  onclick="myFunction()">
                                                                            <span class="custom-control-label">Show</span>
                                                                        </label>	
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <div class="row">
                                                                <div class="col-md-12 col-lg-3 mt-5">
                                                                <input type="hidden" name="id" value="{{$application->id}}">
                                                                    <input type="hidden" name="roundAssign" value="{{$application->round}}">
                                                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    {!! Form::close() !!}   
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
<script type="text/javascript">  
    function printDiv() {
            var divContents = document.getElementById("GFG").innerHTML;
            var a = window.open('', '', 'height=500, width=500');
            a.document.write('<html>');
            a.document.write('<body > <h1>Div contents are <br>');
            a.document.write(divContents);
            a.document.write('</body></html>');
            a.document.close();
            a.print();
        } 
</script>  