<?php
    $user = Auth::user();
	$userType = $user->userType;
	$appointStatus = $user->appointStatus;

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
                    <h4 class="page-title">Appointment</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Appointment Detail</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-5">
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">To</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">
                                            {{Form::select('requestTo', ['3'=>'MD Milind ladge', '4'=>'CEO Pratik Ladge', '5'=>'COO Pranav Ladge'], $appointment->appointWith, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;',  'disabled'])}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 col-lg-3"></div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label" style="font-size:18px;"><b style="font-size:20px;"><u>Subject: {{$appointment->reason}}</u></label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Dear Sir,</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">I am <b>{{$appointment->name}}</b>,employee Id <b>{{($appointment->firmType == 1)?'AWS':(($appointment->firmType == 2)?'AFF':'ADF')}}{{$appointment->empCode}}</b>,  
                                        working as a <b>{{$appointment->desName}}, {{$appointment->deptName}}</b>in <b>{{($appointment->branchName == '')?'-':$appointment->branchName}}</b>.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                        I am writing to request a meeting with you to discuss <b style="color:red">{{$appointment->reason}}</b> so, can we meet on <b style="color:red">{{($appointment->originalForDate == '')?'-':date('d-M-Y', strtotime($appointment->originalForDate))}}</b>. <br>Please tell me and I will adjust accordingly.
                                        I appreciate your consideration and hope to meet you soon. <br>Thank you for your time.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 col-lg-9"></div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Best Regards,</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 col-lg-9"></div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label"><b>{{$appointment->name}}</b></label>
                                    </div>
                                </div>
                            </div>
                                @if($appointment->status != "1")
                                    <tr>
                                        <th>
                                            <div class="row"> 
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        @if($appointment->status == "2")
                                                            <b style="color:green;">
                                                                Hi,<br><br>

                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thank you for requesting an time for appointement. Your request has been accpeted, your appointment is on {{date('d-M-Y', strtotime($appointment->forDate))}} and time {{date('h:i A', strtotime($appointment->forTime))}}
                                                                    <br><br>  
                                                                Thanks.                                                                    
                                                            </b>
                                                            @if($appointStatus != "0")
                                                                @if($appointment->meetingStatus != "Completed")
                                                                    <br><br>
                                                                    <hr>
                                                                    {!! Form::open(['action' => 'admin\AppointmentsController@changeStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                                        <div class="row"> 
                                                                            <div class="col-md-4">
                                                                                <input type="submit" value="Meeting Completed" name="Completed" class="btn btn-success">
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <input type="date" name="forDate" value="{{$appointment->forDate}}" class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <input type="time" name="forTime" value="{{$appointment->forTime}}" class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <input type="hidden" value="2" name="flag">
                                                                                        <input type="hidden"  value="{{$appointment->id}}" name="id">
                                                                                        <input type="submit" value="Meeting Re-schedule" name="approve" class="btn btn-danger">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            @endif
                                                        @elseif($appointment->status == "3")
                                                            <b style="color:orange;">
                                                                Hi,<br><br>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thank you for requesting an time for appointement.  Your appointment has been postpone. Sorry for the inconvenience cause.
                                                                    <br><br>  
                                                                Thanks.                                                                    
                                                            </b>
                                                        @elseif($appointment->status == "4")
                                                            <b style="color:red;">
                                                                Hi,<br><br>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thank you for requesting an time for appointement. Your request has been rejected due to busy Bussiness Sechedule, please try in next week. 
                                                                    <br><br>  
                                                                Thanks.                                                                    
                                                            </b>
                                                        @endif
                                                        <br><hr>
                                                        <b style="color:purple;">
                                                            Meeting Date : {{date('d-m-Y', strtotime($appointment->forDate))}}   <br>
                                                            Comment : {{$appointment->comment}}   <br>
                                                            Location : {{$appointment->location}}                                                                
                                                        </b>
                                                    </div>
                                                </div>  
                                            </div>    
                                        </th>
                                    </tr>
                                @endif
                                @if($appointment->status == "1")
                                    @if($appointStatus != "0")
                                        {!! Form::open(['action' => 'admin\AppointmentsController@changeStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                            @if($lastStatus)
                                                <b style="color:purple;">Last Meeting Status : {{($lastStatus->status=="2")?'Approved':(($lastStatus->status=="3")?"Postponed":"Rejected")}} on {{date('d-M-Y', strtotime($lastStatus->forDate))}}</b>
                                            @endif
                                            <hr>
                                            <h4 style="color:red;">If Any Feedback</h4>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="form-label">Comment :</label>
                                                        <textarea class="form-control" name="comment" rows="3" cols="50"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Location :</label>
                                                        <input id="myText" class="form-control" name="otherLocation" placeholder="Search Location" />
                                                        <input type="hidden" value="1" name="flag">
                                                    </div>
                                                </div>  
                                            </div> 
                                            <div class="row"> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Date <span class="text-red">*</span>:</label>
                                                        <input type="date" class="form-control" name="forDate" value="{{$appointment->forDate}}" placeholder="Comment" required>
                                                    </div>
                                                </div>  
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Time <span class="text-red">*</span>:</label>
                                                        <input type="time" class="form-control" name="forTime" placeholder="Comment" required>
                                                    </div>
                                                </div> 
                                            </div>    
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-4"></div>
                                                    <div class="col-md-12 col-lg-6">
                                                        <input type="hidden" value="{{$appointment->id}}" name="id">
                                                        <input type="submit" name="approve" value="Approve" class="btn btn-success btn-lg">
                                                        <input type="submit" name="postponed" value="Postponed" class="btn btn-warning btn-lg">
                                                        <input type="submit" name="reject" value="Reject" class="btn btn-danger btn-lg">
                                                    </div>
                                                    <div class="col-md-12 col-lg-3"></div>
                                                </div>
                                            </div>
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

<script>
