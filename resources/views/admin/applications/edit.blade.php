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
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empApplications" class="btn btn-primary mr-3">Active List</a>
                            <a href="/empApplications/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/empApplications/create" class="btn btn-success mr-3">Apply</a>
                            
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
                            <h4 class="card-title">Apply</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => ['admin\employees\EmpApplicationsController@update', $application->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($application->type == 1)
                                            <h4 style="color:red;">AGF Application</h4>
                                        @elseif($application->type == 3)
                                            <h4 style="color:red;">Leave Application</h4>
                                        @else
                                            <h4 style="color:red;">Exit Pass Application</h4>
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
                                                <input type="date" class="form-control" value="{{$application->startDate}}" name="AGFForDate" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Issue<span class="text-red">*</span>:</label>
                                                {{Form::select('AGFIssue', ['Test 1'=>'Test 1', 'Test 2'=>'Test 2', 'Other'=>'Other'], $application->reason, ['placeholder'=>'Select a Option', 'class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="AGFDescription" placeholder="Description">{{$application->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($application->type == 2)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="exitPassForDate" value="{{$application->startDate}}" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Reason<span class="text-red">*</span>:</label>
                                                {{Form::select('exitPassIssue', ['Test 1'=>'Test 1', 'Test 2'=>'Test 2', 'Other'=>'Other'], $application->reason, ['placeholder'=>'Select a Option', 'class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Time Out<span class="text-red">*</span>:</label>
                                                <input type="time" class="form-control" name="exitPassTimeout" value="{{$application->timeout}}" placeholder="Time Out">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="exitPassDescription" placeholder="Description">{{$application->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($application->type == 3)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Leave Type<span class="text-red">*</span>:</label>
                                                {{Form::select('leaveType', ['1'=>'Full Day Leave', '2'=>'Half Day Leave( 1st Half )', '3'=>'Half Day Leave( 2nd Half )'], $application->leaveType, ['class'=>'leaveType form-control', 'placeholder'=>'Select a Option'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">From Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="leaveStartDate" value="{{$application->startDate}}" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6 toDate">
                                            <div class="form-group">
                                                <label class="form-label">To Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="leaveEndDate"  value="{{$application->endDate}}" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">I will be reporting my duty on<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="leaveReportingDate"  value="{{$application->reportingDate}}" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Reason<span class="text-red">*</span>:</label>
                                                {{Form::select('leaveIssue', ['Sick Leave'=>'Sick Leave', 'Casual Leave'=>'Casual Leave', 'Other Leave'=>'Other Leave'], $application->reason, ['class'=>'form-control', 'placeholder'=>'Select a Option'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description">{{$application->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($application->type == 4)
                                    <div class="row travelApp">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Date':'दिनांक'}}<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="forDate" placeholder="Date" value="{{$application->startDate}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Reason for travelling':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" name="reason" placeholder="Reason for travelling" value="{{$application->reason}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'From':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                {{Form::select('fromDest', $branches, (is_numeric($application->fromDest))?$application->fromDest:'', ['class'=>'form-control', 'placeholder'=>'Other', 'id'=>'fromDest'])}}
                                                <br><input type="text" class="form-control" name="otherFromDest" id="otherFromDest" value="{{(!is_numeric($application->fromDest))?$application->fromDest:''}}" placeholder="Other">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'To':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                {{Form::select('toDest', $branches,  (is_numeric($application->toDest))?$application->toDest:'', ['class'=>'form-control', 'placeholder'=>'Other', 'id'=>'toDest'])}}
                                                <br><input type="text" class="form-control" name="otherToDest" id="otherToDest"  value="{{(!is_numeric($application->toDest))?$application->toDest:''}}" placeholder="other">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{($language == 1)?'Kms travelled':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" name="kms" placeholder="Kms travelled"  value="{{$application->kms}}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <input type="hidden" value="{{$application->type}}" name="appType">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                            <a href="/empApplications" class="btn btn-danger btn-lg">Cancel</a>
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
