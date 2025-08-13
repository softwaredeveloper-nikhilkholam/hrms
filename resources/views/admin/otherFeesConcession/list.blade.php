<?php
$authorityStatus = Auth::user()->username;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Fees Concession</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Active List</a>
                            <a href="/employees/dFeesConcession" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/employees/addFeesConcession" class="btn btn-primary mr-3">Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <div class='col-md-7'><h4 class="card-title">Activate Fees Concession</h4></div>
                        </div>
                        <div class="card-body">
                            @if(isset($feesConcessions))
                                @if(count($feesConcessions) >= 1)
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="example">
											<thead class="text-white" style="background-color:seagreen;">
                                                <tr>
                                                    <th class="text-white"  width="5%">Sr. No.</th>
                                                    <th class="text-white">Academic Year</th>
                                                    <th class="text-white">Student Name</th>
                                                    <th class="text-white">Branch</th>
                                                    <th class="text-white">Class-Section</th>
                                                    <th class="text-white">Under Category</th>
                                                    <th class="text-white" width="10%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($feesConcessions as $row)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$row->acadmicYear}}</td>
                                                        <td>{{$row->studentName}}</td>
                                                        <td>{{$row->branchName}}</td>
                                                        <td>{{$row->classSection}}</td>
                                                        <td>{{$row->category}}</td>
                                                        <td><a href="/employees/showFeesConcession/{{$row->id}}" class="btn btn-warning mr-3">View</a>
                                                            @if($row->active == 1)
                                                                <a href="/employees/cancelFeesConcession/{{$row->id}}" class="btn btn-danger mr-3">✘</a>
                                                            @else
                                                                <a href="/employees/cancelFeesConcession/{{$row->id}}" class="btn btn-success mr-3">&#x2714;</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Record not found...</h4>
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
  

