@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Change Office Time</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employees/changeTimeList" class="btn btn-primary mr-3">List</a>
                            <a href="/employees/changeTime" class="btn btn-success mr-3">Update Time</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Changed Time List</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmployeesController@changeTimeList', 'method' => 'GET', 'class' => 'form-horizontal' , "onsubmit"=>"myButton.disabled = true; return true;"]) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="{{($startDate != '')?$startDate:date('Y-m-01', strtotime('-1 month'))}}"  name="startDate" id="startDate" placeholder="Date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="{{($endDate != '')?$endDate:date('Y-m-t', strtotime('-1 month'))}}"  name="endDate" id="endDate" placeholder="Date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            @if(isset($changeTimes))
                                @if(count($changeTimes) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0">Emp Code</th>
                                                    <th class="border-bottom-0">Branch</th>
                                                    <th class="border-bottom-0">Department</th>
                                                    <th class="border-bottom-0">Designation</th>
                                                    <th class="border-bottom-0">From Date</th>
                                                    <th class="border-bottom-0">To Date</th>
                                                    <th class="border-bottom-0">In Time</th>
                                                    <th class="border-bottom-0">Out Time</th>
                                                    <th class="border-bottom-0">Updated By</th>
                                                    <th class="border-bottom-0">Updated Ats<?php $i=1; ?></th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($changeTimes as $row)
                                                    @if($row->status == 0)
                                                        <tr style="color:red;">
                                                            <td style="color:red;"><b>{{$i++}}</th>
                                                            <td style="color:red;"><b>{{$row->empCode}} - {{$row->name}}</b></td>
                                                            <td style="color:red;"><b>{{$row->branchName}}</b></td>
                                                            <td style="color:red;"><b>{{$row->departmentNamr}}</b></td>
                                                            <td style="color:red;"><b>{{$row->designationName}}</b></td>
                                                            <td style="color:red;"><b>{{date('d-m-Y', strtotime($row->startDate))}}</b></td>
                                                            <td style="color:red;"><b>{{date('d-m-Y', strtotime($row->endDate))}}</b></td>
                                                            <td style="color:red;"><b>{{date('H:i', strtotime($row->inTime))}}</b></td>
                                                            <td style="color:red;"><b>{{date('H:i', strtotime($row->outTime))}}</b></td>
                                                            <td style="color:red;"><b>{{$row->updated_by}}</b></td>
                                                            <td style="color:red;"><b>{{date('d-M-Y H:i', strtotime($row->created_at))}}</b></td>
                                                        </tr>
                                                    @else
                                                        <tr style="color:green;">
                                                            <td style="color:green;"><b>{{$i++}}</th>
                                                            <td style="color:green;"><b>{{$row->empCode}} - {{$row->name}}</b></td>
                                                            <td style="color:green;"><b>{{$row->branchName}}</b></td>
                                                            <td style="color:green;"><b>{{$row->departmentName}}</b></td>
                                                            <td style="color:green;"><b>{{$row->designationName}}</b></td>
                                                            <td style="color:green;"><b>{{date('d-m-Y', strtotime($row->startDate))}}</b></td>
                                                            <td style="color:green;"><b>{{date('d-m-Y', strtotime($row->endDate))}}</b></td>
                                                            <td style="color:green;"><b>{{date('H:i', strtotime($row->inTime))}}</b></td>
                                                            <td style="color:green;"><b>{{date('H:i', strtotime($row->outTime))}}</b></td>
                                                            <td style="color:green;"><b>{{$row->updated_by}}</b></td>
                                                            <td style="color:green;"><b>{{date('d-M-Y H:i', strtotime($row->created_at))}}</b></td>
                                                        </tr>
                                                    @endif
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Active Records.</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
