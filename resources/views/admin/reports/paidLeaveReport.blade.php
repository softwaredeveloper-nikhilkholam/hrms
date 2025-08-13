<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Paid Leave Report</h4>
            </div> 
            <div class="page-rightheader">
                <h4 class="page-title"><a href="{{ url()->previous() }}" class="btn btn-primary">Back to Home</a></h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@paidLeaveReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        @csrf
                            <div class="row">
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Section:</label>
                                        {{Form::select('paymentDays', $policies, ((isset($paymentDays))?$paymentDays:null), ['placeholder'=>'Select Policy','class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <input type="submit" class="btn btn-primary" value="Search">
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                        @if(isset($employees))
                            @if(count($employees))
                                <div class="table-responsive">
                                    <table id="example" class="table card-table table-vcenter text-nowrap mb-0">
										<thead  class="bg-success text-white">
                                            <tr>
                                                <th class="text-white border-bottom-0 w-5">No.</th>
                                                <th class="text-white border-bottom-0 w-5">Emp Code</th>
                                                <th class="text-white border-bottom-0">Name</th>
                                                <th class="text-white border-bottom-0 w-15">Job Joing Date</th>
                                                <th class="text-white border-bottom-0 w-15">Department</th>
                                                <th class="text-white border-bottom-0 w-15">Designation</th>
                                                <th class="text-white border-bottom-0 w-15">Branch<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employees as $row)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$row->empCode}}</td>
                                                    <td>{{$row->name}}</td>
                                                    <td>{{date('d-m-Y', strtotime($row->jobJoingDate))}}</td>
                                                    <td>{{$row->departmentName}}</td>
                                                    <td>{{$row->designationName}}</td>
                                                    <td>{{$row->branchName}}</td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
                                    <div class="row mt-2 mt-4">
                                        <div class="col-md-12 col-lg-12 text-right">
                                        <a href="{{ route('reports.exportPaidLeaveReport', [
                                            'joiningMonth' => $joiningMonth,
                                            'endMonth' => $endMonth,
                                            'branchId' => $branchId,
                                            'section' => $section
                                        ]) }}" class="btn btn-success">
                                        <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Export to Excel
                                        </a>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Records.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
