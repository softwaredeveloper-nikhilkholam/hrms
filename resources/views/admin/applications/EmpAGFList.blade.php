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
                <h4 class="page-title">AGF List</h4>
            </div> 
            <div class="page-rightheader">
                <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/applyApplication/1">Apply AGF</a></h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@empAGFList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
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
                                    <table width="100%" class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-white w-7">#</th>
                                                <th class="text-white w-7">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                <th class="text-white w-12">{{($language == 1)?'Issue In Brief': 'एप्लीकेशन टाईप'}}</th>
                                                <th class="text-white w-10">{{($language == 1)?'Reporting Auth.': 'स्टेटस'}}</th>
                                                <th class="text-white w-10">{{($language == 1)?'HR Dept.': 'स्टेटस'}}</th>
                                                <th class="text-white w-10">{{($language == 1)?'Accounts Dept.': 'स्टेटस'}}</th>
                                                <th class="text-white w-7">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $app)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                    <td>{{$app->reason}}</td>
                                                    <td style="color:{{($app->status1 == 0)?'purple':(($app->status1 == 1)?'green':'red')}}"><b>{{($app->status1 == 0)?'Pending':(($app->status1 == 1)?'Approved':'Rejected')}}
                                                        <br>{{($app->updatedAt1 == '')?'-':(date('d-M h:i A', strtotime($app->updatedAt1)))}}
                                                        <br>{{($app->approvedBy1 == '')?'':$app->approvedBy1}}<br>
                                                    </b></td>
                                                    <td style="color:{{($app->status2 == 0)?'purple':(($app->status2 == 1)?'green':'red')}}"><b>{{($app->status2 == 0)?'Pending':(($app->status2 == 1)?'Approved':'Rejected')}}
                                                        <br>{{($app->updatedAt2 == '')?'-':(date('d-M h:i A', strtotime($app->updatedAt2)))}}
                                                        <br>{{($app->approvedBy2 == '')?'':$app->approvedBy2}}<br>
                                                    </b></td>
                                                    <td style="color:{{($app->status == 0)?'purple':(($app->status == 1)?'green':'red')}}"><b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':'Rejected')}}
                                                        <br>{{($app->updatedAt3 == '')?'-':(date('d-M h:i A', strtotime($app->updatedAt3)))}}
                                                        <br>{{($app->approvedBy == '')?'':$app->approvedBy}}<br>
                                                    </b></td>
                                                    <td>
                                                        @if($app->status1 == 0)
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
