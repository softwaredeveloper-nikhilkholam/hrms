@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Leave Paid Policy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{route('leavePaymentPolicy.index')}}" class="btn btn-primary mr-3">Active List</a>
                            <a href="{{route('leavePaymentPolicy.dlist')}}" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="{{route('leavePaymentPolicy.create')}}" class="btn btn-success mr-3">Add Leave Paid Policy</a>
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
                            <h4 class="card-title">Leave Paid Policy List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($policies))
                                @if(count($policies) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0">Section</th>
                                                    <th class="border-bottom-0">Date</th>
                                                    <th class="border-bottom-0">Payment Days</th>
                                                    <th class="border-bottom-0">Branch</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($policies as $policy)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$policy->section}}</td>
                                                        <td>{{date('M-Y', strtotime($policy->startMonth))}} To {{date('M-Y', strtotime($policy->startMonth))}}</td>
                                                        <td>{{$policy->paymentDays}}</td>
                                                        <td>{{$policy->branchName}}</td>
                                                        <td>
                                                            <a href="/leavePaymentPolicy/{{$policy->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/leavePaymentPolicy/{{$policy->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                        <a href="/leavePaymentPolicy/1/excel" class="btn btn-primary mr-3">Export</a>
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
