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
                            <a href="/apprisal/create" class="btn btn-primary mr-3">Get Appraisal</a>
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
                            <h4 class="card-title">Appraisal Details</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($empDet))
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Basic Details</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empDet->empCode}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Name&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="empName"  value="{{$empDet->name}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Phone No<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="phoneNo"  value="{{$empDet->phoneNo}}" id="phoneNo" placeholder="Phone No" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Branch &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$empDet->branchName}}" placeholder="Employee Designation" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Department &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$empDet->departmentName}}" placeholder="Employee Designation" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$empDet->designationName}}" placeholder="Employee Designation" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Job Joining Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{($empDet->jobJoingDate == '')?'-':date('d-m-Y', strtotime($empDet->jobJoingDate))}}" id="empName" placeholder="Employee Name" disabled>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">For Authority only</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Contract From Date &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="contractFromDate" value="{{(isset($empApp))?$empApp->contractFrom:date('Y-m-d', strtotime('+1 day', strtotime($empDet->contractEndDate)))}}" placeholder="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Contract To Date &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="contractToDate" value="{{(isset($empApp))?$empApp->contractTo: date('Y-m-d', strtotime('+1 year', strtotime($empDet->contractEndDate)))}}" placeholder="Employee Designation" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">From Month &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="month" class="form-control" name="fromMonth" value="{{(isset($empApp))?$empApp->month : date('Y-m-d', strtotime('+1 month'))}}" placeholder="Employee Designation" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Previous Salary &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="oldSalary" value="{{$empApp->oldSalary}}" id="oldSalary" placeholder="Employee Designation" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Hike in Rs &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="hikeRs" value="{{(isset($empApp))?$empApp->hikeRs : 0.00}}" id="hikeRs" onkeypress="return isNumberKey(event)" placeholder="Hike in Rs" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Percentage &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{(isset($empApp))?$empApp->percentage : 0.00}}" name="percentage" id="percentage" onkeypress="return isNumberKey(event)" placeholder="Employee Designation" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Final Salary&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="finalSalary" value="{{(isset($empApp))?$empApp->finalRs : 0.00}}" onkeypress="return isNumberKey(event)" id="finalSalary" placeholder="Final Salary" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="form-label">Remark&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <textarea type="text" class="form-control" name="remarks" placeholder="Remarks" maxlength="499" readonly>{{(isset($empApp))?$empApp->remarks : ''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Signature By<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('signBy', $signFiles,((isset($empApp))?$empApp->signBy : null), ['placeholder'=>'Select Signature By','class'=>'form-control', 'id'=>'signBy', 'readonly'])}}
                                        </div>
                                    </div> 
                                </div>
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
