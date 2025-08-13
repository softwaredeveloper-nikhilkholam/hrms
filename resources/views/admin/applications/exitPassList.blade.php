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
                <h4 class="page-title">Employee Exit Pass List</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@exitPassList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Month</label>
                                        <input type='month' value="{{date('Y-m', strtotime($month))}}" class="form-control" name="month" id="month"/>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($applications))
                            @if(count($applications) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0" id="example">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-white w-5">#</th>
                                                <th class="text-white">Employee Name</th>
                                                <th class="text-white w-20">Dept. Name</th>
                                                <th class="text-white w-20">Designation Name</th>
                                                <th class="text-white w-10">Pending</th>
                                                <th class="text-white w-10">Total Application</th>
                                                <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $app)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>
                                                            @if($app->firmType == 1)
                                                                {{$app->empCode}}
                                                            @elseif($app->firmType == 2)
                                                                AFF{{$app->empCode}}
                                                            @else
                                                                AFS{{$app->empCode}}
                                                            @endif -
                                                            {{$app->empName}}
                                                    </td>
                                                    <td>{{$app->departmentName}}</td>
                                                    <td>{{$app->designationName}}</td>
                                                    <td>{{$app->pendingCt}}</td>
                                                    <td>{{$app->totalCt}}</td>
                                                    <td>
                                                        <a href="/empApplications/{{$app->empId}}/{{$month}}/2/exitPassShow" class="btn btn-success btn-icon btn-sm">
                                                            <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
                                    <div class="btn-toolbar float-right mt-3">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;&nbsp;Excel</button>
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                              <span class="caret"></span>
                                              <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                              <a class="dropdown-item" href="/empApplications/{{$month}}/1/exportExitPassExcel"><i class="fa fa-file-excel-o" aria-hidden="true" style="color:green;"></i>&nbsp;&nbsp;Summary Report</a>
                                              <a class="dropdown-item" href="/empApplications/{{$month}}/2/exportExitPassExcel"><i class="fa fa-file-excel-o" aria-hidden="true" style="color:green;"></i>&nbsp;&nbsp;Detail Report</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <hr>
                                <h4 style="color:red;">Record not Found.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
