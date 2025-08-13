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
                    <h4 class="page-title">{{($language == 1)?'Applications': 'अर्ज करणे'}}</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">{{($language == 1)?'Application List': 'अर्ज यादी'}}</a>
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
                            <h4 class="card-title">
                                @if($type == 1)
                                    {{($language == 1)?'AGF Application':'अर्ज करणे'}}
                                @elseif($type == 2)
                                    {{($language == 2)?'Exit Pass Application':'अर्ज करणे'}}
                                @elseif($type == 3)
                                    {{($language == 1)?'Leave Application':'अर्ज करणे'}}
                                @else
                                    {{($language == 1)?'Travelling Allowance Application':'अर्ज करणे'}}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($type != 4)
                                {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@store', 'method' => 'POST', 'class' => 'form-horizontal' , "onsubmit"=>"myButton.disabled = true; return true;"]) !!}
                                    <input type="hidden" value="{{$type}}" id="appType" name="appType">
                                    @if($type == 1)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Date':'दिनांक'}}<span class="text-red">*</span>:</label>
                                                    @if($common->forMonth == '1')
                                                        @if($common->AGFLastDate <= date('Y-m-d'))
                                                            <input type="date" class="form-control" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('+1 day', strtotime($common->AGFLastDate)))}}"  name="AGFForDate" id="AGFForDate" placeholder="Date">
                                                        @else
                                                            <input type="date" class="form-control" min="{{date('Y-m-01')}}" max="{{date('Y-m-d')}}" name="AGFForDate" id="AGFForDate" placeholder="Date">
                                                        @endif
                                                    @else
                                                        @if($common->AGFLastDate <= date('Y-m-d'))
                                                            <input type="date" class="form-control" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime($common->AGFLastDate))}}"  name="AGFForDate" id="AGFForDate" placeholder="Date">
                                                        @else
                                                            <input type="date" class="form-control" max="{{date('Y-m-d')}}" min="{{date('Y-m-01', strtotime('-1 month'))}}"  name="AGFForDate" id="AGFForDate" placeholder="Date">
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Select Issue':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    {{Form::select('AGFIssue', ['Was working out of office premises'=>'Was working out of office premises', 'Was working in another branch where punching is not registered '=>'Was working in another branch where punching is not registered ','Out for School Event / Workhop'=>'Out for School Event / Workhop','Out for Competitions'=>'Out for Competitions', 'At Outstationed branch'=>'At Outstationed branch', 'Power failure'=>'Power failure','Extra Working on Holiday'=>'Extra Working on Holiday','Others'=>'Others'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'In Time':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    <input type="time" class="form-control"  name="inTime" id="inTime" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Out Time':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    <input type="time" class="form-control" id="outTime"  name="outTime" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Select Day':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    {{Form::select('dayStatus', ['Full Day'=>'Full Day', 'Half Day'=>'Half Day'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Description':'डिस्क्रिप्शन'}}<span class="text-red">*</span>:</label>
                                                    <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="AGFDescription" placeholder="Description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="{{$empCode}}" id="empCode">
                                    @endif

                                    @if($type == 2)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Date':'दिनांक'}}<span class="text-red">*</span>:</label>
                                                    <input type="date" class="form-control" name="exitPassForDate" value="{{date('Y-m-d')}}" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Select Reason':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    {{Form::select('exitPassIssue', ['Office Work'=>'Office Work', 'Personal Work'=>'Personal Work', 'Vehical Exit'=>'Vehical Exit', 'Other'=>'Other'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Time Out':'जाण्याची वेळ'}}<span class="text-red">*</span>:</label>
                                                    <input type="time" class="form-control" name="exitPassTimeout" value="{{date('h:i')}}" placeholder="Time Out">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Description':'डिस्क्रिप्शन'}}<span class="text-red">*</span>:</label>
                                                    <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="exitPassDescription" placeholder="Description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($type == 3)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Select Leave Type':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    {{Form::select('leaveType', ['1'=>'Full Day Leave', '2'=>'Half Day Leave( 1st Half )', '3'=>'Half Day Leave( 2nd Half )'], null, ['class'=>'leaveType form-control', 'placeholder'=>'Select a Option'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'From Date':'पासून'}}<span class="text-red">*</span>:</label>
                                                    <input type="date" class="form-control" name="leaveStartDate" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6 toDate">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'To Date':'च्यापर्यत'}}<span class="text-red">*</span>:</label>
                                                    <input type="date"  class="form-control" name="leaveEndDate" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'I will be reporting my duty on':'परत येण्याची दिनांक'}}<span class="text-red">*</span>:</label>
                                                    <input type="date" class="form-control" name="leaveReportingDate" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Select Reason':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    {{Form::select('leaveIssue', ['Sick Leave'=>'Sick Leave', 'Casual Leave'=>'Casual Leave', 'Other Leave'=>'Other Leave'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Description':'डिस्क्रिप्शन'}}<span class="text-red">*</span>:</label>
                                                    <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-5"></div>
                                            <div class="col-md-12 col-lg-3">
                                                <input type="submit" name="myButton" class="btn btn-primary btn-lg mr-2" value="Save">    
                                                <a href="/empApplications" class="btn btn-danger btn-lg">{{($language == 1)?'Cancel':'कॅन्सल'}}</a>
                                            </div>
                                            <div class="col-md-12 col-lg-4"></div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            @endif

                            @if($type == 4)
                                {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@applyTAllow', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Month<span class="text-red">*</span>:</label>
                                                <input type="month" class="form-control" value="{{date('Y-m')}}" max="{{date('Y-m')}}" name="month" placeholder="Date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary mt-5">Apply For</button>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
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
