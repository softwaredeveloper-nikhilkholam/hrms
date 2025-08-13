@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Verification Details</h4>
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
                            @if($application)
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
                                            {{Form::select('designationId', $designations, $application->designationId, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','disabled'])}}
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                            {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $application->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'disabled'])}}
                                        </div>
                                    </div>
                                </div> 
                            @else
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$employee->name}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="phoneNo"  value="{{$employee->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
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
                                            <label class="form-label">Date of Birth&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" name="DOB"  id="empDOB" value="{{$employee->DOB}}" class="form-control" placeholder="select dates" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Gender&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], $employee->gender, ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'disabled'])}}
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('branchId', $branches, $employee->branchId, ['placeholder'=>'Select Branch','class'=>' form-control', 'id'=>'departmentId', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Department&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('departmentId', $departments, $employee->departmentId, ['placeholder'=>'Select Department','class'=>' form-control', 'id'=>'', 'disabled'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('designationId', $designations, $employee->designationId, ['placeholder'=>'Select Designation','class'=>'form-control', 'id'=>'', 'disabled'])}}
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
                                        <b style="color:blue;font-size:14px;"> </b></h4>
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
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Age</th>
                                                    <th>Relation</th>
                                                    <th>Occupation</th>
                                                    <th>Contact No</th>
                                                </tr>
                                            </thead>
                                            <tbody>	
                                                @if($empFamilyDet)
                                                    <input type="hidden" value="1" id="">
                                                    @foreach($empFamilyDet as $family)
                                                        <tr>
                                                            <td><input type="text" class="form-control" value="{{$family->name}}" name="familyName[]{{$family->id}}" disabled/></td>
                                                            <td><input type="text" class="form-control" value="{{$family->age}}" name="familyAge[]{{$family->id}}" disabled/></td>
                                                            <td><input type="text" class="form-control" value="{{$family->relation}}" name="familyRelation[]{{$family->id}}" disabled/></td>
                                                            <td><input type="text" class="form-control" value="{{$family->occupation}}" name="familyOccupation[]{{$family->id}}" disabled/></td>
                                                            <td><input type="text" class="form-control" value="{{$family->contactNo}}" name="familyContactNo[]{{$family->id}}" disabled/></td>
                                                        </tr>
                                                    @endforeach
                                                @endif											
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Qualification<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="phoneNo"  value="{{$employee->qualification}}" id="phoneNo" placeholder="Phone No" disabled>
                                        </div>
                                    </div>
                                </div>
                                @if($employee->workingStatus == 2)
                                    @for($i=0; $i < 5; $i++)
                                        @if(isset($empExperiences[$i]))
                                            @if($empExperiences[$i]->experName != '')
                                                <hr>
                                                <h4 class="mb-5 mt-3 font-weight-bold">Previous Experience Details {{$i+1}}</h4>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Name Of the Organisation&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experName[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experName:''}}" placeholder="Name"  disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experDesignation[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experDesignation:''}}" placeholder="Designation" disabled >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Duration From &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="date" class="form-control" name="experFromDuration[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experFromDuration:''}}" placeholder="Duration"  disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Duration To &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="date" class="form-control" name="experToDuration[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experToDuration:''}}" placeholder="Duration"  disabled>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Last Salary &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" onkeypress="return isNumberKey(event)" name="experLastSalary[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experLastSalary:''}}" placeholder="Last Salary"  disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Job Description &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experJobDesc[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experJobDesc:''}}" placeholder="First Name"  disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Reason for Leaving &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experReasonLeaving[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReasonLeaving:''}}" placeholder="Reason for Leaving"  disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Reporting Auth. Name &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experReportingAuth[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingAuth:''}}" placeholder="Reason for Leaving" disabled >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Reporting Auth. Designation &nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experReportingDesignation[]" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experReportingDesignation:''}}" placeholder="Reason for Leaving" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Company Contact No.&nbsp;<span class="text-red" style="font-size:22px;margin-top:2px;"></span> :</label>
                                                            <input type="text" class="form-control" name="experCompanyCont[]" onkeypress="return isNumberKey(event)" value="{{(isset($empExperiences[$i]))?$empExperiences[$i]->experCompanyCont:''}}" placeholder="Company Contact No."  disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endfor
                                @endif
                            @endif
                            @if($flag == 1)   
                                {!! Form::open(['action' => 'admin\recruitments\JobApplicationsController@storeVerification', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            @endif
                                <hr>
                                <h4 style="color:red;">Verification Team </h4>
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0" width="20%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Aadhaar Number Verification
                                                    @if(!isset($empVerfication))
                                                        <input type="text" name="verificationRemark5" minlength="12" maxlength="12"  class="form-control" value="{{$employee->AADHARNo}}" placeholder="Aadhaar Number">
                                                    @else
                                                        <input type="text" name="verificationRemark5" minlength="12" maxlength="12"  class="form-control" value="{{$empVerfication->verificationRemark5}}" readonly>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!isset($empVerfication))
                                                        {{Form::select('verificationStatus5', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required'])}}
                                                    @else
                                                        {{Form::select('verificationStatus5', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus5, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Document Verification
                                                    @if(!isset($empVerfication))
                                                        <input type="text" name="verificationRemark1" class="form-control" value="" placeholder="Remark">
                                                    @else
                                                        <input type="text" name="verificationRemark1" class="form-control" value="{{$empVerfication->verificationRemark1}}" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!isset($empVerfication))
                                                        {{Form::select('verificationStatus1', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required'])}}
                                                    @else
                                                        {{Form::select('verificationStatus1', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus1, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Address Verification
                                                    @if(!isset($empVerfication))
                                                        <input type="text" name="verificationRemark2" class="form-control" value="" placeholder="Remark">
                                                    @else
                                                        <input type="text" name="verificationRemark2" class="form-control" value="{{$empVerfication->verificationRemark2}}" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!isset($empVerfication))
                                                        {{Form::select('verificationStatus2', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required'])}}
                                                    @else
                                                        {{Form::select('verificationStatus2', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus2, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Previous Job Verification
                                                    @if(!isset($empVerfication))
                                                        <input type="text" name="verificationRemark3" class="form-control" value="" placeholder="Remark">
                                                    @else
                                                        <input type="text" name="verificationRemark3" class="form-control" value="{{$empVerfication->verificationRemark3}}" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!isset($empVerfication))
                                                        {{Form::select('verificationStatus3', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required'])}}
                                                    @else
                                                        {{Form::select('verificationStatus3', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus3, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Reference Verification
                                                    @if(!isset($empVerfication))
                                                        <input type="text" name="verificationRemark4" class="form-control" value="" placeholder="Remark">
                                                    @else
                                                        <input type="text" name="verificationRemark4" class="form-control" value="{{$empVerfication->verificationRemark4}}" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!isset($empVerfication))
                                                        {{Form::select('verificationStatus4', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required'])}}
                                                    @else
                                                        {{Form::select('verificationStatus4', ['Yes'=>'Yes','Hold'=>'Hold','No'=>'No'], $empVerfication->verificationStatus4, ['placeholder'=>'Select Option','class'=>'form-control', 'disabled'])}}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Remarks<span class="text-red"></span>:</label>
                                            @if(!isset($empVerfication))
                                                <input type="text" class="form-control" name="reamkrs" value="" placeholder="Remarks" required>
                                            @else
                                                <input type="text" class="form-control" name="reamkrs" placeholder="Remarks" value="{{$empVerfication->remarks}}" disabled>
                                            @endif
                                        </div>
                                    </div>
                                </div>  
                                @if(isset($empVerfication))
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
                                @endif
                                <div class="form-group">
                                    <div class="row">
                                        @if(!isset($empVerfication))
                                            <div class="col-md-4 col-lg-3"></div>
                                            <div class="col-md-4 col-lg-6">
                                                <input type="hidden" value="{{!isset($application)?0:$application->id}}" name="cifId">
                                                <input type="hidden" value="{{$employee->id}}" name="id">
                                                <button type="submit" name="reject" value="reject" class="btn btn-danger btn-lg">Reject</button>
                                                <button type="submit" name="verified" value="verified" class="btn btn-success btn-lg">Verified</button>
                                                <button type="submit" name="hold" value="hold" class="btn btn-primary btn-lg">Hold</button>
                                                <a href="{{ url()->previous() }}" class="btn btn-warning btn-lg">Cancel</a>
                                            </div>
                                            <div class="col-md-4 col-lg-3"></div>
                                        @else
                                            <div class="col-md-5 col-lg-5"></div>
                                            <div class="col-md-2 col-lg-2">
                                                @if($empVerfication->status == "Rejected")
                                                    <button type="button" name="reject" value="reject" class="btn btn-danger btn-lg">Reject</button>
                                                @elseif($empVerfication->status == "Verified")
                                                    <button type="button" name="verified" value="verified" class="btn btn-success btn-lg">Verified</button>
                                                @else
                                                    <button type="button" name="hold" value="hold" class="btn btn-primary btn-lg">Hold / CBC</button>
                                                @endif
                                            </div>
                                            <div class="col-md-5 col-lg-5"></div>
                                        @endif
                                        
                                    </div>
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
