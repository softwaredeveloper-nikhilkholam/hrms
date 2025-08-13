@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Appointment</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Archive List</a>
                            <a href="/appointments/create" class="btn btn-primary mr-3">Get Appointment</a>
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
                            <h4 class="card-title">Archive</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($appointments))
                                @if(count($appointments))
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0"  width="10%">Appointment with</th>
                                                    <th class="border-bottom-0" >Reason</th>
                                                    <th class="border-bottom-0" width="10%">Status</th>
                                                    <th class="border-bottom-0" width="5%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($appointments as $appoint)
                                                    <tr>
                                                        <td>{{date('d-M-Y', strtotime($appoint->forDate))}}</td>
                                                        <td>{{$appoint->name}}</td>
                                                        <td>{{$appoint->reason}}</td>
                                                        @if($appoint->status == "1")
                                                            <td style="color:red;"><b>Pending</b></td>
                                                        @elseif($appoint->status == "2")
                                                            <td style="color:green;"><b>Approved</b></td>
                                                        @elseif($appoint->status == "3")
                                                            <td style="color:orange;"><b>Postpone</b></td>
                                                        @elseif($appoint->status == "4")
                                                            <td style="color:red;"><b>Rejected</b></td>
                                                        @endif
                                                        <td>
                                                            <a href="/appointments/{{$appoint->id}}" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Records not found.</h4>
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
