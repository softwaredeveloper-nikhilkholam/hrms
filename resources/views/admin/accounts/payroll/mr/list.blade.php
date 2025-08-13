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
                    <h4 class="page-title">MR Report</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">MR Report</a>
                            <a href="/accounts/uploadMR" class="btn btn-primary mr-3">Upload MR Sheet</a>
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
                            <h4>MR Report List</h4>
                        </div>
                        <div class="card-body">
                            @if(count($mrs) > 0)
                                <div class="table-responsive">
                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">Month-Year</th>
                                                <th class="border-bottom-0">Status</th>
                                                <th class="border-bottom-0">Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mrs as $mr)
                                                <tr>
                                                    <td>{{date('M-Y', strtotime($mr->month))}}</td>
                                                    <td>Uploaded</td>
                                                    <td>{{date('d-M-Y H:i A', strtotime($mr->updated_at))}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Active Records.</h4>
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
