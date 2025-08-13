<?php
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
                    <h4 class="page-title">New Joinee</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <div class='col-md-8'><h4 class="card-title">New Joinee <b style="color:red;">[Total {{count($employees)}}]</b></h4></div>
                            <div class='col-md-4'>
                                {!! Form::open(['action' => 'admin\employees\EmployeesController@newJoinee', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8">
                                            <div class="form-group">
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                                <input type='hidden' value="{{$startDate}}" name="startDate" id="startDate"/>
                                                <input type='hidden' value="{{$endDate}}" name="endDate" id="endDate"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                                            </div>
                                        </div>
                                    </div> 
                                {!! Form::close() !!} 
                            </div>
                        </div>
                        <div class="card-body">
                            
                            @if(isset($employees))
                                @if(count($employees) >= 1)
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="example">
											<thead class="text-white" style="background-color:seagreen;">
                                                <tr>
                                                    <th class="text-white" width="5%">Sr. No.</th>
                                                    <th class="text-white">Employee Name</th>
                                                    <th class="text-white" width="5%">Employee Code</th>
                                                    <th class="text-white">DOJ</th>
                                                    <th class="text-white">Designation</th>
                                                    <th class="text-white">Branch</th>
                                                    <th class="text-white">Salary (Joining Time)</th>
                                                    <th class="text-white">Account No</th>
                                                    <th class="text-white">Bank</th>
                                                    <th class="text-white">Branch</th>
                                                    <th class="text-white">IFSC Code<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$employee->name}}</td>
                                                        <td style="font-size:16px;">
                                                            @if($userType == '61')
                                                                {{$employee->empCode}}
                                                            @else
                                                                @if($employee->firmType == 1)
                                                                    {{$employee->empCode}}
                                                                @elseif($employee->firmType == 2)
                                                                    AFF{{$employee->empCode}}
                                                                @else
                                                                    AFS{{$employee->empCode}}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{date('d-m-Y', strtotime($employee->jobJoingDate))}}</td>
                                                        <td>{{$employee->designationName}}</td>
                                                        <td>{{$employee->branch}}</td>
                                                        <td>{{$employee->salaryScale}}</td>
                                                        <td>{{$employee->bankAccountNo}}</td>
                                                        <td>{{$employee->bankName}}</td>
                                                        <td>{{$employee->branchName}}</td>
                                                        <td>{{$employee->bankIFSCCode}}</td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                        <div class="row" style="margin-top:15px;">
                                            <div class='col-md-10'></div>
                                            <div class='col-md-2'>
                                                <a href="/reports/exportNewJoinee/{{(isset($startDate))?$startDate:0}}/{{(isset($endDate))?$endDate:0}}" class="btn btn-danger mt-2"><span class="icons"><i class="ri-download-2-line"></i></span> Download Excel <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
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
  

