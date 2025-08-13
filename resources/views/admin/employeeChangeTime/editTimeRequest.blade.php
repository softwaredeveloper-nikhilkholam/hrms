<?php $userType = Auth::user()->userType; ?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Change Office Time Request</h4>
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
            <style>
#employeeTable th,
#employeeTable td {
    vertical-align: middle;
}

#selectAll {
    cursor: pointer;
}

#searchInput {
    max-width: 300px;
}


                </style>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Change Office Time</h4>
                        </div>
                        <div class="card-body">
                            @if($row)
                                {!! Form::open(['action' => 'admin\employees\EmployeesController@updateChangeTimeRequest', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Start Date</label>
                                            <h5>{{date('d-m-Y', strtotime($row->fromDate))}}</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">End Date</label>
                                            <h5>{{date('d-m-Y', strtotime($row->toDate))}}</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">End Date</label>
                                            <h5>{{date('d-m-Y', strtotime($row->toDate))}}</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive mt-2">
                                        <table class="table table-striped table-hover align-middle text-nowrap border" id="employeeTable">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Branch</th>
                                                    <th>In Time</th>
                                                    <th>Out Time</th>
                                                    <th>
                                                        <input type="checkbox" id="selectAll" title="Select All" />
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td>{{ $employee->empCode }}</td>
                                                        <td>{{ $employee->name }}</td>
                                                        <td>{{ $employee->designationName }}</td>
                                                        <td>{{ $employee->branchName }}</td> 
                                                        <td>{{ optional($employee->startTime) ? date('H:i', strtotime($employee->startTime)) : '-' }}</td>
                                                        <td>{{ optional($employee->endTime) ? date('H:i', strtotime($employee->endTime)) : '-' }}</td>
                                                        <td>
                                                            <input type="checkbox" name="empIds[]" value="{{ $employee->id }}" class="emp-checkbox">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="startDate" value="{{$startDate}}">
                                            <input type="hidden" name="endDate" value="{{$endDate}}">
                                            <input type="hidden" name="designationId" value="{{$designationId}}">
                                            <input type="hidden" name="branchId" value="{{$branchId}}">
                                            <button type="submit" id="updateTimes" class="btn btn-primary">Update Times</button>
                                            <button type="reset" id="updateTimes" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                {!! Form::close()!!}
                            @else
                                <div class="alert alert-info">No employees found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

