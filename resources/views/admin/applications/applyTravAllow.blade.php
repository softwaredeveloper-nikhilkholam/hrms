<?php 
$userType = Auth::user()->userType; 
$userEmpId = Auth::user()->empId; 
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
                            <h4 class="card-title">Application Details</h4>
                        </div>
                        <div class="card-body">
                            @if($empDet)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="6"  style="background-color:gray;"><h4 style="color:yellow;margin-top:6px;"><center>Travelling Allowance Applications</center></h4></th>
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
                            
                            {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="table-responsive">
                                    <table id="" style="background-color:#00ff4e38;" class="table table-vcenter table-bordered border-top border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="border border-primary" width="10%">Date</th>
                                                <th class="border border-primary" width="40%">Reason for Travelling</th>
                                                <th class="border border-primary" width="15%">From </th>
                                                <th class="border border-primary" width="15%">To</th>
                                                <th class="border border-primary" width="10%">Kms<?php $i=1; $totKM=0; ?></th>
                                                <th class="border border-primary" width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border border-primary"><input type="date" class="form-control" max="{{date('Y-m-d', strtotime($endDate))}}" min="{{date('Y-m-d', strtotime($startDate))}}" name="forDate" id="forDate"></td>
                                                <td class="border border-primary"><input type="text" class="form-control" value="" name="reason" id="reason"></td>
                                                <td class="border border-primary">
                                                    {{Form::select('fromDest', $branches, null, ['class'=>'form-control', 'placeholder'=>'Other', 'id'=>'fromDest'])}}
                                                    <input type="text" class="form-control" name="otherFromDest" id="otherFromDest" placeholder="Other">
                                                </td>
                                                <td class="border border-primary">{{Form::select('toDest', $branches, null, ['class'=>'form-control', 'placeholder'=>'Other', 'id'=>'toDest'])}}
                                                    <input type="text" class="form-control" name="otherToDest" id="otherToDest" placeholder="other">
                                                </td>
                                                <td class="border border-primary"><input type="text" class="form-control" value="0" name="km" id="km" onkeypress="return isNumberKey(event)" ></td>
                                                <td class="border border-primary"><input type="hidden" value="{{$appType}}" name="appType">
                                                    <input type="hidden" value="{{$month}}" name="month">
                                                    <button type="submit" class="btn btn-primary btn-lg">Add Entry</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            {!! Form::close() !!}
                            @if(isset($travells))
                                @if(count($travells) >= 1)
                                    <div class="table-responsive">
                                        <table id="travelAllowTable" class="table table-vcenter table-bordered border-top border-bottom travelAllowTable">
                                            <thead>
                                                <tr>
                                                    <th class="" width="10%">Date</th>
                                                    <th class="" width="30%">Reason for Travelling</th>
                                                    <th class="" width="11%">From </th>
                                                    <th class="" width="11%">To</th>
                                                    <th class="" width="5%">Kms<?php $i=1; $totKM=0; ?></th>
                                                    <th class="" width="11%">Reporting Auth</th>
                                                    <th class="" width="11%">HR </th>
                                                    <th class="" width="11%">Accounts</th>
                                                    <th class="" width="11%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($travells as $row)
                                                    <tr>
                                                        <td><?php $totKM += (double)str_replace("km","",$row->kms); ?>
                                                        <input type="hidden" value="{{$row->id}}" name="appId[]">
                                                        {{date('d-m-Y', strtotime($row->startDate))}}</td>
                                                        <td>{{$row->reason}}</td>
                                                        <td>{{$row->fromDest}}</td>
                                                        <td>{{$row->toDest}}</td>
                                                        <td>{{$row->kms}}</td>
                                                        <td>{{($row->repStatus == '')?'-':$row->repStatus}}</td>
                                                        <td>{{($row->hrStatus == '')?'-':$row->hrStatus}}</td>
                                                        <td>{{($row->accountStatus == '')?'-':$row->accountStatus}}</td>
                                                        <td>@if($row->status == 0)
                                                                <a href="/empApplications/deleteApplication/{{$row->id}}/{{$month}}" class="btn btn-danger" >Delete</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
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
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
