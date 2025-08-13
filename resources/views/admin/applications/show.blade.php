<?php
    $user = Auth::user();
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
                <div class="page-header d-xl-flex d-block">
                    <div class="page-leftheader">
                        <h4 class="page-title">Applications</h4>
                    </div>
                </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">
                                @if($application->type == 1)
                                    <h4 style="color:red;">AGF Application</h4>
                                @elseif($application->type == 3)
                                    <h4 style="color:red;">Leave Application</h4>
                                @elseif($application->type == 2)
                                    <h4 style="color:red;">Exit Pass Application</h4>
                                @else
                                    <h4 style="color:red;">Travelling Allownces Application</h4>
                                @endif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url()->previous() }}"><i class="fa fa-home" style="font-size:40px;" aria-hidden="true"></i></a></h4>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($application->status == 0)
                                            <h4 style="color:purple;">Application Pending</h4>
                                        @elseif($application->status == 1)
                                            <h4 style="color:green;">Application Approved</h4>
                                        @else
                                            <h4 style="color:red;">Application Rejected ({{$application->rejectReason}})</h4>
                                        @endif
                                        
                                        <input type="hidden" value="{{$application->type}}" id="appType">
                                    </div>
                                </div>
                                <hr>
                                @if($application->type == 1)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" value="{{$application->startDate}}" name="AGFForDate" placeholder="Date" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Issue<span class="text-red">*</span>:</label>
                                                <input type="text" value="{{$application->reason}}" class="form-control" disabled>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'In Time':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                <input type="time" class="form-control"  name="inTime" placeholder="Date" value="{{$application->inTime}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Out Time':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                <input type="time" class="form-control"  name="outTime" placeholder="Date" value="{{$application->outTime}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Day':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                {{Form::select('dayStatus', ['Full Day'=>'Full Day', 'Half Day'=>'Half Day'], $application->dayStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" disabled name="AGFDescription" placeholder="Description">{{$application->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($application->type == 2)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="exitPassForDate" value="{{$application->startDate}}" placeholder="Date" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Reason<span class="text-red">*</span>:</label>
                                                {{Form::select('exitPassIssue', ['Office Work'=>'Office Work', 'Personal Work'=>'Personal Work', 'Other'=>'Other'], $application->reason, ['placeholder'=>'Select a Option', 'class'=>'form-control', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Time Out<span class="text-red">*</span>:</label>
                                                <input type="time" class="form-control" name="exitPassTimeout" value="{{$application->timeout}}" placeholder="Time Out" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="exitPassDescription" placeholder="Description" disabled>{{$application->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($application->type == 3)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Leave Type<span class="text-red">*</span>:</label>
                                                {{Form::select('leaveType', ['1'=>'Full Day Leave', '2'=>'Half Day Leave( 1st Half )', '3'=>'Half Day Leave( 2nd Half )'], $application->leaveType, ['class'=>'leaveType form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">From Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="leaveStartDate" value="{{$application->startDate}}" placeholder="Date" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">To Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="leaveEndDate"  value="{{$application->endDate}}" placeholder="Date" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">I will be reporting my duty on<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="leaveReportingDate"  value="{{$application->reportingDate}}" placeholder="Date" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Reason<span class="text-red">*</span>:</label>
                                                {{Form::select('leaveIssue', ['Test 1'=>'Test 1', 'Test 2'=>'Test 2', 'Other'=>'Other'],  $application->reason, ['placeholder'=>'Select a Option', 'class'=>'form-control', 'disabled'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description" disabled>{{$application->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($application->type == 4)
                                    <div class="row travelApp">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Date':'दिनांक'}}<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="forDate" placeholder="Date" value="{{$application->startDate}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Reason for travelling':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" name="reason" placeholder="Reason for travelling" value="{{$application->reason}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'From':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                {{Form::select('fromDest', $branches, (is_numeric($application->fromDest))?$application->fromDest:'', ['class'=>'form-control', 'placeholder'=>'Other', 'id'=>'fromDest', 'disabled'])}}
                                                <br><input type="text" class="form-control" name="otherFromDest" id="otherFromDest" value="{{(!is_numeric($application->fromDest))?$application->fromDest:''}}" placeholder="Other" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'To':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                {{Form::select('toDest', $branches,  (is_numeric($application->toDest))?$application->toDest:'', ['class'=>'form-control', 'placeholder'=>'Other', 'id'=>'toDest'])}}
                                                <br><input type="text" class="form-control" name="otherToDest" id="otherToDest"  value="{{(!is_numeric($application->toDest))?$application->toDest:''}}" placeholder="other" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Kms travelled':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" name="kms" placeholder="Kms travelled"  value="{{$application->kms}}" readonly>
                                            </div>
                                        </div>
                                    </div>
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
