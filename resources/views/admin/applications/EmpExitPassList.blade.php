<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = Auth::user()->language; 
    $transAllowed = Auth::user()->transAllowed; 

?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Exit Pass List</h4>
            </div> 
            <div class="page-rightheader">
                <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/applyApplication/2">Apply Exit Pass</a></h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@empExitPassList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
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
                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example2">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-white w-5">#</th>
                                                <th class="text-white" width="10%">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                <th class="text-white">{{($language == 1)?'Reason': 'दिनांक'}}</th>
                                                <th class="text-white" width="10%">{{($language == 1)?'Time Out': 'दिनांक'}}</th>
                                                <th class="text-white" width="10%">{{($language == 1)?'Status': 'स्टेटस'}}</th>
                                                <th class="text-white" width="10%">{{($language == 1)?'Updated At': 'अपडेटेड टाईम'}}</th>
                                                <th class="text-white" width="10%">{{($language == 1)?'Updated By': 'अपडेटेड बाय'}}</th>
                                                <th class="text-white " width="5%">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $app)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                    <td>{{$app->reason}}</td>
                                                    <td>{{date('H:i A', strtotime($app->timeout))}}</td>
                                                    <td style="color:{{($app->status == 0)?'purple':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                        <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                    <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                    <td>{{$app->updated_by}}</td>
                                                    <td>
                                                        @if($app->status == 0)
                                                        <a href="/empApplications/{{$app->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                            <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                        </a>
                                                        <a href="/empApplications/{{$app->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                        </a>
                                                        @endif
                                                        <a href="/empApplications/{{$app->id}}" class="btn btn-success btn-icon btn-sm">
                                                            <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Active Records.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
