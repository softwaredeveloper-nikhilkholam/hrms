@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Verification</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/jobApplications/verificationList" class="btn btn-primary mr-3">Selected List</a>
                            <a href="#" class="btn btn-success mr-3">Verified List</a>
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
                            <h4 class="card-title">Verified Employee List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($verifieds))
                                @if(count($verifieds))
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0">Candidate Name</th>
                                                    <th class="border-bottom-0"  width="10%">Section</th>
                                                    <th class="border-bottom-0" width="10%">Contact No</th>
                                                    <th class="border-bottom-0" width="10%">Status<?php $i=1; ?></th>
                                                    <th class="border-bottom-0" width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($verifieds as $row)
                                                    <tr>
                                                        <td>{{date('d-m-Y', strtotime($row->jobJoingDate))}}</td>
                                                        <td>{{$row->name}}</td>
                                                        <td>{{$row->section}}</td>
                                                        <td>{{$row->phoneNo}}</td>
                                                        <td>{{$row->verifyStatus}}</td>
                                                        <td>
                                                            <a href="/jobApplications/showDetails/{{$row->jobAppId}}/2" class="btn btn-danger btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Record Not found...</h4>
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
