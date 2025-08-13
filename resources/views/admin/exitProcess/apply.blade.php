<?php
    $user = Auth::user();
    $language = $user->language;
    $userType = Auth::user()->userType;
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
                    <h4 class="page-title">Resignation</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Resignation List</a>
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
                                Resignation
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($userType == '51')
                                {!! Form::open(['action' => 'admin\HrPoliciesController@storeResignation', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        {{-- Application Date --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Application Date <span class="text-red">*</span>:</label>
                                                <input type="date" name="forDate" class="form-control" value="{{ old('forDate', date('Y-m-d')) }}" required>
                                            </div>
                                        </div>

                                        {{-- Employee Code --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Employee Code <span class="text-red">*</span>:</label>
                                                <input type="number" name="empCode" id="exitProcessEmpCode" class="form-control" value="{{ old('empCode') }}" placeholder="Employee Code" required>
                                            </div>
                                        </div>

                                        {{-- Employee Name (readonly, populated via JS probably) --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Employee Name</label>
                                                <input type="text" id="empDetails" class="form-control" placeholder="Employee Name" readonly>
                                            </div>
                                        </div>

                                        {{-- Resignation Type --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Resignation Type <span class="text-red">*</span>:</label>
                                                {{ Form::select('processType', ['2'=>'Absconding', '3'=>'Sabbatical', '4'=>'Termination'], old('processType'), ['class'=>'form-control', 'placeholder'=>'Select an Option', 'required']) }}
                                            </div>
                                        </div>

                                        {{-- Last Day As Per Policy --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Last Day As Per Policy <span class="text-red">*</span>:</label>
                                                <input type="date" name="expectedLastDate" class="form-control" value="{{ old('expectedLastDate', date('Y-m-d')) }}" required>
                                            </div>
                                        </div>

                                        {{-- Requested Last Date --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Requested Last Date <span class="text-red">*</span>:</label>
                                                <input type="date" name="reqExitDate" class="form-control" value="{{ old('reqExitDate', date('Y-m-d')) }}" required>
                                            </div>
                                        </div>

                                        {{-- Description --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description <span class="text-red">*</span>:</label>
                                                <textarea id="resignationDescription" name="description" class="form-control" required>{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Submit Buttons --}}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-5"></div>
                                            <div class="col-md-12 col-lg-3">
                                                <button type="submit" class="btn btn-primary btn-lg">Raise</button>
                                                <a href="/empApplications" class="btn btn-danger btn-lg">{{ ($language == 1) ? 'Cancel' : 'कॅन्सल' }}</a>
                                            </div>
                                            <div class="col-md-12 col-lg-4"></div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}

                            @else
                                {!! Form::open(['action' => 'admin\HrPoliciesController@storeResignation', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" value="{{$toDay = date('Y-m-d')}}"  name="forDate" placeholder="Date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Reporting Authority<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" value="{{$user->name}}" placeholder="Reporting Authority" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Last Day As Per Policy<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" value="{{date('Y-m-d', strtotime('+'.$noticeP.' months', strtotime($toDay)))}}"  name="expectedLastDate" placeholder="Date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Requested Last Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" value="{{$toDay = date('Y-m-d')}}"  name="reqExitDate" placeholder="Date" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description<span class="text-red">*</span>:</label>
                                                <textarea id="resignationDescription" class="form-control" name="description" style="height: 100px !important;" rows="5" required>
                                                    {{ old('description') }}
                                                </textarea>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-5"></div>
                                            <div class="col-md-12 col-lg-3">
                                                <input type="hidden" value="1" name="processType">
                                                <input type="hidden" value="{{$employee->empCode}}" name="empCode">
                                                <input type="hidden" value="{{$user->id}}" name="repoId">
                                                <button type="submit" class="btn btn-primary btn-lg">Raise</button>
                                                <a href="/empApplications" class="btn btn-danger btn-lg">{{($language == 1)?'Cancel':'कॅन्सल'}}</a>
                                            </div>
                                            <div class="col-md-12 col-lg-4"></div>
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
