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
                <h4 class="page-title">Candidate Bank</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@recruitementReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Application Type:</label>
                                        {{Form::select('applicationType', ['0'=>'Candidate Application','1'=>'Walk-in','2'=>'Interview Drive','3'=>'Recruitment Id'], (isset($applicationType))?$applicationType:'null', ['placeholder'=>'Select Application Type','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Status:</label>
                                        {{Form::select('status', ['Selected'=>'Selected','CBC'=>'CBC','Rejected'=>'Rejected'], (isset($status))?$status:'null', ['placeholder'=>'Select Status','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Date(s)</label>
                                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                        <input type='hidden' value="{{$fromDate}}" name="fromDate" id="startDate"/>
                                        <input type='hidden' value="{{$toDate}}" name="toDate" id="endDate"/>
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
                        @if(isset($applications))
                            @if(count($applications) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 w-5">#</th>
                                                <th class="border-bottom-0 w-5">Candidate Id</th>
                                                <th class="border-bottom-0 w-10">Date</th>
                                                <th class="border-bottom-0 w-25">Candidate Name</th>
                                                <th class="border-bottom-0 w-15">Department</th>
                                                <th class="border-bottom-0 w-15">Designation</th>
                                                <th class="border-bottom-0 w-5">Status</th>
                                                <th class="border-bottom-0 w-10">Updated At</th>
                                                <th class="border-bottom-0 w-10">Updated By</th>
                                                <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $app)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$app->id}}</td>
                                                    <td>{{date('d-m-Y', strtotime($app->created_at))}}</td>
                                                    <td>{{$app->firstName}} {{$app->middleName}} {{$app->lastName}}</td>
                                                    <td>{{$app->departmentName}}</td>
                                                    <td>{{$app->designationName}}</td>
                                                    <td>{{($app->appStatus == '')?'Pending':$app->appStatus}}</td>
                                                    <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                    <td>{{$app->updated_by}}</td>
                                                    @if($app->appType == 0)
                                                        <td><a href="/candidateApplication/show/{{$app->id}}" class="btn btn-primary">Show</a></td>
                                                    @endif
                                                    @if($app->appType == 1)
                                                        <td><a href="/jobApplications/walkinShow/{{$app->id}}" class="btn btn-primary">Show</a></td>
                                                    @endif
                                                    @if($app->appType == 2)
                                                        <td><a href="/jobApplications/interviewDShow/{{$app->id}}" class="btn btn-primary">Show</a></td>
                                                    @endif
                                                    @if($app->appType == 3)
                                                        <td><a href="/jobApplications/interviewDShow/{{$app->id}}" class="btn btn-primary">Show</a></td>
                                                    @endif
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$applications->links()}}</div>
                                    <div class='col-md-4'>
                                        <a href="/reports/recruitementReport/0/{{$applicationType}}/{{($status == '')?0:$status}}/{{$fromDate}}/{{$toDate}}/excelRecruitementReport" class="btn btn-primary">Download Excel</a>
                                    </a>
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
