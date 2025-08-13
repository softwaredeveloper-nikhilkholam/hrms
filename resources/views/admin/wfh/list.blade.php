@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">WFH</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Archive</a>
                            <a href="/hrPolicies/createWFH" class="btn btn-primary mr-3">Add WFH</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">WFH</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($wfhs))
                                @if(count($wfhs) >= 1)
                                    <div class="table-responsive">
                                        <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-10">From Date</th>
                                                    <th class="border-bottom-0 w-20">To Date</th>
                                                    <th class="border-bottom-0 w-10">Type</th>
                                                    <th class="border-bottom-0 w-15">Salary %</th>
                                                    <th class="border-bottom-0 w-10">Status</th>
                                                    <th class="border-bottom-0 w-10">Updated At</th>
                                                    <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($wfhs as $row)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$row->fromDate}}</td>
                                                        <td>{{$row->toDate}}</td>
                                                        <td>{{$row->type}}</td>
                                                        <td>{{$row->percent}}</td>
                                                        <td>{{$row->status}}</td>
                                                        <td>{{date('d-M-Y H:i A', strtotime($row->updated_at))}}</td>
                                                        <td>
                                                            <a href="/hrPolicies/{{$row->id}}/showWFH" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>{{$wfhs->links()}}</div>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Records.</h4>
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
