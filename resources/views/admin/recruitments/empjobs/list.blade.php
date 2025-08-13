@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Job Vacancy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Current Opening List</a>
                            <a href="/empJobs/dlist" class="btn btn-primary mr-3">Archive Opening List</a>
                            <a href="/empJobs/create" class="btn btn-primary mr-3">Add Job Vacancy</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Current Opening Vacancy</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($jobs))
                                @if(count($jobs) >= 1)
                                    <div class="table-responsive">
                                        <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-25">Position</th>
                                                    <th class="border-bottom-0">Type</th>
                                                    <th class="border-bottom-0">Posted Date</th>
                                                    <th class="border-bottom-0">Last Date To Apply</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($jobs as $job)
                                                    <tr>
                                                        <th>{{$i++}}</th>
                                                        <td>{{$job->jobPosition}}</td>
                                                        <td>{{$job->jobType}}</td>
                                                        <td>{{date('d-m-Y', strtotime($job->postedDate))}}</td>
                                                        <td>{{date('d-m-Y', strtotime($job->lastDateToApply))}}</td>
                                                        <td>
                                                            <a href="/empJobs/{{$job->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View"></i>
                                                            </a>
                                                            <a href="/empJobs/{{$job->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/empJobs/{{$job->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
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
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
