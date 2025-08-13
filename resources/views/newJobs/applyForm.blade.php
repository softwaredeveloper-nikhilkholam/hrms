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
                            {!! Form::open(['action' => 'LandingPagesController@applyJobApplication', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Section<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="name" value="{{$empJob->section}}" placeholder="Holiday Name" disabled>
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
                                            <label class="form-label">Department<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="name" value="{{$empJob->departmentName}}" placeholder="Holiday Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Designation<span class="text-red"></span>:</label>
                                            <input type="text" class="form-control" name="forDate" value="{{$empJob->designationName}}" placeholder="" disabled>
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
                                            <input type="text" class="form-control" name="mobileNo" value="" placeholder="Mobile No." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Emergency No.<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="emergencyNo" value="" placeholder="Emergency No." required>
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
                                </div>  
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Interview Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <select class="form-control" name="forDate">
                                                <option value="">Select Slot</option>
                                                <?php
                                                    $start = new DateTime($empJob->postedDate);
                                                    $end = new DateTime($empJob->lastDateToApply);
                                                    $current = clone $start;
                                                    while ($current <= $end) { 
                                                        $to = $current->format('d-m-Y');
                                                        ?>
                                                        <option value="{{$to}}">{{$to}}</option>
                                                            <?php
                                                            $current->modify("+1 day"); 
                                                        }
                                                ?>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Slot<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <select class="form-control" name="slot">
                                                <option value="">Select Slot</option>
                                                <?php
                                                    $fDate1 = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$empJob->startTime1.":00"));
                                                    $fDate2 = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$empJob->endTime1.":00"));
                                                    
                                                    $start = new DateTime($fDate1);
                                                    $end = new DateTime(date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($fDate2))));
                                                    $current = clone $start;
                                                    while ($current <= $end) { 
                                                        $from = $current->format('g:ia');
                                                        $time = "+".$empJob->slots." hours";
                                                        $current->modify($time);
                                                        $to = $current->format('g:ia');
                                                        ?>
                                                        <option value="{{$from}} - {{$to}}">{{$from}} - {{$to}}</option>
                                                            <?php
                                                    }

                                                    $fDatex1 = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$empJob->startTime2.":00"));
                                                    $fDatex2 = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$empJob->endTime2.":00"));

                                                    $startx = new DateTime($fDatex1);
                                                    $endx = new DateTime(date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($fDatex2))));
                                                    $currentx = clone $startx;
                                                    while ($currentx <= $endx) { 
                                                        $fromx = $currentx->format('g:ia');
                                                        $timex = "+".$empJob->slots." hours";
                                                        $currentx->modify($timex);
                                                        $tox = $currentx->format('g:ia');
                                                        ?>
                                                        <option value="{{$fromx}} - {{$tox}}">{{$fromx}} - {{$tox}}</option>
                                                            <?php
                                                    }
                                                ?>
                                            </select>
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
													<td><input type="text" class="form-control" name="percent10Th" value="" id="txtChar" onkeypress="return jobIsNumberKey(event)"  placeholder="Percentage"></td>
												</tr>
												<tr>
													<th scope="row">2</th>
													<td>Std 12</td>
													<td><input type="text" class="form-control" name="board12Th" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPass12Th" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percent12Th" value="" id="txtChar" onkeypress="return jobIsNumberKey(event)" placeholder="Percentage"></td>
												</tr>
                                                <tr>
													<th scope="row">3</th>
													<td>Graduate</td>
													<td><input type="text" class="form-control" name="boardGrad" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPassGrad" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percentGrad" value="" id="txtChar" onkeypress="return jobIsNumberKey(event)" placeholder="Percentage"></td>
												</tr>
												<tr>
													<th scope="row">4</th>
													<td>Post Graduate</td>
													<td><input type="text" class="form-control" name="boardPostG" value="" placeholder="Board /Universtity"></td>
													<td><input type="text" class="form-control" name="yearPassPostG" value="" placeholder="Year Of passing"></td>
													<td><input type="text" class="form-control" name="percentPostG" value="" id="txtChar" onkeypress="return jobIsNumberKey(event)" placeholder="Percentage"></td>
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
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Upload Resume <span class="text-red"></span>:</label>
                                            <input type="file" class="form-control" name="resume" value="" placeholder="Upload Resume">
                                        </div>
                                    </div>
                                </div>  -->
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
                                            <input type="hidden" value="{{$empJob->id}}" name="jobId">
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
