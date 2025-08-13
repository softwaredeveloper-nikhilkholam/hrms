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
                    <h4 class="page-title">Appraisal</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/apprisal" class="btn btn-success mr-3">List</a>
                            <a href="#" class="btn btn-primary mr-3">Get Appraisal</a>
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
                            <h4 class="card-title">Update Appraisal</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\AppraisalsController@create', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
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
                                {!! Form::open(['action' => 'admin\AppraisalsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
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
                                    </div>
                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Employee History</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0" width="5%">Sr. No.</th>
                                                            <th class="border-bottom-0">Letter Name</th>
                                                            <th class="border-bottom-0" width="10%">Count</th>
                                                            <th class="border-bottom-0" width="5%">View<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>Offer Letter</td>
                                                            <td>{{$offerLetters}}</td>
                                                            <td>
                                                                <a href="/employeeLetters/list/1" target="_blank" class="btn btn-primary btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>Aggreement</td>
                                                            <td>{{$agreement}}</td>
                                                            <td>
                                                                <a href="/employeeLetters/list/3" target="_blank" class="btn btn-primary btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>Appointment Letter</td>
                                                            <td>{{$appointment}}</td>
                                                            <td>
                                                                <a href="/employeeLetters/list/2" target="_blank" class="btn btn-primary btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>Warning Letter</td>
                                                            <td>{{$warning}}</td>
                                                            <td>
                                                                <a href="/employeeLetters/list/1" target="_blank" class="btn btn-primary btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> 
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">For Authority only</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Contract From Date <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="date" class="form-control" name="contractFromDate" value="{{(isset($empApp))?$empApp->contractFrom:''}}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Contract To Date <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="date" class="form-control" name="contractToDate" value="{{(isset($empApp))?$empApp->contractTo: ''}}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">From Month <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="month" class="form-control" name="fromMonth" value="{{(isset($empApp))?$empApp->month : date('Y-m', strtotime('-1 month'))}}" placeholder="Employee Designation" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Current Salary <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="text" class="form-control" name="oldSalary" value="{{$empDet->salaryScale}}" id="oldSalary" placeholder="Employee Designation" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Hike in Rs <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="text" class="form-control" name="hikeRs" value="{{(isset($empApp))?$empApp->hikeRs : 0.00}}" id="hikeRs" onkeypress="return isNumberKey(event)" placeholder="Hike in Rs" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Percentage <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="text" class="form-control" value="{{(isset($empApp))?$empApp->percentage : 0.00}}" name="percentage" id="percentage" onkeypress="return isNumberKey(event)" placeholder="Employee Designation" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Final Salary <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <input type="text" class="form-control" name="finalSalary" value="{{(isset($empApp))?$empApp->finalRs : 0.00}}" onkeypress="return isNumberKey(event)" id="finalSalary" placeholder="Final Salary" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="form-label">Remark <span class="text-red" style="font-size:22px;">*</span> :</label>
                                                <textarea type="text" class="form-control" name="remarks" placeholder="Remarks" maxlength="499" required>{{(isset($empApp))?$empApp->remarks : 'Appriased Salary for the A. Y. 2025 - 26'}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Signature By<span class="text-red" style="font-size:22px;">*</span> :</label>
                                                {{Form::select('signBy', $signFiles,((isset($empApp))?$empApp->signBy : 4), ['placeholder'=>'Select Signature By','class'=>'form-control', 'id'=>'signBy', 'required'])}}
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <input type="hidden" value="{{$empDet->designationId}}" name="designationId">
                                            <input type="hidden" value="{{$empDet->empCode}}" name="empCode">
                                            <input type="hidden" value="{{$empDet->id}}" name="empId">
                                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                                            <input type="reset" value="Cancel" class="btn btn-danger btn-lg">
                                        </div>  
                                        <div class="col-md-4"></div>
                                    </div>
                                {!! Form::close() !!} 
                                <hr>
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Updated History</h4>

                                @if(isset($appHistories))
                                    @if(count($appHistories))
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
