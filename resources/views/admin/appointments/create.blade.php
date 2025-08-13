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
                            <a href="/appointments" class="btn btn-primary mr-3">Archive List</a>
                            <a href="#" class="btn btn-success mr-3">Get Appointment</a>
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
                            <h4 class="card-title">Get Appointment</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\AppointmentsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                                   
                                <div class="row mt-5">
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">To<span class="text-red">*</span></label>
                                            {{Form::select('requestTo', ['3'=>'MD Milind ladge', '4'=>'CEO Pratik Ladge', '5'=>'COO Pranav Ladge'], null, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;',  'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Priority<span class="text-red">*</span></label>
                                            {{Form::select('priority', ['Urgent'=>'Urgent', 'General'=>'General'], null, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;',  'required'])}}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3 col-lg-3"></div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" style="font-size:18px;"><b style="font-size:20px;"><u>Subject:</u></b> 
                                            <input type="text" name="agenda" value="" id="subject" placeholder="Please enter your Agenda" class="form-control" required></label>
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
                                            I <b>{{$empDet->name}}</b>,employee Id <b>{{$empDet->empCode}}</b>,  
                                            working as a <b>{{$empDet->desName}}, {{$empDet->deptName}}</b>at <b>{{($empDet->branchName == '')?'-':$empDet->branchName}}</b>.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                            I am writing to request a meeting with you to discuss <b id="subjectShow">-</b> so, can we meet on <input type="date" name="forDate" min="{{date('Y-m-d')}}" required>. <br>Please tell me and I will adjust accordingly.
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
                                            <label class="form-label"><b>{{$empDet->name}}</b></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Send Request</button>
                                            <a href="/appointments" class="btn btn-danger btn-lg">Cancel</a>
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
