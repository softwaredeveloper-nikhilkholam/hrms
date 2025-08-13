<?php
 $user = Auth::user();
 $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Job Vacancy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empJobs" class="btn btn-primary mr-3">Current Opening List</a>
                            <a href="/empJobs/dlist" class="btn btn-primary mr-3">Archive Opening List</a>
                            <a href="/empJobs/create" class="btn btn-success mr-3">Add Job Vacancy</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xl-3 col-md-12">
                            <div class="card">
                                <div class="card-header  border-0">
                                    <h4 class="card-title">Overview</h4>
                                </div>
                                <div class="card-body pb-0 pt-3">
                                    <div class="mt-3">
                                        <label class="form-label mb-0">Experiences:</label>
                                        <p class="text-muted">{{$empJob->experience}}</p>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label mb-0">vacancy:</label>
                                        <p class="text-muted">{{$empJob->noOfVacancy}}</p>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label mb-0">Job Type:</label>
                                        <p class="text-muted">{{$empJob->jobType}}</p>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label mb-0">Posted Date:</label>
                                        <p class="text-muted">{{date('d M Y', strtotime($empJob->postedDate))}}</p>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label mb-0">Last Date To Apply:</label>
                                        <p class="text-muted">{{date('d M Y', strtotime($empJob->lastDateToApply))}}</p>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0">
                                    <div class="btn-list">
                                        <a href="job-apply.html" class="btn btn-primary"><i class="feather feather-check my-auto mr-2"></i>Apply Now</a>
                                        <a href="#" class="btn btn-outline-primary"><i class="feather feather-phone-call  my-auto mr-2"></i>Contact Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-5">
                                        <a class="text-dark" href="#">
                                        <h3 class="mb-2">{{$empJob->jobPosition}}</h3></a>
                                        <div class="d-flex">
                                            <ul class="mb-0 d-md-flex">
                                                <li class="mr-5">
                                                    <a class="icons" href="#"><i class="si si-briefcase text-muted mr-1"></i>{{$empJob->branchName}}</a>
                                                </li>
                                                <li class="mr-5" data-placement="top" data-toggle="tooltip" title="Views">
                                                    <a class="icons" href="#"><i class="si si-eye text-muted mr-1"></i> 765</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clearfix"></div>
                                        <div class="d-flex">
                                            <ul class="mb-0 d-md-flex">
                                                <li class="mr-5">
                                                    <a class="icons" href="#"><i class="si si-location-pin text-muted mr-1"></i>{{$empJob->address}}</a>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                    @if(isset($jobDesc))
                                        <h5 class="mb-4 font-weight-semibold">Description</h5>
                                        <ul class="list-style-disc mb-5">
                                            @foreach($jobDesc as $desc)
                                                <li>{{$desc->description}}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <h5 class="mb-3 mt-5 font-weight-semibold">Job Details</h5>
                                    <div class="table-responsive">
                                        <table class="table row table-borderless w-100 m-0 text-nowrap">
                                            <tbody class="col-lg-12 col-xl-6 p-0">
                                                <tr>
                                                    <td><span class="font-weight-semibold">Job Role :</span> {{$empJob->designationName}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="font-weight-semibold">Min Salary :</span> Rs. {{$empJob->salaryFrom}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="font-weight-semibold">Max Salary :</span> Rs. {{$empJob->salaryTo}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="font-weight-semibold">Job skill :</span> {{$empJob->skill}}</td>
                                                </tr>
                                            </tbody>
                                            <tbody class="col-lg-12 col-xl-6 p-0">
                                                <tr>
                                                    <td><span class="font-weight-semibold">Job Experience :</span> {{$empJob->experience}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="font-weight-semibold">Languages :</span> {{$empJob->language}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="font-weight-semibold">Eligibility :</span> {{$empJob->education}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="list-id">
                                        <div class="row">
                                            <div class="col">
                                                <a class="mb-0">
                                                    <div class="icons">
                                                        <a class="btn btn-primary icons" data-target="#apply" data-toggle="modal" href="#"><i class="si si-check mr-1"></i>Apply Now</a>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col col-auto">
                                                Posted By <a class="mb-0 font-weight-semibold">Office</a> / {{date('d M Y', strtotime($empJob->postedDate))}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
