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
                <h4 class="page-title">Arrears Report</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@arrearsReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Organisation:</label>
                                        {{Form::select('organisation', $organisations, null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Branches:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Department:</label>
                                        {{Form::select('departmentId', $departments, ((isset($departmentId))?$departmentId:null), ['placeholder'=>'Select Department','class'=>'form-control'])}}
                                    </div>
                                </div>
                                 <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Month<span style="color:red;">*</span>:</label>
                                        <input type="month" class="form-control" name="month" value="{{$month}}">
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($arrears))
                            @if(count($arrears))
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0" id="hr-table">
										<thead  class="bg-success text-white">
                                            <tr>
                                                <th class="text-white border-bottom-0 w-5">No.</th>
                                                <th class="text-white border-bottom-0 w-5">Emp Code</th>
                                                <th class="text-white border-bottom-0 w-15">Name</th>
                                                <th class="text-white border-bottom-0">Branch</th>
                                                <th class="text-white border-bottom-0">Department</th>
                                                <th class="text-white border-bottom-0">Designation<?php $i=1; ?></th>
                                                <th class="text-white border-bottom-0 w-20">Organisation</th>
                                                <th class="text-white border-bottom-0 w-5">HR Count</th>
                                                <th class="text-white border-bottom-0 w-5">Account Count</th>
                                                <th class="text-white border-bottom-0 w-5">Status</th>
                                                <th class="text-white border-bottom-0 w-5">Updated At</th>
                                                <th class="text-white border-bottom-0 w-5">Updated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($arrears as $row)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$row->empCode}}</td>
                                                    <td>{{$row->name}}</td>
                                                    <td>{{$row->branchName}}</td>
                                                    <td>{{$row->departmentName}}</td>
                                                    <td>{{$row->designationName}}</td>
                                                    <td>{{$row->shortName}}</td>
                                                    <td>{{$row->arrearsDays}}</td>
                                                    <td>{{$row->arrearsDaysByAccount}}</td>
                                                    <td>
                                                        @if($row->status == 1)
                                                            <b style="color:purple;">Pending</b>
                                                        @elseif($row->status == 2)
                                                            <b style="color:green;">Closed</b>
                                                        @elseif($row->status == 4)
                                                            <b style="color:orange;">On Hold</b>
                                                        @else
                                                            <b style="color:red;">Rejected</b>
                                                        @endif
                                                    </td>
                                                    <td>{{$row->updated_at}}</td>
                                                    <td>{{$row->updated_by}}</td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
                                </div>
                                <hr>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'></div>
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
