<?php 
use App\Helpers\Utility;
$util = new Utility();
$empCode = session()->get('empCode');
$salary = Session()->get('salary');
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
                    <h4 class="page-title">Form 16</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empPayroll/form16" class="btn btn-success mr-3">Archive</a>
                            <a href="#" class="btn btn-primary mr-3">Raise Request</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-2 col-md-2 col-lg-2"></div>
                <div class="col-xl-8 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4>Form 16</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmpAttendancesController@raiseReqForm16', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        {{Form::select('year', [(date('Y',strtotime('-3 years')).'-'.date('Y',strtotime('-2 years')))=>(date('Y',strtotime('-3 years')).'-'.date('Y',strtotime('-2 years'))), (date('Y',strtotime('-2 years')).'-'.date('Y',strtotime('-1 years')))=>(date('Y',strtotime('-2 years')).'-'.date('Y',strtotime('-1 years'))), (date('Y',strtotime('-1 years')).'-'.date('Y'))=>(date('Y',strtotime('-1 years')).'-'.date('Y'))], (isset($year))?$year:'', ['placeholder'=>'Select FY','class'=>'form-control'])}}
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            @if(isset($empDet))
                                <div class="table-responsive mt-5">
                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                        <thead>
                                            <tr>
                                                <th>
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
                                                                <label class="form-label">HR Department & Account Department,</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-lg-3">
                                                            <div class="form-group">
                                                                <label class="form-label">ASCON Head Office</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-lg-3"></div>
                                                        <div class="col-md-6 col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label" style="font-size:18px;"><b style="font-size:20px;"><u>Subject:</b> Request for Form16 of FY {{$year}}</u></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-lg-3"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-lg-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Dear Sir/Madam,</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group">
                                                                <label class="form-label">I am <b>{{$empDet->name}}</b>,employee Id <b>{{$empDet->empCode}}</b>,  
                                                                working as a <b>{{$empDet->desName}}, {{$empDet->deptName}}</b><br> in <b>{{$empDet->branchName}}</b>.</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group">
                                                                <label class="form-label"><u>PAN Card No. <b>{{$empDet->PANNo}}</b></u></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group">
                                                                <label class="form-label">So here I am requesting you to please send me my form 16 copy so that I can file my IT returns.</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-9 col-lg-9"></div>
                                                        <div class="col-md-3 col-lg-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Yours sincerely,</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-9 col-lg-9"></div>
                                                        <div class="col-md-3 col-lg-3">
                                                            <div class="form-group">
                                                                <label class="form-label"><b>{{$empDet->name}}</b></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                {!! Form::open(['action' => 'admin\EmpAttendancesController@updateRaiseReqForm16', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <input type="hidden" value="{{$year}}" name="selectedYear">
                                            <button type="submit" class="btn btn-primary btn-lg">Raised Request</button>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                {!! Form::close() !!} 
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2 col-lg-2"></div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
