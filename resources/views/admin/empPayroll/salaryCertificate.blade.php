<?php 
use App\Helpers\Utility;
$util = new Utility();
$empCode = session()->get('empCode');
$salary = Session()->get('salary');
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
                    <h4 class="page-title">Salary Certificate</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Archive</a>
                            <a href="/empPayroll/raiseReqSalaryCertificate" class="btn btn-primary mr-3">Raise Request</a>
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
                            <h4>Salary Certificate</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 w-5">Sr. No.</th>
                                            <th class="border-bottom-0 w-10">Applied Date</th>
                                            <th class="border-bottom-0">Subject</th>
                                            <th class="border-bottom-0 w-5">Download</th>
                                            <th class="border-bottom-0 w-5">Status</th>
                                            <th class="border-bottom-0 w-5">Updated At</th>
                                            <th class="border-bottom-0 w-5">Updated By<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($salaryCerts))
                                            @foreach($salaryCerts as $slip)
                                                <tr>
                                                    <td class="font-weight-semibold text-right">{{$i++}}</td>
                                                    <td class="font-weight-semibold text-right">{{date('d-M-Y', strtotime($slip->forDate))}}</td>
                                                    <td class="font-weight-semibold">Request For Salary Slip Of {{$slip->caption}}</td>
                                                    <td class="font-weight-semibold text-center">
                                                        @if($slip->status == 1)
                                                            Comming Soon....
                                                        @else
                                                            <a href="/empPayroll/downloadSalaryCertificate/{{$slip->id}}" target="_blank"><i class="fa fa-download" style="font-size:25px; color:red;"></i></a>
                                                        @endif
                                                    </td>
                                                    <td class="font-weight-semibold">{{($slip->status == 1)?"Pending":(($slip->status == 2)?"Completed":"Rejected")}}</td>
                                                    <td class="font-weight-semibold text-right">{{date('d-M-Y H:i A', strtotime($slip->updated_at))}}</td>
                                                    <td class="font-weight-semibold text-right">{{$slip->updated_by}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" style="margin-top:15px;">
                                <div class='col-md-8'>{{$salaryCerts->links()}}</div>
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
