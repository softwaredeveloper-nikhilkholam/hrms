@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Agreement</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">Employee Agreement</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\FormAndCircularsController@getAgreement', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="empCode" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Search Employee Code..." class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <b style="color:red;">Note: Enter only Emp Code without (AWS, AFF, ADF...)</b>
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>
                            @if(isset($empName))
                                {!! Form::open(['action' => 'admin\FormAndCircularsController@generateAgreement', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empCode}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empName}}" id="empName" placeholder="Employee Code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Agreement Made Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="madeDate"  value="{{date('Y-m-d')}}" id="" placeholder="Agreement Made Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Agreement From&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="month" class="form-control" name="fromYear"  value="{{date('Y-m')}}" id="" placeholder="Agreement From Date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Agreement To&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="month" class="form-control" name="toYear"  value="{{date('Y-m', strtotime('+1 Year'))}}" id="" placeholder="Agreement To Date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Organisation<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('organisation', ['1'=>'Ellora Medicals and Educational foundation', '2'=>'Snayraa Agency', '3'=>'Tejasha Educational and research foundation'], null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation', 'required'])}}
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Appointment Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="appointmentDate"  value="{{date('Y-m-d')}}" id="" placeholder="Appointment Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:10px;">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-6">
                                        <input type="hidden" value="{{$empCode}}" name="employeeCode">
                                        <button type="Submit" class="empAdd btn btn-primary btn-lg"><i class="fa fa-handshake-o" aria-hidden="true"></i> Generate Agreement</button>
                                        <a href="/formsCirculars/getAgreement" class="btn btn-danger btn-lg">Cancel</a>
                                    </div>
                                    <div class="col-md-2"></div>
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
