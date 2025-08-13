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
                            {!! Form::open(['action' => 'admin\employees\EmployeesController@changeTimeRequest', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">From Date<span class="required-asterisk">*</span> :</label>
                                            <input type="date" class="form-control" name="startDate"  max="{{date('Y-m-t')}}" value="{{$startDate}}" id="startDate" placeholder="From Date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">End Date<span class="required-asterisk">*</span> :</label>
                                            <input type="date" class="form-control" name="endDate" value="{{$endDate}}" id="endDate" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation :</label>
                                            {{Form::select('designationId', $designations, $designationId, ['placeholder'=>'Pick a Option','class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <div class="form-group">
                                            <label class="form-label">Branch :</label>
                                            {{Form::select('branchId', $branches, $branchId, ['placeholder'=>'Pick a Option','class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-danger">Search</button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}   
                            <hr>
                            @if($employees && count($employees) > 0 && ($startDate && $endDate))
                                {!! Form::open(['action' => 'admin\employees\EmployeesController@updateChangeTimeRequest', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" id="startDate" name="startDate" value="{{$startDate}}" class="form-control startDate" placeholder="Search employees..." required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">End Date</label>
                                            <input type="date" id="endDate" name="endDate" value="{{$endDate}}" class="form-control endDate" placeholder="Search employees..." required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mt-2 text-center">Change In Master also</label>
                                            <input type="checkbox" name="masters" id="masters" class="form-control remark" placeholder="Remark...">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-2">
                                            <label class="form-label">In Time</label>
                                            <input type="time" id="startTime" name="startTime" class="form-control startTime" placeholder="Search employees..." required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Out Time</label>
                                            <input type="time" id="endTime" name="endTime" class="form-control endTime" placeholder="Search employees..." required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Remark</label>
                                            <input type="text" name="remarks" id="remark" class="form-control remark" placeholder="Remark...">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <label class="form-label">Search</label>
                                            <input type="text" id="searchInput" class="form-control searchInput" placeholder="Search employees...">
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

