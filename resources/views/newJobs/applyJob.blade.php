@extends('layouts.master3')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Job Application</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Back To Job Descriptiondddd</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(['action' => 'LandingPagesController@applyJobApplication', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    @if($errors->any())
                                        <b style='color:red;'>{{ implode('', $errors->all(":message")) }}</b></br>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Section<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], NULL, ['placeholder'=>'Select Section','class'=>'form-control', 'required', 'id'=>'jobSection'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="forDate" value="{{date('d-M-Y')}}" required placeholder="" disabled>
                                        </div>
                                    </div>  
                                </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Post Applied for<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('designationId', $designations, NULL, ['placeholder'=>'Select Designation','class'=>'form-control jobDesignationId', 'id'=>'jobDesignationId','required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Profile Photo<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="file" class="form-control" name="profilePhoto" accept="image/*;capture=camera" required placeholder="">
                                        </div>
                                    </div>  
                                </div>
                                <hr>
                                <h5 style="color:red;">Personal Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">First Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="firstName" value="" required placeholder="First Name" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Middle Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="middleName" value="" required placeholder="Middle Name" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Last Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="lastName" value="" required placeholder="Last Name" >
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mother's Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="motherName" value="" required placeholder="Mother's Name" >
                                        </div>
                                    </div>
                                </div> 
                                <h5 style="color:red;">Name as on Adhar Card</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">First Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="adharFirstName" value="" required placeholder="First Name" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Middle Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="adharMiddleName" value="" required placeholder="Middle Name" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Last Name<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="adharLastName" value="" required placeholder="Last Name" >
                                        </div>
                                    </div>  
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Birth Date<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="date" class="form-control" name="DOB" value="" required placeholder="Birth Date" >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Gender<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('gender', ['Male'=>'Male','Female'=>'Female'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Religion<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="religion" value="" required placeholder="Religion">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Caste<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="caste" value="" required placeholder="Caste">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Category<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="category" value="" required placeholder="Category">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Marital status<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Divorcee'=>'Divorcee','Seperated'=>'Seperated','Widow'=>'Widow'], null, ['placeholder'=>'Select Marital status','class'=>'form-control', 'required'])}}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Present Address in detail<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="presentAddress" value="" required placeholder="Present Address in detail" >
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Permenant Address in detail<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="permanentAddress" value="" required placeholder="Permanent Address in detail" >
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mobile No.<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="mobileNo" onkeypress="return isNumberKey(event)" maxlength="10" value="" required placeholder="Mobile No." >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">W.A. No.<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="whatsMobileNo" onkeypress="return isNumberKey(event)" maxlength="10" value="" required placeholder="W.A. No." >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Email Id<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="email" class="form-control" name="email" value="" required placeholder="Email Id">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 style="color:red;">Available on</h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Facebook" name="facebook" value="1">
                                            <label class="form-check-label" for="Facebook">Facebook</label>
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Instagram" name="instagram" value="1">
                                            <label class="form-check-label" for="Instagram">Instagram</label>
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="LinkedIn" name="linkedIn" value="1">
                                            <label class="form-check-label" for="LinkedIn">LinkedIn</label>
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="check1" name="twitter" value="1">
                                            <label class="form-check-label" for="check1">Twitter</label>
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="YouTube" name="youTube" value="1">
                                            <label class="form-check-label" for="YouTube">YouTube</label>
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Google" name="googlePlus" value="1">
                                            <label class="form-check-label" for="Google">Google +</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 style="color:red;">Languages known<span class="text-red" style="font-size:22px;">*</span></h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="English" name="english" value="1">
                                            <label class="form-check-label" for="English">English</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Hindi" name="hindi" value="1">
                                            <label class="form-check-label" for="Hindi">Hindi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Marathi" name="marathi" value="1">
                                            <label class="form-check-label" for="Marathi">Marathi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Other</label>
                                            <input type="text" class="form-control" name="otherLanguage" value="" placeholder="Other Language">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 style="color:red;">Emergency contact details</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Name of the person<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="emergencePersonName" value="" required placeholder="Name of the person">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Relation<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="emergenceRelation" value="" required placeholder="Relation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mob<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="emergenceMob" onkeypress="return isNumberKey(event)" maxlength="10" value="" required placeholder="Mob">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 style="color:red;">Advertisement and Reference Source</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Advertisement Source<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('advSource', ['Newspaper Advertisement'=>'Newspaper Advertisement', 'Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Whatsapp'=>'Whatsapp', 'Website'=>'Website', 'Friend'=>'Friend', 'Current Employee'=>'Current Employee', 'Ex-Employee of Aaryans'=>'Ex-Employee of Aaryans', 'Authority Relative'=>'Authority Relative', 'Walk in'=>'Walk in', 'Other'=>'Other'], null, ['placeholder'=>'Select Source','class'=>'form-control', 'required', 'id'=>'reference'])}}
                                        </div>
                                    </div>
                                </div>
                                <h5 style="color:red;" id="">Reference from Aaryans World School (if any)</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="refName" value=""  placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Contact No.</label>
                                            <input type="text" class="form-control" name="refContactNo" onkeypress="return isNumberKey(event)" maxlength="10" value=""  placeholder="Contact No.">
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
													<td><input type="text" class="form-control" name="degree1" value="" placeholder="Degree / Stream / Qualification" ></td>
													<td><input type="text" class="form-control" name="board1" value="" placeholder="Board /Universtity" ></td>
													<td><input type="text" class="form-control" name="passingYear1" value="" placeholder="Year Of passing" ></td>
													<td><input type="text" class="form-control" name="percent1" value="" placeholder="Percentage" ></td>
												</tr>
												<tr>
													<td>Std. 12th</td>
													<td><input type="text" class="form-control" name="degree2" value=""  placeholder="Degree / Stream / Qualification"></td>
													<td><input type="text" class="form-control" name="board2" value=""  placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="passingYear2" value=""  placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent2" value=""  placeholder="Percentage"></td>
												</tr>
                                                <tr>
													<td>Graduation</td>
													<td><input type="text" class="form-control" name="degree3" value=""  placeholder="Degree / Stream / Qualification"></td>
													<td><input type="text" class="form-control" name="board3" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="passingYear3" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent3" value="" placeholder="Percentage"></td>
												</tr>
												<tr>
													<td>Post Graduation</td>
													<td><input type="text" class="form-control" name="degree4" value="" placeholder="Degree / Stream / Qualification"></td>
													<td><input type="text" class="form-control" name="board4" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="passingYear4" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent4" value="" placeholder="Percentage"></td>
												</tr>
                                                <tr class="traineeDegree">
													<td>Trainee Degree</td>
                                                    <td>{{Form::select('degree5', ['TTC'=>'TTC','NTC'=>'NTC','D.Ed. '=>'D.Ed.','B.Ed.'=>'B.Ed.','M.Ed.'=>'M.Ed.','PhD.'=>'PhD.'], null, ['placeholder'=>'Select Degree / Stream / Qualification','class'=>'form-control'])}}</td>
													<td><input type="text" class="form-control" name="board5" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="passingYear5" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent5" value="" placeholder="Percentage"></td>
												</tr>
                                                <tr class="methods">
													<td>Methods / Subjects / Topic</td>
													<td><input type="text" class="form-control" name="degree6" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="board6" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="passingYear6" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent6" value="" placeholder="Percentage"></td>
												</tr>
                                                <tr class="">
													<td>Any other Special qualification (if any)</td>
													<td><input type="text" class="form-control" name="degree7" value="" placeholder="Methods / Subjects / Topic"></td>
													<td><input type="text" class="form-control" name="board7" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="passingYear7" value=""  placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent7" value=""  placeholder="Percentage"></td>
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
                                                    <input type="radio" class="form-check-input" id="Beginner" name="overallComputerProficiency" value="1">
                                                    <label class="form-check-label" for="Beginner">Beginner</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="Medium" name="overallComputerProficiency" value="2">
                                                    <label class="form-check-label" for="Medium">Medium</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="Expert" name="overallComputerProficiency" value="3">
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
                                                    <input type="radio" class="form-check-input" id="Beginner1" name="microsoftOffice" value="1">
                                                    <label class="form-check-label" for="Beginner1">Beginner</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="Medium1" name="microsoftOffice" value="2">
                                                    <label class="form-check-label" for="Medium1">Medium</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="Expert1" name="microsoftOffice" value="3">
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
                                            <input type="text" class="form-control" name="specialEducation" value="" required placeholder="Special Education / Certification in Ccomputer (if any)">
                                        </div>
                                    </div>
                                </div>
                                 
                                <hr>
                                <h5 style="color:red;">Work Experience Details</h5>   
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="Fresher" name="workExperienceDetails" value="1">
                                            <label class="form-check-label" for="Fresher">Fresher<span class="text-red" style="font-size:22px;">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="Experienced" name="workExperienceDetails" value="2">
                                            <label class="form-check-label" for="Experienced">Experienced<span class="text-red" style="font-size:22px;">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 selectExperiencedDetails">
                                        <div class="form-check">
                                            <label class="form-label">Select Experience<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('experience', ['0to1 year'=>'0to1 year', '1to2 years'=>'1to2 years', '3to5 years '=>'3to5 years ', '6years & +'=>'6years & +'], null, ['placeholder'=>'Select experience','class'=>'form-control',  'id'=>'experience'])}}
                                        </div>
                                    </div>
                                </div>
                                <hr>  
                                <div class="row ExperiencedDetails">
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
													<td><input type="text" class="form-control" name="organisation1" value=""  placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp1" value=""  placeholder="Exp in yrs/mnths"></td>
													<td><input type="text" class="form-control" name="from1" value=""  placeholder="From (month and year)"></td>
													<td><input type="text" class="form-control" name="to1" value=""  placeholder="To (month and year)"></td>
													<td><input type="text" class="form-control" name="post1" value=""  placeholder="Post/Responsibility"></td>
													<td class="stdSub1"><input type="text" class="form-control " name="std1" value=""  placeholder="Std&Sub"></td>
													<td><input type="text" class="form-control" name="reason1" value=""  placeholder="Reason for leaving"></td>
												</tr>
                                                <tr>
													<th scope="row">2nd</th>
													<td><input type="text" class="form-control" name="organisation2" value=""  placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp2" value=""  placeholder="Exp in yrs/mnths"></td>
													<td><input type="text" class="form-control" name="from2" value=""  placeholder="From (month and year)"></td>
													<td><input type="text" class="form-control" name="to2" value=""  placeholder="To (month and year)"></td>
													<td><input type="text" class="form-control" name="post2" value=""  placeholder="Post/Responsibility"></td>
													<td class="stdSub2"><input type="text" class="form-control " name="std2" value=""  placeholder="Std&Sub"></td>
													<td><input type="text" class="form-control" name="reason2" value=""  placeholder="Reason for leaving"></td>
												</tr>
                                                <tr>
													<th scope="row">3rd</th>
													<td><input type="text" class="form-control" name="organisation3" value=""  placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp3" value=""  placeholder="Exp in yrs/mnths"></td>
													<td><input type="text" class="form-control" name="from3" value=""  placeholder="From (month and year)"></td>
													<td><input type="text" class="form-control" name="to3" value=""  placeholder="To (month and year)"></td>
													<td><input type="text" class="form-control" name="post3" value=""  placeholder="Post/Responsibility"></td>
													<td class="stdSub3"><input type="text" class="form-control " name="std3" value=""  placeholder="Std&Sub"></td>
													<td><input type="text" class="form-control" name="reason3" value=""  placeholder="Reason for leaving"></td>
												</tr>
                                                <tr>
													<th scope="row">4th</th>
													<td><input type="text" class="form-control" name="organisation4" value=""  placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp4" value=""  placeholder="Exp in yrs/mnths"></td>
													<td><input type="text" class="form-control" name="from4" value=""  placeholder="From (month and year)"></td>
													<td><input type="text" class="form-control" name="to4" value=""  placeholder="To (month and year)"></td>
													<td><input type="text" class="form-control" name="post4" value=""  placeholder="Post/Responsibility"></td>
													<td class="stdSub4"><input type="text" class="form-control " name="std4" value=""  placeholder="Std&Sub"></td>
													<td><input type="text" class="form-control" name="reason4" value=""  placeholder="Reason for leaving"></td>
												</tr>
												
											</tbody>
										</table>
									</div>
                                    </div>
                                </div>  
                               
                                <h5 style="color:red;" class="ExperiencedDetailsH2 mt-3">Reference details of last two Organizations</h5>
                                <div class="row ExperiencedDetailsRow">
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
													<td><input type="text" class="form-control" name="refOrganization1" value=""  placeholder="Name of the organization"></td>
													<td><input type="text" class="form-control" name="refrepoAuth1" value=""  placeholder="Name of reporting authority"></td>
													<td><input type="text" class="form-control" name="refRepoAuthPost1" value=""  placeholder="Post of reporting authority"></td>
													<td><input type="text" class="form-control" name="refContctNo1" value=""  placeholder="Contact No." onkeypress="return isNumberKey(event)" maxlength="10"></td>
													<td><input type="email" class="form-control" name="refEmail1" value=""  placeholder="Email id"></td>
												</tr>
												<tr>
													<td>2</td>
													<td><input type="text" class="form-control" name="refOrganization2" value=""  placeholder="Name of the organization"></td>
													<td><input type="text" class="form-control" name="refRepoAuth2" value=""  placeholder="Name of reporting authority"></td>
													<td><input type="text" class="form-control" name="refRepoAuthPost2" value=""  placeholder="Post of reporting authority"></td>
													<td><input type="text" class="form-control" name="refContctNo2" value="" onkeypress="return isNumberKey(event)" maxlength="10" placeholder="Contact No."></td>
													<td><input type="email" class="form-control" name="refEmail2" value=""  placeholder="Email id"></td>
												</tr>
											</tbody>
										</table>
									</div>
                                    </div>
                                    <hr> 
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Last drawn in-hand salary<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="lastSalary" onkeypress="return isNumberKey(event)" value="" required placeholder="Last drawn in-hand salary">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Expected Salary<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="expectedSalary" onkeypress="return isNumberKey(event)" value="" placeholder="Expected Salary">
                                        </div>
                                    </div>
                                </div>  

                                <hr>
                                <h5 style="color:red;">About you</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Your Strenghths</label>
                                            <input type="text" class="form-control" name="strenghths" value="" placeholder="Your Strenghths">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Hobbies<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="hobbies" value="" required placeholder="Hobbies">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Extra-curricular activities and achievements (if any)</label>
                                            <input type="text" class="form-control" name="extraCurricular" value="" placeholder="Extra-curricular activities and achievements (if any)">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 style="color:red;">Medical History if any</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Previous<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="medicalPrevious" value="" required placeholder="Previous">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Current<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="medicalCurrent" value="" required placeholder="Current">
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Blood Group</label>
                                            <input type="text" class="form-control" name="bloodGp" value="" placeholder="Blood Group">
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Previously applied here<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('prevAppliedFor', ['Yes'=>'Yes', 'No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required', 'id'=>'appliedFor'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3 showAppliedHere1">
                                        <div class="form-group">
                                            <label class="form-label">For Month<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="month" class="form-control" name="appliedForMonth" value=""  placeholder="For Month">
                                        </div>
                                    </div>
                                    <div class="col-md-3 showAppliedHere2">
                                        <div class="form-group">
                                            <label class="form-label">For which Post<span class="text-red" style="font-size:22px;">*</span></label>
                                            <input type="text" class="form-control" name="appliedForPost" value=""  placeholder="For which Post">
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Ex-Employee of Aaryans World School<span class="text-red" style="font-size:22px;">*</span></label>
                                            {{Form::select('exEmployee', ['Yes'=>'Yes', 'No'=>'No'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'required', 'id'=>'reference'])}}
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Upload Resume</label>
                                            <input type="file" class="form-control" name="resume" value="" placeholder="Upload Resume">
                                        </div>
                                    </div>
                                </div>  
                                <hr>   
                                <h5 style="color:red;">Declaration</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="Declaration" name="option1" value="something">
                                            <label class="form-check-label" for="Declaration">I hereby declare that the above information is true & correct.<span class="text-red" style="font-size:22px;">*</span></label>
                                        </div>
                                    </div>
                                </div>   
                                           
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Apply</button>
                                            <a href="/" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
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
