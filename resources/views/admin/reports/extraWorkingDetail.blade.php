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
                <h4 class="page-title">Extra Working Day Report</h4>
            </div> 
            <div class="page-rightheader">
                <h4 class="page-title"><a href="{{ url()->previous() }}" class="btn btn-primary">Back to Home</a></h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(isset($extraWorks))
                            @if(count($extraWorks))
                                <div class="row mt-5">
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Emp Code : 
                                                <b style="color:red;">@if($userType != '61')
                                                    {{$emp->empCode}}</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Emp Name : <b style="color:red;">{{$emp->name}}</b></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Branch : <b style="color:red;">{{$emp->branchName}}</b></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Department : <b style="color:red;">{{$emp->departmentName}}</b></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
										<thead  class="bg-success text-white">
                                            <tr>
                                                <th class="text-white border-bottom-0 w-5">Date</th>
                                                <th class="text-white border-bottom-0 w-20">Day</th>
                                                <th class="text-white border-bottom-0">In Time</th>
                                                <th class="text-white border-bottom-0">Out Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($extraWorks as $row)
                                                <tr>
                                                    <td>{{date('d-M-Y', strtotime($row->forDate))}}</td>
                                                    <td>{{date('l', strtotime($row->forDate))}}</td>
                                                    <td>{{($row->inTime != "0")?date('h:i A', strtotime($row->inTime)):"-"}}</td>
                                                    <td>{{($row->outTime != "0")?date('h:i A', strtotime($row->outTime)):"-"}}</td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
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
