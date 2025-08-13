<?php
$authorityName = Session()->get('authorityName');
$userType = Auth::user()->userType;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Applications</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">
                                @if($type == 1)
                                    AGF Application
                                @elseif($type == 2)
                                    Exit Pass Application
                                @elseif($type == 4)
                                    Travelling Allowance Application
                                @else
                                    Leave Application 
                                @endif
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url()->previous() }}"><i class="fa fa-home" style="font-size:40px;" aria-hidden="true"></i></a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table  style="font-size:20px;" class="table" width="100%" border="1px">
                                        <tr>
                                            <td width="25%" class="text-center"><b>Employee Code No</b></td>
                                            <td width="25%" class="text-center"><b>Branch</b></td>
                                            <td width="25%" class="text-center"><b>Date</b></td>
                                            <td width="25%" class="text-center"><b>Reporting Authority</b></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                            @if($application->firmType == 1)
                                                {{$application->empCode}}
                                            @elseif($application->firmType == 2)
                                                AFF{{$application->empCode}}
                                            @else
                                                AFS{{$application->empCode}}
                                            @endif
                                            </td>
                                            <td class="text-center">{{$application->branchName}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($application->startDate))}}</td>
                                            <td class="text-center">{{$application->reportingName}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label class="form-label">Employee Name :</label>
                                                            <input type="text" disabled class="form-control" value="{{$application->empName}}" name="leaveStartDate" placeholder="Date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Designation<span class="text-red">*</span>:</label>
                                                            <input type="text" disabled class="form-control" value="{{$application->designationName}}" name="leaveEndDate" placeholder="Date">
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($type == 1)
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Issue in Brief :</label>
                                                                <input type="text" disabled class="form-control" value="{{$application->reason}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Description :</label>
                                                                <textarea class="form-control mb-4" disabled placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description">{{$application->description}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($type == 2)
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Reason :</label>
                                                                <input type="text" disabled class="form-control" value="{{$application->reason}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Time Out :</label>
                                                                <input type="text" disabled class="form-control" value="{{$application->timeout}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Description :</label>
                                                                <textarea class="form-control mb-4" disabled placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description">{{$application->description}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($type == 4)
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Date :</label>
                                                                <input type="date" disabled class="form-control" value="{{$application->startDate}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">From :</label>
                                                                <input type="text" disabled class="form-control" value="{{($fromDest == '')?$application->fromDest:$fromDest}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">To :</label>
                                                                <input type="text" disabled class="form-control" value="{{($toDest == '')?$application->toDest:$toDest}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Kms travelled :</label>
                                                                <input type="text" disabled class="form-control" value="{{$application->kms}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Description :</label>
                                                                    <textarea class="form-control mb-4" disabled placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description">{{$application->reason}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">From :</label>
                                                                <input type="date" disabled class="form-control" value="{{$application->startDate}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">To :</label>
                                                                <input type="date" disabled class="form-control" value="{{$application->endDate}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">I will be reporting my duty on :</label>
                                                                <input type="date" disabled class="form-control" value="{{$application->reportingDate}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Reason :</label>
                                                                <input type="text" disabled class="form-control" value="{{$application->reason}}" name="leaveStartDate" placeholder="Date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Description :</label>
                                                                <textarea class="form-control mb-4" disabled placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description">{{$application->description}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td> 
                                        </tr> 
                                        {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@updateApplicatinStatus', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                            @if($userType == '31')
                                                <tr>
                                                    <td class="text-center"><b style="color:purple;">{{($application->status == 0)?'Pending':''}}</td>
                                                    <td class="text-center"><b style="color:green;">{{($application->status == 1)?'Approved':''}}</td>
                                                    <td class="text-center"><b style="color:red;">{{($application->status == 2)?'Rejected':''}}</td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="text-center"><input style="width: 20px;height: 20px;" type="radio" value="0" name="permission" {{($application->status == 0)?'checked':''}}>&nbsp;&nbsp;Pending</td>
                                                    <td class="text-center" style="color:green;"><input style="width: 20px;height: 20px;" value="1" type="radio" name="permission" {{($application->status == 1)?'checked':''}}>&nbsp;&nbsp;Approved</td>
                                                    <td class="text-center" style="color:red;"><input style="width: 20px;height: 20px;" value="2" type="radio" name="permission" {{($application->status == 2)?'checked':''}}>&nbsp;&nbsp;Rejected</td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center" colspan="4">
                                                        <input type="hidden" value="{{$application->id}}" name="id">
                                                        @if(isset($appHistory))
                                                            @if(count($appHistory) >= 1)
                                                                <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                                            @else
                                                                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                                            @endif
                                                        @else
                                                            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if(isset($appHistory))
                                                    @if(count($appHistory) >= 1)
                                                        <tr>
                                                            <td class="text-center" colspan="4" style="color:purple;background-color:yellow;">Approval History</td>
                                                        </tr>
                                                        @foreach($appHistory as $appr)
                                                            <tr>
                                                                <td class="text-center" colspan="2">{{$appr->approvedBy}}</td>
                                                                <td class="text-center" colspan="2">{{date('d-m-Y h:i A', strtotime($appr->approvedAt))}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endif
                                        {!! Form::close() !!}
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
