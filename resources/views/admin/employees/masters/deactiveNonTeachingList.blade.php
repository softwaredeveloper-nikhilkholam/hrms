<?php 
use App\Helpers\Utility;
$util = new Utility();
$authorityStatus = Auth::user()->username;
$userType = Session()->get('userType'); 
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Non Teaching Employees</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <div class='col-md-7'><h4 class="card-title">Deactive Non Teaching Employees</h4></div>
                        </div>
                        <div class="card-body">
                            @if(isset($employees))
                                @if(count($employees) >= 1)
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="example">
											<thead class="text-white" style="background-color:seagreen;">
                                                <tr>
                                                    <th class="text-white" width="5%">Emp Code</th>
                                                    <th class="text-white">Employee Name</th>
                                                    <th class="text-white">Department</th>
                                                    <th class="text-white">Contact No</th>
                                                    <th class="text-white">Reason<?php $i=1; ?></th>
                                                    @if($authorityStatus == 1)
                                                        <th class="text-white">Added By</th>
                                                        <th class="text-white">Last Updated By</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td>{{$employee->empCode}}</td>
                                                        <td>
                                                            <a href="/employees/{{$employee->id}}">
                                                                <div class="d-flex">
                                                                    <div class="mr-3 mt-0 mt-sm-1 d-block">
                                                                        <h6 class="mb-1 fs-14">{{$employee->name}}</h6>
                                                                        <p class="text-muted mb-0 fs-12">Username:- {{$employee->username}}</p>
                                                                    </div>
                                                                </div>
                                                            </a>
														</td>
                                                        <td>{{$employee->departmentName}}</td>                                                        </td>
                                                        <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp; {{$employee->phoneNo}}</td>
                                                        <td>{{$employee->reason}}</td>          
                                                        @if($authorityStatus == 1)
                                                            <td class="text-white">{{$employee->added_by}}</td>
                                                            <td class="text-white">{{$employee->updated_by}}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    @if(!isset($search))
                                        <div class="row" style="margin-top:15px;">
                                            <div class='col-md-10'></div>
                                            <div class='col-md-2'>
                                                <a href="/employees/exportempExcel/{{(isset($search))?$search:0}}/0/Non Teaching" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Download Excel <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    @endif
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
  

