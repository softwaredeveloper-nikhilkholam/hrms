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
                    <h4 class="page-title">In-Active Teaching Employees</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <div class='col-md-7'><h4 class="card-title">In Active Employees</h4></div>
                        </div>
                        <div class="card-body">
                            @if(isset($employees))
                                @if(count($employees) >= 1)
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="example">
											<thead class="text-white" style="background-color:seagreen;">
                                                <tr>
                                                    <th class="text-white"  width="7%">Emp Code</th>
                                                    <th class="text-white">Employee Name</th>
                                                    <th class="text-white">Branch</th>
                                                    <th class="text-white">Designation</th>
                                                    <th class="text-white">Contact No</th>
                                                    @if($authorityStatus == 1)
                                                        <th class="text-white">Added By</th>
                                                        <th class="text-white">Last Updated By</th>
                                                    @endif
                                                    <th class="text-white" width="10%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td class="text-black" style="font-size:16px;">
                                                            {{$employee->empCode}}&nbsp;&nbsp;
                                                            @if($employee->verifyStatus == 1)
                                                                <i class="fa fa-check-circle" style="font-size:28px;color:green;text-align:right;"></i>
                                                            @else
                                                                <i class="fa fa-times-circle" style="font-size:28px;color:red;text-align:right;"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="/employees/{{$employee->id}}">
                                                                <div class="d-flex">
                                                                    <div class="mr-3 mt-0 mt-sm-1 d-block">
                                                                        <h6 class="mb-1 fs-14" style="font-size:16px;">{{$employee->name}}&nbsp;&nbsp;
                                                                        </h6>
                                                                        <p class="text-muted mb-0 fs-12">Username:- {{$employee->username}}</p>
                                                                    </div>
                                                                </div>
                                                            </a>
														</td>
                                                        <td>{{$employee->branchName}}</td>
                                                        <td>{{$employee->designationName}}</td>
                                                        <td><i class="fa fa-phone" aria-hidden="true"></i>{{$employee->phoneNo}}</td>
                                                        @if($authorityStatus == 1)
                                                            <td class="text-white">{{$employee->added_by}}</td>
                                                            <td class="text-white">{{$employee->updated_by}}</td>
                                                        @endif
                                                        <td>
                                                            <a href="/employees/{{$employee->id}}/edit" class="btn btn-warning btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                        </td>
                                                        <div class="modal fade" id="myModal{{$employee->id}}" role="dialog">
                                                            <div class="modal-dialog">
                                                              <div class="modal-content">
                                                                <div class="modal-header">
                                                                  <h4>Employee In-Active</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {!! Form::open(['action' => 'admin\employees\EmployeesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Reason&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                                    <input type="text" class="form-control" id="reason" name="reason" placeholder="Reason" required>
                                                                                </div>
                                                                            </div>       
                                                                            <div class="col-md-2">
                                                                                <div class="form-group">
                                                                                    <input type="hidden" value="{{$employee->id}}" name="id">
                                                                                    <button type="submit" class="btn btn-primary" style="margin-top:38px;">In-Active</button>                                            
                                                                                </div>
                                                                            </div>                                
                                                                        </div>   
                                                                    {!! Form::close() !!} 
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                              </div>
                                                              
                                                            </div>
                                                        </div>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    @if(!isset($search))
                                        <div class="row" style="margin-top:15px;">
                                            <div class='col-md-10'></div>
                                            <div class='col-md-2'>
                                                <a href="/employees/exportempExcel/{{(isset($search))?$search:0}}/2/Teaching" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Download Excel <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
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
  

