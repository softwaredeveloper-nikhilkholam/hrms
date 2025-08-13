<?php 
use App\Helpers\Utility;
$util = new Utility();
$userType = Auth::user()->userType; 
$userEmpId = Auth::user()->empId; 
$perKmRate = Auth::user()->perKmRate;
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
                    <h4 class="page-title">Application Details List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{ url()->previous() }}" class="btn btn-primary">Back To List</a>
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
                            <h4 class="card-title">Application  Details</h4>
                        </div>
                        <div class="card-body">
                            @if($empDet)
                                <div class="table-responsive">
                                    <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="6"  style="background-color:gray;"><h4 style="color:yellow;margin-top:6px;"><center>
                                                    @if($appType ==4)
                                                        Travelling Allowance Applications
                                                    @elseif($appType ==1)
                                                        AGF Applications
                                                    @elseif($appType ==2)
                                                        Exit Pass Applications
                                                    @else
                                                        Leave Applications
                                                    @endif
                                                </center></h4></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" width="10%"><h5 style="color:black;">Employee Name</h5></th>
                                                <th class="text-center" width="25%"><h5 style="color:green;">{{$empDet->empCode}} - {{$empDet->empName}}</h5></th>
                                                <th class="text-center" width="10%"><h5 style="color:black;">Department</h5></th>
                                                <th class="text-center" width="25%"><h5 style="color:green;">{{$empDet->departmentName}}</h5></th>
                                                <th class="text-center" width="10%"><h5 style="color:black;">Designation</h5></th>
                                                <th class="text-center" width="25%"><h5 style="color:green;">{{$empDet->designationName}}</h5></th>
                                            </tr>
                                            <tr>
                                                <th class=" text-center" width="10%"><h5 style="color:black;">From</h5></th>
                                                <th class=" text-center" width="40%" colspan="2"><h5 style="color:red;">{{date('d-M-Y', strtotime($startDate))}}</h5></th>
                                                <th class=" text-center" width="10%"><h5 style="color:black;">To</h5></th>
                                                <th class=" text-center" width="40%" colspan="2"><h5 style="color:red;">{{date('d-M-Y', strtotime($endDate))}}</h5></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            @endif

                            @if($appType == 1)
                                @if(isset($applications))
                                    @if(count($applications))
                                        <div class="table-responsive">
                                            <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                                <thead>
                                                    <tr>
                                                        <th class="" width="5%">#</th>
                                                        <th class="" width="7%">Date</th>
                                                        <th class="" width="15%">Issue in Brief</th>
                                                        <th>Description</th>
                                                        <th class="" width="12%">Authority</th>
                                                        <th class="" width="10%">Accounts</th>
                                                        <th class="" width="12%">Updated At</th>
                                                        <th class="" width="12%">Updated By</th>
                                                        <th class="" width="10%">Action<?php $i=1;?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@changeStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                        @foreach($applications as $row)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{date('d-M', strtotime($row->startDate))}}</td>
                                                                <td>{{$row->reason}}</td>
                                                                <td>{{$row->description}}</td>
                                                                <td style="color:{{($row->status1 == 0)?'purple':(($row->status1 == 1)?'green':'red')}}"><b>{{($row->status1 == 0)?'Pending':(($row->status1 == 1)?'Approved':'Rejected')}}</b></td>
                                                                <td style="color:{{($row->status == 0)?'purple':(($row->status == 1)?'green':'red')}}"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}</b></td>
                                                                <td>{{date('d-m-Y h:i', strtotime($row->updated_at))}}</td>
                                                                <td>{{$row->updated_by}}</td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="check1" name="option[]" style="height:30px;width:30px;" value="{{$row->id}}">
                                                                    </div>
                                                                    <br><br>
                                                                </td>
                                                            </tr>
                                                        @endforeach   
                                                        <tr>
                                                            <td colspan="3"></td>
                                                            <td colspan="2"><button type="submit" style="width:500px;" name="" class="btn btn-danger btn-lg">Submit</button></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    {!! Form::close() !!}
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <h4 style="color:red;">Record Not Found</h4>
                                    @endif
                                @endif
                            @endif

                            @if($appType == 2)
                                @if(isset($applications))
                                    @if(count($applications))
                                        <div class="table-responsive">
                                            <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                                <thead>
                                                    <tr>
                                                        <th class="" width="5%">#</th>
                                                        <th class="" width="10%">Date</th>
                                                        <th class="" width="20%">Reason</th>
                                                        <th class="" width="25%">Description</th>
                                                        <th class="" width="10%">Time Out</th>
                                                        <th class="" width="10%">Time In</th>
                                                        <th class="" width="10%">Status</th>
                                                        <th class="" width="10%">Action<?php $i=1;?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($applications as $row)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>{{date('d-m-Y', strtotime($row->startDate))}}</td>
                                                            <td>{{$row->reason}}</td>
                                                            <td>{{$row->description}}</td>
                                                            <td>{{$row->timeout}}</td>
                                                            <td>{{($row->timein == '')?'-':$row->timein}}</td>
                                                            <td style="color:{{($row->status == 0)?'orange':(($row->status == 1)?'green':'Red')}};"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}</b></td>
                                                            <td>
                                                                <a href="/empApplications/changeStatus/{{$row->id}}/2" class="btn btn-success btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach   
                                                </tbody>
                                            </table>
                                            @if($userType == '61' || $userType == '51')
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-5"></div>
                                                        <div class="col-md-12 col-lg-3">
                                                            <a href="/reports/{{$empDet->id}}/{{date('Y-m', strtotime($startDate))}}/{{$appType}}/applicationPdfView" class="btn btn-success btn-lg"><img src="{{asset('admin/images/files/file.png')}}" alt="img" class="w-5 h-5 mr-2">&nbsp;&nbsp;Print</a>
                                                        </div>
                                                        <div class="col-md-12 col-lg-4"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <h4 style="color:red;">Record Not Found</h4>
                                    @endif
                                @endif
                            @endif

                            @if($appType == 3)
                                @if(isset($applications))
                                    @if(count($applications))
                                        <div class="table-responsive">
                                            <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                                <thead>
                                                    <tr>
                                                        <th class="" width="5%">#</th>
                                                        <th class="" width="10%">Date</th>
                                                        <th class="" width="15%">Reason</th>
                                                        <th class="" width="20%">Description</th>
                                                        <th class="" width="10%">From</th>
                                                        <th class="" width="10%">To</th>
                                                        <th class="" width="10%">Leave Type</th>
                                                        <th class="" width="10%">Status</th>
                                                        <th class="" width="10%">Action<?php $i=1;?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($applications as $row)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>{{date('d-m-Y', strtotime($row->startDate))}}</td>
                                                            <td>{{$row->reason}}</td>
                                                            <td>{{$row->description}}</td>
                                                            <td>{{date('d-m-Y', strtotime($row->startDate))}}</td>
                                                            <td>{{date('d-m-Y', strtotime($row->endDate))}}</td>
                                                            <td>{{($row->leaveType == '1')?'Full Day Leave':(($row->leaveType == '2')?'Half Day Leave( 1st Half )':'Half Day Leave( 2nd Half )')}}</td>
                                                            <td style="color:{{($row->status == 0)?'orange':(($row->status == 1)?'green':'Red')}};"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}</b></td>
                                                            <td>
                                                                <a href="/empApplications/changeStatus/{{$row->id}}/3" class="btn btn-success btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach   
                                                </tbody>
                                            </table>
                                            @if($userType == '61' || $userType == '51')
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-5"></div>
                                                        <div class="col-md-12 col-lg-3">
                                                            <a href="/reports/{{$empDet->id}}/{{date('Y-m', strtotime($startDate))}}/{{$appType}}/applicationPdfView" class="btn btn-success btn-lg"><img src="{{asset('admin/images/files/file.png')}}" alt="img" class="w-5 h-5 mr-2">&nbsp;&nbsp;Print</a>
                                                        </div>
                                                        <div class="col-md-12 col-lg-4"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <h4 style="color:red;">Record Not Found</h4>
                                    @endif
                                @endif
                            @endif

                            @if($appType == 4)
                                @if(isset($travells))
                                    @if(count($travells) >= 1)
                                        {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@updateApplicatinStatus', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                            <div class="table-responsive">
                                                <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="" width="5%">#</th>
                                                            <th class="" width="5%">Day</th>
                                                            <th class="" width="25%">Reason for Travelling</th>
                                                            <th class="" width="11%">From </th>
                                                            <th class="" width="11%">To</th>
                                                            <th class="" width="5%">Kms<?php $i=1;$totKM=0;$totRs=0; ?></th>
                                                            <th class="" width="8%">Rs.</th>
                                                            <th class="" width="10%">Reporting Auth</th>
                                                            <th class="" width="10%">HR </th>
                                                            <th class="" width="10%">Accounts</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($travells as $row)
                                                            <tr>
                                                                <td>{{$i++}}<?php $km = (double)str_replace("km","",$row->kms); 
                                                                $totKM += $km; ?>
                                                                <input type="hidden" value="{{$row->id}}" name="appId[]">  </td>
                                                                <td>{{date('d', strtotime($row->startDate))}}</td>
                                                                <td>{{$row->reason}}</td>
                                                                <td>{{$row->fromDest}}</td>
                                                                <td>{{$row->toDest}}</td>
                                                                <td>{{$row->kms}}</td>
                                                                <td>{{$amt = $km*$perKmRate}}/-
                                                                    <?php $totRs += $amt;?>
                                                                </td>
                                                                <td>{{Form::select('appRep[]', ['0'=>'Pending','1'=>'Approv', '2'=>'Reject'], $row->appRep, ['class'=>'form-control', 'placeholder'=>'Pick a Option', ($empId == $userEmpId)?'disabled':''])}}</td>
                                                                <td>{{Form::select('appHr[]', ['0'=>'Pending','1'=>'Approv', '2'=>'Reject'], $row->appHr, ['class'=>'form-control', 'placeholder'=>'Pick a Option', ($userType != '51')?'disabled':''])}}</td>
                                                                <td>{{Form::select('appAccount[]', ['0'=>'Pending','1'=>'Approv', '2'=>'Reject'], $row->status, ['class'=>'form-control', 'placeholder'=>'Pick a Option', ($userType != '61')?'disabled':''])}}</td>
                                                            </tr>
                                                        @endforeach   
                                                        <tr>
                                                            <th colspan="5">Total</th>
                                                            <th>{{$util->numberFormatRound($totKM)}}</th>
                                                            <th>{{$util->numberFormatRound($totRs)}}/-</th>
                                                            <th colspan="3"></th>
                                                        </tr>     
                                                    </tbody>
                                                </table>
                                                @if($empId != $userEmpId)
                                                    @if($userType == '61')
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="form-group">
                                                                    <label class="form-label">Remark&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                    <input type="text" class="form-control" name="remark" value="{{(isset($travelAllows))?$travelAllows->remark:''}}" id="empName" placeholder="Payment Remark" required>
                                                                    <input type="hidden" name="totKm"  value="{{$totKM}}" >
                                                                    <input type="hidden" name="totRs"  value="{{$totRs}}" >
                                                                    <input type="hidden" name="empId"  value="{{$empDet->id}}" >
                                                                    <input type="hidden" name="month"  value="{{date('Y-m', strtotime($startDate))}}" >
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="form-label">Status&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                                    {{Form::select('paymentStatus', ['Paid'=>'Paid', 'UnPaid'=>'UnPaid'], (isset($travelAllows))?$travelAllows->paymentStatus:'' , ['placeholder'=>'Pick a Option','class'=>'form-control', 'id'=>'acctTAPayment', 'required'])}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-5"></div>
                                                            <div class="col-md-12 col-lg-3">
                                                                <input type="hidden" value="{{$appType}}" name="appType">
                                                                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                                                @if($userType == '61' || $userType == '51')
                                                                    <a href="/empApplication/taApplication/{{$empDet->id}}/{{date('Y-m', strtotime($startDate))}}/exportPdf" class="btn btn-success btn-lg"><img src="{{asset('admin/images/files/file.png')}}" alt="img" class="w-5 h-5 mr-2">&nbsp;&nbsp;Print</a>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-12 col-lg-4"></div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if($travelAllows)
                                                        <div class="table-responsive">
                                                            <table class="table table-vcenter table-bordered border-top border-bottom">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="3"><center><b style="color:red;">Account Department</b></center></td>
                                                                    </tr>  
                                                                    <tr style="font-size:12px;">
                                                                        <th width="60%">Remark</th>
                                                                        <th width="20%">Updated By</th>
                                                                        <th width="20%">Updated At</th>
                                                                    </tr>   
                                                                    <tr style="font-size:12px;">
                                                                        <td width="60%">{{$travelAllows->remark}}</td>
                                                                        <td width="20%">{{$travelAllows->updated_by}}</td>
                                                                        <td width="20%">{{date('d-m-Y H:i', strtotime($travelAllows->updated_at))}}</td>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        {!! Form::close() !!}
                                    @else
                                        <h4 style="color:red;">Not Found Active Records.</h4>
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
