@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Walk in</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Walk in List</a>
                            <a href="/jobApplications/walkinCreate" class="btn btn-primary mr-3">Add Entry</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Walk in List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($applications))
                                @if(count($applications) >= 1)
                                    <div class="table-responsive">
                                        <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-25">Name</th>
                                                    <th class="border-bottom-0">Section</th>
                                                    <th class="border-bottom-0">Department</th>
                                                    <th class="border-bottom-0">Designation</th>
                                                    <th class="border-bottom-0">Date</th>
                                                    <th class="border-bottom-0">Status</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($applications as $app)
                                                    <tr>
                                                        <th>{{$i++}}</th>
                                                        <td>{{$app->firstName}} {{$app->middleName}} {{$app->lastName}}</td>
                                                        <td>{{$app->section}}</td>
                                                        <td>{{$app->departmentName}}</td>
                                                        <td>{{$app->designationName}}</td>
                                                        <td>{{date('d-m-Y', strtotime($app->forDate))}}</td>
                                                        <td>{{$app->appStatus}}</td>
                                                        <td>
                                                            <a href="/jobApplications/walkinShow/{{$app->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View"></i>
                                                            </a>
                                                            <a href="/jobApplications/walkinEdit/{{$app->id}}" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
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
</div>
@endsection
