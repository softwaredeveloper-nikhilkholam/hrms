<?php
    $user = Auth::user();
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">{{($language == 1)?'Applications': 'अर्ज करणे'}}</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empApplications" class="btn btn-primary mr-3">{{($language == 1)?'Active List': 'ऍक्टिव्ह लिस्ट'}}</a>
                            <a href="#" class="btn btn-success mr-3">{{($language == 1)?'Deactive List': 'डी ऍक्टिव्ह लिस्ट'}}</a>
                            <a href="/empApplications/create" class="btn btn-primary mr-3">{{($language == 1)?'Apply': 'अर्ज करणे'}}</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">{{($language == 1)?'Deactive Applications': 'डी ऍक्टिव्ह अर्ज'}}</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($applications))
                                @if(count($applications) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Application Type': 'एप्लीकेशन टाईप'}}</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Status': 'स्टेटस'}}</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Updated At': 'अपडेटेड टाईम'}}</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Updated By': 'अपडेटेड बाय'}}</th>
                                                    <th class="border-bottom-0 w-15">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($applications as $app)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                        <td>{{(($app->type == 1)?'AGF Application':(($app->type == 2)?'Exit Pass Application':'Leave Application'))}}</td>
                                                        <td style="color:{{($app->status == 0)?'purple':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                            <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                        <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                        <td>{{$app->updated_by}}</td>
                                                        <td>
                                                            <a href="/empApplications/{{$app->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>
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
                                    <h4 style="color:red;">Not Found Deactive Records.</h4>
                                @endif
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
