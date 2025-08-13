<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Log Time Report</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@logTimeReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Employee Code:</label>
                                        <input type="text" class="form-control" placeholder="Employee Code" value="{{(isset($empCode))?$empCode:''}}" name="empCode">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Branches:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Month:</label>
                                        <input type="month" class="form-control" value="{{(isset($forMonth))?$forMonth:date('Y-m')}}" name="forMonth" required>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <input type="hidden" value="2" name="flag">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($logList))
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Code</th>
                                            <th>Date Time</th>
                                            <th>Device Name<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logList as $log)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$log->EmployeeCode}}</td>
                                                <td>{{date('d-m-Y H:i:s', strtotime($log->LogDateTime))}}</td>
                                                <td>{{$log->DeviceSerialNumber}}</td>
                                            </tr>  
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
