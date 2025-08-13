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
                    <h4 class="page-title">Salary Hold/Release</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empAttendances/salaryHoldList" class="btn btn-success mr-3">Active List</a>
                            <a href="/empAttendances/salaryHoldDList" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/empAttendances/searchSalaryHold" class="btn btn-primary mr-3">Add</a>
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
                            <h4>Active List</h4>
                        </div>
                        <div class="card-body">
                            @if(count($lists) > 0)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-bottom border-top" id="example">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">No</th>
                                                <th class="border-bottom-0">Month-Year</th> 
                                                <th class="border-bottom-0">Code</th>
                                                <th class="border-bottom-0">Name</th>
                                                <th class="border-bottom-0">Designation</th>
                                                <th class="border-bottom-0">Branch</th>
                                                <th class="border-bottom-0">Remark</th>
                                                <th class="border-bottom-0">Reference</th>
                                                <th class="border-bottom-0">Status</th>
                                                <th class="border-bottom-0">Updated at</th>
                                                <th class="border-bottom-0">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lists as $list)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('M-Y', strtotime($list->forMonth))}}</td>
                                                    <td><a href="/employees/{{$list->empId}}" target="_blank">{{$list->empCode}}</a></td>
                                                    <td>{{$list->name}}</td>
                                                    <td>{{$list->designationName}}</td>
                                                    <td>{{$list->branchName}}</td>
                                                    <td>{{$list->remark}}</td>
                                                    <td>{{$list->referenceBy}}</td>
                                                    <td style="color:<?php echo ($list->status == 0)?'green':'red'; ?>"><b>{{($list->status == 0)?'✓':'✗'}}</b></td>
                                                    <td>{{date('d-M-Y H:i A', strtotime($list->updated_at))}}</td>
                                                    <td>
                                                        <a href="/empAttendances/search?empCode={{$list->empCode}}&organisation=&section=&branchId=&departmentId=&month={{$list->forMonth}}&flag=2" target="_blank" class="btn btn-primary"><i class="fa fa-calendar"></i></a>
                                                        <a href="/empAttendances/{{$list->id}}/editSalaryHoldDetail" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                        <a href="/empAttendances/{{$list->id}}/activeOrDeactiveSalaryStatus" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                    </td>
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
