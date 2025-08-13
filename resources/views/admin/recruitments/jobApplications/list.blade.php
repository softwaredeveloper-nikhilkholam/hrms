<?php
$userType = Auth::user()->userType;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Candidate Application</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Candidate Application List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($startDate))
                                {!! Form::open(['action' => 'admin\recruitments\JobApplicationsController@list', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label">Select Date(s)</label>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                                <input type='hidden' value="{{$startDate}}" name="startDate" id="startDate"/>
                                                <input type='hidden' value="{{$endDate}}" name="endDate" id="endDate"/>
                                            </div>
                                        </div>
                                        <div class="class="col-md-1 col-lg-2">
                                            <div class="form-group mt-5">
                                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!} 
                            @endif
                            <hr>
                            @if(isset($applications))
                                @if(count($applications) >= 1)
                                    <div class="table-responsive">
                                        <table id="example" class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-5">Recruit. Id</th>
                                                    <th class="border-bottom-0 w-25">Name</th>
                                                    <th class="border-bottom-0">Job Position</th>
                                                    <th class="border-bottom-0">Date</th>
                                                    <th class="border-bottom-0">Status</th>
                                                    <th class="border-bottom-0">Round</th>
                                                    <th class="border-bottom-0" width="8%">Updated</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($applications as $app)
                                                    <tr>
                                                        <th>{{$i++}}</th>
                                                        <td>{{$app->id}}</td>
                                                        <td>{{$app->firstName}} {{$app->middleName}} {{$app->lastName}}</td>
                                                        <td>{{$app->jobPosition}}</td>
                                                        <td>{{date('d-m-Y', strtotime($app->created_at))}}</td>
                                                        @if($app->appStatus == '')
                                                            <td style="color:green"><b>Pending</b></td>
                                                        @elseif(($app->appStatus == 'Selected' || $app->appStatus == 'CBC') && $app->round == 1)
                                                            <td style="color:green"><b>{{$app->appStatus}}</b></td>
                                                        @elseif(($app->appStatus == 'Selected' || $app->appStatus == 'CBC') && $app->round == 2)
                                                            <td style="color:green"><b>{{$app->appStatus}}</b></td>
                                                        @elseif($app->appStatus == 'Rejected')
                                                            <td style="color:orange"><b>{{$app->appStatus}}</b></td>
                                                        @else
                                                            <td style="color:orange"><b>{{$app->appStatus}}</b></td>
                                                        @endif
                                                        <td>{{($app->round == 0)?'-':'Assigned '.$app->round}}</td>                                                        
                                                        <td>{{date('d-m-Y H:i', strtotime($app->updated_at))}}<br>{{$app->updated_by}}</td>
                                                        <td>
                                                            <a href="/candidateApplication/show/{{$app->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View"></i>
                                                            </a>
                                                            @if($userType == '51')
                                                                <a href="/candidateApplication/editForm/{{$app->id}}" class="btn btn-warning btn-icon btn-sm">
                                                                    <i class="fa fa-pencil" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                </a>
                                                                <a href="/candidateApplication/{{$app->id}}/1/showToAssign" class="btn {{($app->round1Status == 0)?'btn-danger':(($app->round1Status == 1)?'btn-warning':'btn-success')}}">1st</a>
                                                                @if($app->section == 'Teaching')
                                                                    <a href="/candidateApplication/{{$app->id}}/2/showToAssign" class="btn {{($app->round2Status == 0)?'btn-danger':(($app->round2Status == 1)?'btn-warning':'btn-success')}}">Demo</a>
                                                                @endif
                                                                <a href="/candidateApplication/{{$app->id}}/3/showToAssign" class="btn {{($app->round3Status == 0)?'btn-danger':(($app->round3Status == 1)?'btn-warning':'btn-success')}}">3rd</a>
                                                                <a href="/candidateApplication/{{$app->id}}/4/showToAssign" class="btn {{($app->round4Status == 0)?'btn-danger':(($app->round4Status == 1)?'btn-warning':'btn-success')}}">4th</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                        <div class="row mb-4">
                                            <div class="col-md-8">
                                               
                                            </div>
                                            <div class="col-md-4">
                                                <a href="/jobApplications/printList/{{(isset($startDate))?$startDate:0}}/{{(isset($endDate))?$endDate:0}}" class="btn btn-danger">Print PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h4 style="color:red;">Record not found.</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
@endsection
