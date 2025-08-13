<?php
use App\Helpers\Utility;
$util = new Utility();
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
                    <h4 class="page-title">Salary Hold/Release</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empAttendances/salaryHoldList" class="btn btn-primary mr-3">Active List</a>
                            <a href="/empAttendances/salaryHoldDList" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/empAttendances/searchSalaryHold" class="btn btn-success mr-3">Add</a>
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
                            <h4 class="card-title">Add Entry</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmpAttendancesController@searchSalaryHold', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="empCode" onkeypress="return isNumberKey(event)" placeholder="Search Employee Code..." class="form-control">
                                        <b style="color:red;">Enter Only Number without (AWS, AFF, AFS)</b>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>   
                            @if(isset($empDet))
                                {!! Form::open(['action' => 'admin\EmpAttendancesController@updateSalaryStatus', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Employee Code:</label>
                                                <input type="text" class="form-control" name="empName"  value="{{$empDet->empCode}}" id="empName" placeholder="Employee Name" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Employee Name:</label>
                                                <input type="text" class="form-control" name="empName"  value="{{$empDet->name}}" id="empName" placeholder="Employee Name" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Job Joining Date:</label>
                                                <input type="text" class="form-control" value="{{($empDet->jobJoingDate == '')?'-':date('d-m-Y', strtotime($empDet->jobJoingDate))}}" id="empName" placeholder="Employee Name" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Years of Service</label>
                                                <input type="text" class="form-control" name="service"  value="{{$util->calculateExperience($empDet->jobJoingDate)}}" id="service" placeholder="Phone No" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Branch :</label>
                                                <input type="text" class="form-control" value="{{$empDet->branchName}}" placeholder="Employee Designation" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Department :</label>
                                                <input type="text" class="form-control" value="{{$empDet->departmentName}}" placeholder="Employee Designation" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Designation :</label>
                                                <input type="text" class="form-control" value="{{$empDet->designationName}}" placeholder="Employee Designation" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Last Day :</label>
                                                <input type="date" class="form-control" name="lastDate" value="{{$empDet->lastDate}}" placeholder="Employee Late Date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Status <span style="color:red;">*</span>:</label>
                                                {{Form::select('status', ['1'=>'Hold', '0'=>'Release'], (isset($holdStatus)?$holdStatus->status:''), ['placeholder'=>'Select Status','class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">For Month <span style="color:red;">*</span> :</label>
                                                <input type="month" class="form-control" name="forMonth" value="{{(isset($holdStatus)?date('Y-m', strtotime($holdStatus->forMonth)):date('Y-m'))}}" placeholder="From Month" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Reference By <span style="color:red;">*</span> :</label>
                                                <input type="text" class="form-control" value="{{(isset($holdStatus)?$holdStatus->referenceBy:'')}}" name="referenceBy" placeholder="Reference By" required>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Remark <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <textarea type="text" class="form-control" name="remark" placeholder="Remark" maxlength="499" required>{{(isset($holdStatus))?$holdStatus->remark : ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <input type="hidden" value="{{$empDet->empCode}}" name="empCode">
                                            <input type="hidden" value="{{$empDet->id}}" name="empId">
                                            @if(!$holdStatus)
                                                <input type="submit" value="Save" class="btn btn-primary btn-lg">
                                                <input type="hidden" value="0" name="holdId">
                                            @else    
                                                <input type="submit" value="Update" class="btn btn-primary btn-lg">
                                                <input type="hidden" value="{{$holdStatus->id}}" name="holdId">
                                            @endif
                                            <input type="reset" value="Cancel" class="btn btn-danger btn-lg">
                                        </div>  
                                        <div class="col-md-4"></div>
                                    </div>
                                {!! Form::close() !!} 
                                <hr>
                                

                                @if(isset($appHistories))
                                    @if(count($appHistories))
                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Updated History</h4>
                                        <div class="table-responsive">
                                            <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0" width="5%">No.</th>
                                                        <th class="border-bottom-0" width="10%">Previous Salary</th>
                                                        <th class="border-bottom-0" width="10%">Hike in Rs</th>
                                                        <th class="border-bottom-0" width="10%">Percentage</th>
                                                        <th class="border-bottom-0" width="10%">Final Salary</th>
                                                        <th class="border-bottom-0">Remark</th>
                                                        <th class="border-bottom-0" width="10%">Updated At</th>
                                                        <th class="border-bottom-0" width="10%">Updated By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                                    @foreach($appHistories as $appoint)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>{{$appoint->oldSalary}}</td>
                                                            <td>{{$appoint->hikeRs}}</td>
                                                            <td>{{$appoint->percentage}}</td>
                                                            <td>{{$appoint->finalRs}}</td>
                                                            <td>{{$appoint->remarks}}</td>
                                                            <td>{{date('d-m-Y H:i A', strtotime($appoint->changeAt))}}</td>
                                                            <td>{{$appoint->updated_by}}</td>
                                                        </tr>
                                                    @endforeach                                        
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <h4 style="color:red;">Records not found.</h4>
                                    @endif
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
