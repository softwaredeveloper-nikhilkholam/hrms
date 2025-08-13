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
                    <h4 class="page-title">Attendance Confirmed List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empAttendances/finalAttendanceSheet" class="btn btn-primary mr-3">Final Attendance List</a>
                            <a href="/empAttendances/confirmedList" class="btn btn-success mr-3">Confirmed list</a>
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
                        </div>
                        <div class="card-body">
                            @if(isset($attendJobList))
                                @if(count($attendJobList) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="5%">No</th>
                                                    <th class="border-bottom-0" width="10%">Branch</th>
                                                    <th class="border-bottom-0" width="10%">Section</th>
                                                    <th class="border-bottom-0" width="30%">Organisation</th>
                                                    <th class="border-bottom-0" width="10%">Month</th>
                                                    <th class="border-bottom-0" width="10%">Sheet Type</th>
                                                    <th class="border-bottom-0" width="10%">Updated At</th>
                                                    <th class="border-bottom-0" width="10%">Updated By</th>
                                                    <th class="border-bottom-0">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($attendJobList as $row)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$row->branchName}}</td>
                                                        <td>{{$row->section}}</td>
                                                        <td>{{$row->organisation}}</td>
                                                        <td>{{date('M-Y', strtotime($row->fMonth))}}</td>
                                                        <td>{{($row->sheetType == 1)?'Salary':'Arrears'}}</td>
                                                        <td>{{date('d-m-Y H:i', strtotime($row->updated_at))}}</td>
                                                        <td>{{$row->updated_by}}</td>
                                                        <td>
                                                            <a href="#" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found any Record.</h4>
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
