<?php 
use App\Designation;
use App\ContactusLandPage;
<?php $userType = Auth::user()->userType; ?>
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Change Time Request List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            @if($userType == '51')
                                <a href="/employees/changeTimeRequestList" class="btn btn-primary mr-3">Pending Request</a>
                                <a href="/employees/changeTimeRequestListCompleted" class="btn btn-success mr-3">Completed Request List</a>
                            @else
                                <a href="/employees/changeTimeRequest" class="btn btn-primary mr-3">Raise Request</a>
                                <a href="/employees/changeTimeRequestList" class="btn btn-primary mr-3">Pending Request</a>
                                <a href="/employees/changeTimeRequestListCompleted" class="btn btn-success mr-3">Completed Request List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Change Office Time</h4>
                        </div>
                        <div class="card-body">
                            @if($requestList)
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr></tr>
                                                <th width="5%" class="border-bottom-0">No.</th>
                                                <th width="10%" class="border-bottom-0">Start Date</th>
                                                <th width="10%" class="border-bottom-0">End Date</th>
                                                <th width="10%" class="border-bottom-0">Designation</th>
                                                <th width="10%" class="border-bottom-0">Branch</th>
                                                <th width="5%" class="border-bottom-0">In Time</th>
                                                <th width="5%" class="border-bottom-0">Out Time</th>
                                                <th class="border-bottom-0">Remark</th>
                                                <th width="7%" class="border-bottom-0">Status</th>
                                                <th width="5%" class="border-bottom-0">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requestList as $list)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('d-m-Y', strtotime($list->fromDate))}}</td>
                                                    <td>{{date('d-m-Y', strtotime($list->toDate))}}</td>
                                                    <td>{{($list->designationId != '')?Designation::where('id',$list->designationId)->value('name'):'NA'}}</td>
                                                    <td>{{($list->branchId != '')?ContactusLandPage::where('id', $list->branchId)->value('branchName'):'NA'}}</td>
                                                    <td>{{date('H:i', strtotime($list->fromTime))}}</td>
                                                    <td>{{date('H:i', strtotime($list->toTime))}}</td>
                                                    <td>{{$list->remarks}}</td>
                                                    <td>
                                                        @if($list->status == 2)
                                                            <span class="badge badge-success">Pending</span>
                                                        @elseif($list->status == 0)
                                                            <span class="badge badge-danger">Approved</span>
                                                        @else
                                                            <span class="badge badge-warning">Completed</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="/employees/editTimeChangeRequest/{{$list->id}}" class="btn btn-primary btn-sm">Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> 
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
