<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Employee Retention History</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@retentionReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Year:</label>
                                        <select name="" class="form-control">
                                            <option value="">Select Year</option>
                                            @for($i=2015; $i<=date('Y'); $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Month:</label>
                                        <select name="" class="form-control">
                                            <option value="">Select Month</option>
                                            <option value="01">Jan</option>
                                            <option value="02">Feb</option>
                                            <option value="03">Mar</option>
                                            <option value="04">Apr</option>
                                            <option value="05">May</option>
                                            <option value="06">Jun</option>
                                            <option value="07">Jul</option>
                                            <option value="08">Aug</option>
                                            <option value="09">Sept</option>
                                            <option value="10">Oct</option>
                                            <option value="11">Nov</option>
                                            <option value="12">Dec</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Branch:</label>
                                        {{Form::select('branchId', $branches, null, ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Department:</label>
                                        {{Form::select('departmentId', $departments, null, ['placeholder'=>'Select Department','id'=>'departmentId','class'=>'empDepartmentId form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Designation:</label>
                                        {{Form::select('designationId', [], null, ['placeholder'=>'Select Designation','id'=>'designationId','class'=>'empDesignationId form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Employee Code:</label>
                                        <input type="text" value="" name="empCode" placeholder="Enter Employee Code only" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="class="col-md-1 col-lg-2">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div> 
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($histories))
                            @if(count($histories) >= 1)
                                <div class="table-responsive">
                                    <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 w-5">#</th>
                                                <th class="border-bottom-0 w-5">Emp Code</th>
                                                <th class="border-bottom-0">Name</th>
                                                <th class="border-bottom-0 w-10">Month</th>
                                                <th class="border-bottom-0 w-10">Retention Rs<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($histories as $history)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$history->empCode}}</td>
                                                    <td>{{$history->name}}</td>
                                                    <td>{{date('M-Y', strtotime($history->month))}}</td>
                                                    <td>{{$util->numberFormatRound($history->retentionAmount)}}</td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table>
                                    <a href="/reports/exportRetentionReport/{{$branchId}}" class="btn btn-primary mr-3">Export</a>
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Records.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
