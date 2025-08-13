<?php 
use App\Helpers\Utility;
$util = new Utility();
$logEmpId = Auth::user()->empId; 
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
                    <h4 class="page-title">Travelling Allowance Details List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            @if($userType == '51')
                                <a href="/empApplications/AGFList?month={{date('Y-m', strtotime($forDate))}}" class="btn btn-primary">Back To List</a>
                            @else    
                                <a href="{{ url()->previous() }}" class="btn btn-primary">Back To List</a>
                            @endif
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
                            <h4 class="card-title">Travelling Allowance  Details</h4>
                        </div>
                        <div class="card-body">
                            @if($empDet)
                                <div class="table-responsive">
                                    <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="6"  style="background-color:gray;"><h4 style="color:yellow;margin-top:6px;"><center>
                                                    Travelling Allowance Applications
                                                </center></h4></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" width="10%"><h5 style="color:black;">Employee Name</h5></th>
                                                <th class="text-center" width="25%"><h5 style="color:green;"> 
                                                    @if($empDet->firmType == 1)
                                                        {{$empDet->empCode}}
                                                    @elseif($empDet->firmType == 2)
                                                        AFF{{$empDet->empCode}}
                                                    @else
                                                        AFS{{$empDet->empCode}}
                                                    @endif -
                                                    {{$empDet->empName}}
                                                </h5></th>
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
                            @if($userType == '61' || $userType == '51')
                                @if(isset($pendingApplications))
                                    @if(count($pendingApplications))
                                        <h4 class="card-title"><center style="color:red;">Pending Travelling Allowances</center></h4>
                                        <div class="table-responsive">
                                            <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                                <thead>
                                                    <tr>
                                                        <th class="" width="5%">Day</th>
                                                        <th class="">Reason</th>
                                                        <th class="" width="5%">From </th>
                                                        <th class="" width="5%">To</th>
                                                        <th class="" width="3%">Kms<?php $i=1;$totKM=0;$totRs=0; ?></th>
                                                        <th class="" width="3%">Rs.</th>
                                                        <th class="text-center" width="12%">Reporting Authority</th>
                                                        <th class="text-center" width="12%">HR Dept.</th>
                                                        <th class="text-center" width="12%">Accounts Dept.<?php $i=1;?></th>
                                                        <th class="text-center" width="12%">Reason</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@changeTravellingTranspStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                    @foreach($pendingApplications as $row)
                                                        <tr>
                                                            <td>{{date('d', strtotime($row->startDate))}}
                                                            <?php $km = (double)str_replace("km","",$row->kms); 
                                                                $totKM += $km; ?>
                                                            <input type="hidden" value="{{$row->id}}" name="appId[]">  
                                                            </td>
                                                            <td>{{$row->reason}}</td>
                                                            <td>{{$row->fromDest}}</td>
                                                            <td>{{$row->toDest}}</td>
                                                            <td>{{$row->kms}}</td>
                                                            <td>{{$amt = $km*$perKmRate}}
                                                                <?php $totRs += $amt;?>
                                                            </td>
                                                            @if($userType != '61' && $userType != '51')
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status1 == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status1 == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status1 == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div><br>
                                                                    {{($row->updatedAt1 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt1)))}}<br>
                                                                    {{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                                                </td>
                                                            @else
                                                                <td class="text-center" style="color:{{($row->status1 == 0)?'purple':(($row->status1 == 1)?'green':'red')}}"><b>{{($row->status1 == 0)?'Pending':(($row->status1 == 1)?'Approved':'Rejected')}}<br>
                                                                    {{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                                                    {{($row->updatedAt1 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt1)))}}
                                                                </b></td>
                                                            @endif

                                                            @if($userType == '51')
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status2 == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status2 == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status2 == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div><br>
                                                                    {{($row->updatedAt2 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt2)))}}<br>
                                                                    {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                                                </td>
                                                            @else
                                                                <td class="text-center" style="color:{{($row->status2 == 0)?'purple':(($row->status2 == 1)?'green':'red')}}"><b>{{($row->status2 == 0)?'Pending':(($row->status2 == 1)?'Approved':'Rejected')}}<br>
                                                                {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                                                    {{($row->updatedAt2 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt2)))}}</b></td>
                                                            @endif

                                                            @if($userType == '61')
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;">Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div><br>
                                                                    {{($row->updatedAt3 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt3)))}}<br>
                                                                    {{($row->approvedBy == '')?'':$row->approvedBy}}<br>
                                                                </td>
                                                            @else
                                                                <td class="text-center" style="color:{{($row->status == 0)?'purple':(($row->status == 1)?'green':'red')}}"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}<br>
                                                                {{($row->approvedBy3 == '')?'':$row->approvedBy3}}<br>
                                                                    {{($row->updatedAt3 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt3)))}}</b></td>
                                                            @endif
                                                            <td><textarea class="form-control" placeholder="Reason of Rejection" rows="4" name="rejectReason[]">{{$row->rejectReason}}</textarea></td>
                                                        </tr>
                                                    @endforeach   
                                                    <tr>
                                                        <th colspan="4">Total</th>
                                                        <th>{{$util->numberFormat($totKM)}}</th>
                                                        <th>{{$util->numberFormat($totKM*5)}}</th>
                                                        @if($logEmpId != $empId)
                                                            <th colspan="4"></th>
                                                        @else
                                                            <th colspan="2"></th>
                                                        @endif
                                                    </tr>  
                                                    @if($logEmpId != $empId)
                                                        <tr>
                                                            <td colspan="11" class="text-center"><button type="submit" name="" class="btn btn-danger btn-lg">Submit</button>
                                                            <input type="hidden" value="1" name="flagType">
                                                        </tr>
                                                    @endif
                                                {!! Form::close() !!}   
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endif
                                @if(isset($applications))
                                    @if(count($applications))
                                        <h4 class="card-title"><center style="color:red;">Travelling Allowances</center></h4>
                                        <div class="table-responsive">
                                            <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                                <thead>
                                                    <tr>
                                                        <th class="" width="5%">Day</th>
                                                        <th class="">Reason</th>
                                                        <th class="" width="5%">From </th>
                                                        <th class="" width="5%">To</th>
                                                        <th class="" width="3%">Kms<?php $i=1;$totKM=0;$totRs=0; ?></th>
                                                        <th class="" width="3%">Rs.</th>
                                                        <th class="text-center" width="12%">Reporting Authority</th>
                                                        <th class="text-center" width="12%">HR Dept.</th>
                                                        <th class="text-center" width="12%">Accounts Dept.<?php $i=1;?></th>
                                                        <th class="text-center" width="12%">Reason</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@changeTravellingTranspStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                    @foreach($applications as $row)
                                                        <tr>
                                                            <td>{{date('d', strtotime($row->startDate))}}
                                                            <?php $km = (double)str_replace("km","",$row->kms); 
                                                                $totKM += $km; ?>
                                                            <input type="hidden" value="{{$row->id}}" name="appId[]">  
                                                            </td>
                                                            <td>{{$row->reason}}</td>
                                                            <td>{{$row->fromDest}}</td>
                                                            <td>{{$row->toDest}}</td>
                                                            <td>{{$row->kms}}</td>
                                                            <td>{{$amt = $km*$perKmRate}}
                                                                <?php $totRs += $amt;?>
                                                            </td>
                                                            @if($userType != '61' && $userType != '51')
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status1 == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status1 == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status1 == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div><br>
                                                                    {{($row->updatedAt1 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt1)))}}<br>
                                                                    {{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                                                </td>
                                                            @else
                                                                <td class="text-center" style="color:{{($row->status1 == 0)?'purple':(($row->status1 == 1)?'green':'red')}}"><b>{{($row->status1 == 0)?'Pending':(($row->status1 == 1)?'Approved':'Rejected')}}<br>
                                                                    {{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                                                    {{($row->updatedAt1 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt1)))}}
                                                                </b></td>
                                                            @endif

                                                            @if($userType == '51')
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status2 == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status2 == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status2 == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div><br>
                                                                    {{($row->updatedAt2 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt2)))}}<br>
                                                                    {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                                                </td>
                                                            @else
                                                                <td class="text-center" style="color:{{($row->status2 == 0)?'purple':(($row->status2 == 1)?'green':'red')}}"><b>{{($row->status2 == 0)?'Pending':(($row->status2 == 1)?'Approved':'Rejected')}}<br>
                                                                {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                                                    {{($row->updatedAt2 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt2)))}}</b></td>
                                                            @endif

                                                            @if($userType == '61')
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status == 0)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;">Pending</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status == 1)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status == 2)?'checked':''}}>
                                                                        <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                    </div><br>
                                                                    {{($row->updatedAt3 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt3)))}}<br>
                                                                    {{($row->approvedBy == '')?'':$row->approvedBy}}<br>
                                                                </td>
                                                            @else
                                                                <td class="text-center" style="color:{{($row->status == 0)?'purple':(($row->status == 1)?'green':'red')}}"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}<br>
                                                                {{($row->approvedBy3 == '')?'':$row->approvedBy3}}<br>
                                                                    {{($row->updatedAt3 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt3)))}}</b></td>
                                                            @endif
                                                            <td><textarea class="form-control" placeholder="Reason of Rejection" rows="4" name="rejectReason[]">{{$row->rejectReason}}</textarea></td>
                                                        </tr>
                                                    @endforeach   
                                                    <tr>
                                                        <th colspan="4">Total</th>
                                                        <th>{{$util->numberFormat($totKM)}}</th>
                                                        <th>{{$util->numberFormat($totKM*5)}}</th>
                                                        @if($logEmpId != $empId)
                                                            <th colspan="4"></th>
                                                        @else
                                                            <th colspan="2"></th>
                                                        @endif
                                                    </tr>  
                                                    @if($logEmpId != $empId)
                                                        <tr>
                                                            <td colspan="11" class="text-center"><button type="submit" name="" class="btn btn-danger btn-lg">Submit</button>
                                                            <input type="hidden" value="1" name="flagType">
                                                        </tr>
                                                    @endif
                                                {!! Form::close() !!}   
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endif
                            @endif

                            @if(isset($applications))
                                @if(count($applications))
                                    <h4 class="card-title"><center style="color:red;">Travelling Allowances</center></h4>
                                    <div class="table-responsive">
                                        <table id="travelAllowTable" class="table  table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                            <thead>
                                                <tr>
                                                    <th class="" width="5%">No</th>
                                                    <th class="" width="5%">Day</th>
                                                    <th class="">Reason</th>
                                                    <th class="" width="5%">From </th>
                                                    <th class="" width="5%">To</th>
                                                    <th class="" width="3%">Kms<?php $i=1;$totKM=0;$totRs=0; ?></th>
                                                    <th class="" width="3%">Rs.</th>
                                                    <th class="text-center" width="12%">Reporting Authority</th>
                                                    <th class="text-center" width="12%">HR Dept.</th>
                                                    <th class="text-center" width="12%">Accounts Dept.<?php $i=1;?></th>
                                                    <th class="text-center" width="12%">Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@changeTravellingTranspStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                                @foreach($applications as $row)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{date('d', strtotime($row->startDate))}}
                                                        <?php $km = (double)str_replace("km","",$row->kms); 
                                                            $totKM += $km; ?>
                                                        <input type="hidden" value="{{$row->id}}" name="appId[]">  
                                                        </td>
                                                        <td>{{$row->reason}}</td>
                                                        <td>{{$row->fromDest}}</td>
                                                        <td>{{$row->toDest}}</td>
                                                        <td>{{$row->kms}}</td>
                                                        <td>{{$amt = $km*$perKmRate}}
                                                            <?php $totRs += $amt;?>
                                                        </td>
                                                        @if($userType != '61' && $userType != '51' && $logEmpId != $empId)
                                                            <td>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status1 == 0)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status1 == 1)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status1 == 2)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                </div><br>
                                                                {{($row->updatedAt1 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt1)))}}<br>
                                                                {{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                                            </td>
                                                        @else
                                                            <td class="text-center">
                                                                <b style="color:{{($row->status1 == 0)?'purple':(($row->status1 == 1)?'green':'red')}}">{{($row->status1 == 0)?'Pending':(($row->status1 == 1)?'Approved':'Rejected')}}</b><br>
                                                                <b style="color:black;">{{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                                                {{($row->updatedAt1 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt1)))}}</b>
                                                            </td>
                                                        @endif

                                                        @if($userType == '51')
                                                            <td>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status2 == 0)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;"> Pending</b></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status2 == 1)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status2 == 2)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                </div><br>
                                                                {{($row->updatedAt2 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt2)))}}<br>
                                                                {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                                            </td>
                                                        @else
                                                            <td class="text-center" style="color:{{($row->status2 == 0)?'purple':(($row->status2 == 1)?'green':'red')}}"><b>{{($row->status2 == 0)?'Pending':(($row->status2 == 1)?'Approved':'Rejected')}}<br>
                                                            {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                                                {{($row->updatedAt2 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt2)))}}</b></td>
                                                        @endif

                                                        @if($userType == '61')
                                                            <td>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio1{{$row->id}}" name="status{{$row->id}}" value="0" style="height:20px;width:20px;" {{($row->status == 0)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio1{{$row->id}}">&nbsp;<b style="color:orange;">Pending</b></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio2{{$row->id}}" name="status{{$row->id}}" value="1" style="height:20px;width:20px;" {{($row->status == 1)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio2{{$row->id}}">&nbsp;<b style="color:green;"> Approved</b></label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" class="form-check-input" id="radio3{{$row->id}}" name="status{{$row->id}}" value="2" style="height:20px;width:20px;" {{($row->status == 2)?'checked':''}}>
                                                                    <label class="form-check-label" for="radio3{{$row->id}}">&nbsp;<b style="color:red;"> Rejected</b></label>
                                                                </div><br>
                                                                {{($row->updatedAt3 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt3)))}}<br>
                                                                {{($row->approvedBy == '')?'':$row->approvedBy}}<br>
                                                            </td>
                                                        @else
                                                            <td class="text-center" style="color:{{($row->status == 0)?'purple':(($row->status == 1)?'green':'red')}}"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}<br>
                                                            {{($row->approvedBy3 == '')?'':$row->approvedBy3}}<br>
                                                                {{($row->updatedAt3 == '')?'-':(date('d-m-Y h:i A', strtotime($row->updatedAt3)))}}</b></td>
                                                        @endif
                                                        <td><textarea class="form-control" placeholder="Reason of Rejection" rows="4" name="rejectReason[]">{{$row->rejectReason}}</textarea></td>
                                                    </tr>
                                                @endforeach   
                                                <tr>
                                                    <th colspan="4">Total</th>
                                                    <th>{{$util->numberFormat($totKM)}}</th>
                                                    <th>{{$util->numberFormat($totKM*5)}}</th>
                                                    @if($logEmpId != $empId)
                                                        <th colspan="4"></th>
                                                    @else
                                                        <th colspan="2"></th>
                                                    @endif
                                                </tr>  
                                                @if($logEmpId != $empId)
                                                    <tr>
                                                        <td colspan="11" class="text-center"><button type="submit" name="" class="btn btn-danger btn-lg">Submit</button>
                                                        <input type="hidden" value="1" name="flagType">
                                                    </tr>
                                                @endif
                                            {!! Form::close() !!}   
                                            </tbody>
                                        </table>
                                        <a class="btn btn-success" href="/travellingAllowance/{{$userEmpId}}/{{date('Y-m', strtotime($forDate))}}/exportPdfTA">Print</a>
                                    </div>
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
