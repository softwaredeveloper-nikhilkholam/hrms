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
                    <h4 class="page-title">Form 16</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Archive</a>
                            <a href="/empPayroll/raiseReqForm16" class="btn btn-primary mr-3">Raise Request</a>
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
                            <h4>Form 16</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($forms))
                                @if(count($forms) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 w-5">Sr. No.</th>
                                                <th class="border-bottom-0 w-10">Applied Date</th>
                                                <th class="border-bottom-0">Subject</th>
                                                <th class="border-bottom-0 w-10">Status</th>
                                                <th class="border-bottom-0 w-15">Updated At</th>
                                                <th class="border-bottom-0 w-10">Updated By<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($forms as $fm)
                                                    <tr>
                                                        <td class="font-weight-semibold text-right">{{$i++}}</td>
                                                        <td class="font-weight-semibold text-right">{{date('d-M-Y', strtotime($fm->forDate))}}</td>
                                                        <td class="font-weight-semibold">Request For Form16 Of FY {{$fm->caption}}</td>
                                                        <td class="font-weight-semibold">{{($fm->status == 1)?"Pending":(($fm->status == 2)?"Completed":"Rejected")}}</td>
                                                        <td class="font-weight-semibold text-right">{{date('d-M-Y H:i A', strtotime($fm->updated_at))}}</td>
                                                        <td class="font-weight-semibold text-right">{{$fm->updated_by}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>{{$forms->links()}}</div>
                                    </div>
                                @else
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>
                                        <h5 style="color:Red;">Record Not Found</h5>
                                    </div>
                                </div>
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
