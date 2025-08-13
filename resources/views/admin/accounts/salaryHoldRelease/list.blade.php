<?php
    use App\Helpers\Utility;
    $util = new Utility();   

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
                    <h4 class="page-title">Employee Advance</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empAdvRs" class="btn btn-primary mr-3">List</a>
                            <a href="/empAdvRs/create" class="btn btn-success mr-3">Add Advance Entry</a>
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
                            @if(isset($empAdvs))
                                @if(count($empAdvs) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="5%">No</th>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0"  width="30%">Emp Code - Name</th>
                                                    <th class="border-bottom-0"  width="10%">Advance Rs</th>
                                                    <th class="border-bottom-0" width="10%">Mgmt. Status</th>
                                                    <th class="border-bottom-0" width="10%">Accounts Status</th>
                                                    <th class="border-bottom-0" width="7%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($empAdvs as $empAdv)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{date('d-m-Y', strtotime($empAdv->created_at))}}</td>
                                                        <td>{{$empAdv->empCode}}-{{$empAdv->empName}}</td>
                                                        <td>{{$util->numberFormatRound($empAdv->advAmount)}}</td>
                                                        <td style="color:{{($empAdv->managementStatus == 0)?'black':(($empAdv->managementStatus == 1)?'green':'red')}};">{{($empAdv->managementStatus == 0)?'Pending':(($empAdv->managementStatus == 1)?'Approved':'Rejected')}}</td>
                                                        <td style="color:{{($empAdv->accountStatus == 0)?'black':(($empAdv->accountStatus == 1)?'green':'red')}};">{{($empAdv->accountStatus == 0)?'Pending':(($empAdv->accountStatus == 1)?'Approved':'Rejected')}}</td>
                                                        <td>
                                                            <a href="/empAdvRs/{{$empAdv->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/empAdvRs/{{$empAdv->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
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
