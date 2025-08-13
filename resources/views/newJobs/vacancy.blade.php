@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Advance</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Active List</a>
                            <a href="/empAdvRs/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/empAdvRs/create" class="btn btn-primary mr-3">Add Advance Entry</a>
                            
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
                            <h4 class="card-title">Active Advance Entry</h4>
                        </div>
                        <div class="card-body">
                            <section class="section-white" id="pricing">
                                <div class="container">
                                <div class="row">
                                    <div class="col-md-12 text-center padding-top-40">
                                    <h2>Job Vacancy</h2>
                                    </div>
                                    @if(isset($jobs))    
                                    @if(count($jobs) > 0) 
                                        @foreach($jobs as $temp)
                                        <div class="col-md-4">
                                            <div class="price-box">
                                            @if($temp->lastDateToApply == date('Y-m-d'))
                                                <div class="ribbon "><span style="background-color:yellow;color:red;">Last Day</span></div>
                                            @else
                                                <div class="ribbon blue"><span>Latest</span></div>
                                            @endif
                                            <ul class="pricing-list">
                                                <li class="price-title">{{$temp->jobPosition}}</li>
                                                <li class="price-subtitle"></li>
                                                <li class="price-text strong"><i class="bi bi-check-circle-fill blue"></i><strong>Posted Date : {{date('d M Y', strtotime($temp->postedDate))}}</strong></li>
                                                <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Job Type: {{$temp->jobType}}</li>
                                                <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Eligibility: {{$temp->education}}</li>
                                                <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Job Experience: {{$temp->experience}}</li>
                                                <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Vacancy: {{$temp->noOfVacancy}}</li>
                                                <li class="price-tag"><a href="/jobs/showJobDet/{{$temp->id}}" >View & Apply</a></li>
                                            </ul>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                    @endif
                                </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
