<?php
    $userType = Session()->get('userType');
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
                    <h4 class="page-title">Employees</h4>
                </div> 
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employees" class="btn btn-primary mr-3">Active List</a>
                            <a href="#" class="btn btn-success mr-3">In Active List</a>
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
                            <div class='col-md-7'><h4 class="card-title">In Active Employees</h4></div>
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
                                                    <th class="text-white">Branch</th>
                                                    <th class="text-white">Designation</th>
                                                    @if($userType == '51')
                                                        <th class="text-white"  width="10%">Contact No</th>
                                                    @endif
                                                    @if($userType == '81')
                                                        <th  class="text-white" width="10%">Fees Concession</th>
                                                    @endif
                                                    @if($userType != '31')
                                                        <th  class="text-white"  width="10%">Actions<?php $i=1; ?></th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td style="font-size:16px;">
                                                            @if($employee->firmType == 1)
                                                                {{$employee->empCode}}
                                                            @elseif($employee->firmType == 2)
                                                                AFF{{$employee->empCode}}
                                                            @else
                                                                AFS{{$employee->empCode}}
                                                            @endif
                                                        <td>
                                                            <a href="/employees/{{$employee->id}}">
                                                                <div class="d-flex">
                                                                    <div class="mr-3 mt-0 mt-sm-1 d-block">
                                                                        <h6 class="mb-1 fs-14" style="font-size:16px;">{{$employee->name}}</h6>
                                                                        @if($userType == '51')
                                                                            <p class="text-muted mb-0 fs-12">Username:- {{$employee->username}}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </a>
														</td>
                                                        <td>{{$employee->branchName}}</td>
                                                        <td>{{$employee->designationName}}</td>
                                                        @if($userType == '51')
                                                            <td><i class="fa fa-phone" aria-hidden="true"></i> {{$employee->phoneNo}}</td>
                                                        @endif
                                                        @if($userType == '81')
                                                            <td>{{($employee->feeConcession == 1)?'Yes':'No'}}</td>
                                                        @endif
                                                        @if($userType != '31')
                                                            <td>
                                                                <a href="/employees/{{$employee->id}}/edit" class="btn btn-warning btn-icon btn-sm">
                                                                    <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                </a>
                                                                @if($userType == '51')
                                                                    <button class="btn btn-danger btn-icon btn-sm" data-toggle="modal" data-target="#myModal{{$employee->id}}">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true" data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <div class="modal fade" id="myModal{{$employee->id}}" role="dialog">
                                                            <div class="modal-dialog">
                                                            
                                                              <!-- Modal content-->
                                                              <div class="modal-content">
                                                                <div class="modal-header">
                                                                  <h4>Employee Activate</h4>
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
                                                                                    <button type="submit" id="btnSearchPANNO" class="btn btn-primary" style="margin-top:38px;">Deactivate</button>                                            
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
                                @else
                                    <h4 style="color:red;">Not Found Deactive Records.</h4>
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
  

