@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Walk in</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/jobApplications/walkinList" class="btn btn-primary mr-3">Walk in List</a>
                            <a href="/jobApplications/walkinCreate" class="btn btn-success mr-3">Add Entry</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\recruitments\JobApplicationsController@walkinUpdate', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Section<span class="text-red">*</span>:</label>
                                            {{Form::select('sectionId', ['Teaching'=>'Teaching','Non Teaching'=>'Non Teaching'], $application->section, ['placeholder'=>'Select Section','class'=>'sectionId form-control', 'id'=>'sectionId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="forDate" value="{{$application->forDate}}" placeholder="" required>
                                        </div>
                                    </div>  
                                </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Department<span class="text-red">*</span>:</label>
                                            {{Form::select('empDepartmentId', $departments, $application->departmentId, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'empDepartmentId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Designation<span class="text-red">*</span>:</label>
                                            {{Form::select('empDesignationId', $designations, $application->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'empDesignationId', 'required'])}}
                                        </div>
                                    </div>  
                                </div>
                                <hr>
                                <h5 style="color:red;">Personal Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">First Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="firstName" value="{{$application->firstName}}" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Middle Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="middleName" value="{{$application->middleName}}" placeholder="Middle Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Last Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="lastName" value="{{$application->lastName}}" placeholder="Last Name" required>
                                        </div>
                                    </div>  
                                </div>     
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Mobile No.<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="mobileNo" value="{{$application->mobileNo}}" placeholder="Mobile No." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Mother Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="motherName" value="{{$application->motherName}}" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Father Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="fatherName" value="{{$application->fatherName}}" placeholder="Middle Name" required>
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Marital status<span class="text-red">*</span>:</label>
                                            {{Form::select('maritalStatus', ['Married'=>'Married','Single'=>'Single','Seprated'=>'Seprated','Divorce'=>'Divorce','Widow'=>'Widow'], $application->maritalStatus, ['placeholder'=>'Select Marital status','class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Language known<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="language" value="{{$application->language}}" placeholder="Language known" required>
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
													<td><input type="text" class="form-control" name="board10Th" value="{{$application->board10Th}}" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPass10Th" value="{{$application->yearPass10Th}}" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent10Th" value="{{$application->percent10Th}}" placeholder="Percentage"></td>
												</tr>
												<tr>
													<th scope="row">2</th>
													<td>Std 12</td>
													<td><input type="text" class="form-control" name="board12Th" value="{{$application->board12Th}}" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPass12Th" value="{{$application->yearPass12Th}}" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent12Th" value="{{$application->percent12Th}}" placeholder="Percentage"></td>
												</tr>
                                                <tr>
													<th scope="row">3</th>
													<td>Graducte</td>
													<td><input type="text" class="form-control" name="boardGrad" value="{{$application->boardGrad}}" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPassGrad" value="{{$application->yearPassGrad}}" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percentGrad" value="{{$application->percentGrad}}" placeholder="Percentage"></td>
												</tr>
												<tr>
													<th scope="row">4</th>
													<td>Post Graducte</td>
													<td><input type="text" class="form-control" name="boardPostG" value="{{$application->boardPostG}}" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPassPostG" value="{{$application->yearPassPostG}}" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percentPostG" value="{{$application->percentPostG}}" placeholder="Percentage"></td>
												</tr>
											</tbody>
										</table>
									</div>
                                    </div>
                                </div>
                                <hr>
                                <h5 style="color:red;">Computer Proficiency Details</h5>   
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Work Experience (Till Date:)<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="totalWorkExp" value="{{$application->totalWorkExp}}" placeholder="Work Experience (Till Date:)" required>
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
													<td><input type="text" class="form-control" name="organisation1" value="{{$application->organisation1}}" placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp1" value="{{$application->exp1}}" placeholder="Exp in years"></td>
													<td><input type="text" class="form-control" name="respon1" value="{{$application->respon1}}" placeholder="Responsiblity/post"></td>
													<td><input type="text" class="form-control" name="reasonLeav1" value="{{$application->reasonLeav1}}" placeholder="Reason for leaving"></td>
												</tr>
												<tr>
													<th scope="row">2</th>
													<td><input type="text" class="form-control" name="organisation2" value="{{$application->organisation2}}" placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp2" value="{{$application->exp2}}" placeholder="Exp in years"></td>
													<td><input type="text" class="form-control" name="respon2" value="{{$application->respon2}}" placeholder="Responsiblity/post"></td>
													<td><input type="text" class="form-control" name="reasonLeav2" value="{{$application->reasonLeav2}}" placeholder="Reason for leaving"></td>
												</tr>
                                                <tr>
													<th scope="row">3</th>
													<td><input type="text" class="form-control" name="organisation3" value="{{$application->organisation3}}" placeholder="Name of the organistions"></td>
													<td><input type="text" class="form-control" name="exp3" value="{{$application->exp3}}" placeholder="Exp in years"></td>
													<td><input type="text" class="form-control" name="respon3" value="{{$application->respon3}}" placeholder="Responsiblity/post"></td>
													<td><input type="text" class="form-control" name="reasonLeav3" value="{{$application->reasonLeav3}}" placeholder="Reason for leaving"></td>
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
                                            <input type="text" class="form-control" name="yourStrenghths" value="{{$application->yourStrenghths}}" placeholder="Your Strenghths">
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Hobbies<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="hobbies" value="{{$application->hobbies}}" placeholder="Hobbies">
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Application Status<span class="text-red">*</span>:</label>
                                            {{Form::select('appStatus', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], $application->appStatus, ['placeholder'=>'Select Status','class'=>'appStatus form-control', 'id'=>'appStatus', 'required'])}}
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
                                            <input type="hidden" value="{{$application->id}}" name="id">
                                            <input type="hidden" value="1" name="appType">
                                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                            <a href="{{ url()->previous() }}" class="btn btn-danger btn-lg">Cancel</a>
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
