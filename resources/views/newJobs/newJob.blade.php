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
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Back To Job Description</a>
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
                                            <label class="form-label">Section<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="section" value="{{$type}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="forDate" value="{{date('d-M-Y')}}" placeholder="" disabled>
                                        </div>
                                    </div>  
                                </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Designations&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('designationId', $designations, NULL, ['placeholder'=>'Select Designation','class'=>'form-control', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Profile Photo<span class="text-red"></span>:</label>
                                            <input type="file" class="form-control" name="profilePhoto" accept="image/png, image/jpg, image/jpeg" placeholder="">
                                        </div>
                                    </div>  
                                </div>
                                <hr>
                                <h5 style="color:red;">Personal Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">First Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="firstName" value="" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Middle Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="middleName" value="" placeholder="Middle Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Last Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="lastName" value="" placeholder="Last Name" required>
                                        </div>
                                    </div>  
                                </div>     
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mobile No.<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="mobileNo" onkeypress="return isNumberKey(event)" maxlength="10" value="" placeholder="Mobile No." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Emergency No.<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="emergencyNo" onkeypress="return isNumberKey(event)" maxlength="10" value="" placeholder="Emergency No." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mother Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="motherName" value="" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Birth Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="DOB" value="" placeholder="Birth Date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">PAN CARD No<span class="text-red">{{($type == 'Teaching')?'*':''}}</span>:</label>
                                            <input type="text" class="form-control" style="text-transform:uppercase" id="panNumber" name="PANCARDNo" value="" placeholder="PAN CARD No" {{($type == 'Teaching')?'required':''}}>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Aaddhar Card No<span class="text-red">{{($type == 'Teaching')?'*':''}}</span>:</label>
                                            <input type="text" class="form-control" onkeypress="return isNumberKey(event)" maxlength="12" minlength="12" name="AADDHARNo" value="" placeholder="Aaddhar Card No" {{($type == 'Teaching')?'required':''}}>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Address<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="address" value="" placeholder="Address" required>
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Marital status<span class="text-red">*</span>:</label>
                                            {{Form::select('maritalStatus', ['Married'=>'Married','Single'=>'Single','Seprated'=>'Seprated','Divorce'=>'Divorce','Widow'=>'Widow'], null, ['placeholder'=>'Select Marital status','class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Language known<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="language" value="" placeholder="Language known" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Reference<span class="text-red">*</span>:</label>
                                            {{Form::select('reference', ['Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Whatsapp'=>'Whatsapp', 'Website'=>'Website', 'Friend to friend'=>'Friend to friend', 'Authority Relative'=>'Authority Relative', 'AWS School'=>'AWS School', 'Newspaper'=>'Newspaper', 'Other'=>'Other'], null, ['placeholder'=>'Select Source','class'=>'form-control', 'id'=>'reference', 'required'])}}
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
													<td>Std 10 / Others</td>
													<td><input type="text" class="form-control" name="board10Th" value="" placeholder="Board /Universtity" required></td>
													<td><input type="text" class="form-control" name="yearPass10Th" value="" placeholder="Year Of passing" required></td>
													<td><input type="text" class="form-control" name="percent10Th" value="" placeholder="Percentage" required></td>
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
                                <h5 style="color:red;">For Accounts</h5>   

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="check1" name="option1" value="something">
                                            <label class="form-check-label" for="check1">Adv. Excel</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="check2" name="option2" value="something">
                                            <label class="form-check-label" for="check2">Tally 9.2.6</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="check3" name="option3" value="something">
                                            <label class="form-check-label" for="check3">Tally Prime</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="check4" name="option4" value="something">
                                            <label class="form-check-label" for="check4">Typing Speed</label>
                                        </div>
                                    </div>
                                </div>  
                                <hr>
                                <h5 style="color:red;">Computer Proficiency Details</h5>   
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Work Experience (Till Date:)<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="totalWorkExp" value="" placeholder="Work Experience (Till Date:)" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Last Salary<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="lastSalary" value="0" placeholder="Last Salary" required>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="table-responsive">
										<table class="table card-table table-vcenter text-nowrap table-primary mb-0">
											<thead  class="bg-warning text-black">
												<tr >
													<th class="text-black">ID</th>
													<th class="text-black">Name of the organisations</th>
													<th class="text-black">Exp in years</th>
													<th class="text-black">Responsiblity/post</th>
													<th class="text-black">Reason for leaving</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<th scope="row">1</th>
													<td><input type="text" class="form-control" name="organisation1" value="" placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp1" value="" placeholder="Exp in years"></td>
													<td><input type="text" class="form-control" name="respon1" value="" placeholder="Responsiblity/post"></td>
													<td><input type="text" class="form-control" name="reasonLeav1" value="" placeholder="Reason for leaving"></td>
												</tr>
												<tr>
													<th scope="row">2</th>
													<td><input type="text" class="form-control" name="organisation2" value="" placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp2" value="" placeholder="Exp in years"></td>
													<td><input type="text" class="form-control" name="respon2" value="" placeholder="Responsiblity/post"></td>
													<td><input type="text" class="form-control" name="reasonLeav2" value="" placeholder="Reason for leaving"></td>
												</tr>
                                                <tr>
													<th scope="row">3</th>
													<td><input type="text" class="form-control" name="organisation3" value="" placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp3" value="" placeholder="Exp in years"></td>
													<td><input type="text" class="form-control" name="respon3" value="" placeholder="Responsiblity/post"></td>
													<td><input type="text" class="form-control" name="reasonLeav3" value="" placeholder="Reason for leaving"></td>
												</tr>
											</tbody>
										</table>
									</div>
                                    </div>
                                </div>  
                                <hr> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Your Strenghths<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="yourStrenghths" value="" placeholder="Your Strenghths">
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Hobbies<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="hobbies" value="" placeholder="Hobbies">
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Upload Resume <span class="text-red"></span>:</label>
                                            <input type="file" class="form-control" name="resume" value="" placeholder="Upload Resume">
                                        </div>
                                    </div>
                                </div>  
                                <hr>   
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Declaration <span class="text-red"></span>:</label>
                                            <b style="color:red;">I hereby declare that the above information is true & correct.</b>
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
