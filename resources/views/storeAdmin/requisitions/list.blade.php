<?php
    use App\Helpers\Utility;
    $util = new Utility();
    $userType = Session()->get('userType');
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-4"><b style="color:red;">Pending Requisition List  <b style="color:blue;">({{$reqCount}})</b></b></div>
                        <div  class="col-lg-8 text-right">
                            <a href="/requisitions/create" class="btn mb-1 btn-primary">Raise Requisition</a>
                            <a href="/requisitions" class="btn mb-1 btn-success">Pending List</a>     
                            <a href="/requisitions/completedReqList" class="btn mb-1 btn-primary">Completed List</a>   
                            <a href="/requisitions/oldEventReqList" class="btn mb-1 btn-primary">Old Event List</a>                                               
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\RequisitionsController@index', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="requitionNumber" value="{{$requitionNumber}}" class="form-control" placeholder="Req. No.">
                        </div>
                        <div class="col-md-3">
                            {{Form::select('branchId', $branches, $branchId, ['class'=>'form-control', 'placeholder'=>'Select a Branch'])}}
                        </div>
                        <div class="col-md-3">
                            {{Form::select('departmentId', $departments, $departmentId, ['class'=>'form-control', 'placeholder'=>'Select a Department'])}}
                        </div>
                        <div class="col-md-2">
                            {{Form::select('status', ['0'=>'Pending','5'=>'In Progress'], $status, ['class'=>'form-control', 'placeholder'=>'Select a Status'])}}
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!} 
                <div class="row mt-2">
                    <div class="col-lg-12">
                        @if(count($requisitions))
                            <div class="table-responsive">
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;" width="auto">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;" width="3%">No</th>
                                            <th style="font-size:13px;" width="3%">Req. Date</th>           
                                            <th style="font-size:13px;" width="6%">Req. No.</th>
                                            <th style="font-size:13px;" width="3%">Branch</th>
                                            <th style="font-size:13px;">Department</th>
                                            <th style="font-size:13px;" width="30%">Requisition For</th>
                                            <th style="font-size:13px;">Sevirity</th>
                                            <th style="font-size:13px;">Status</th>
                                            <th style="font-size:13px;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requisitions as $row)
                                            @if($row->viewedStatus == 0)
                                                <tr style="background-color:#c2dbff;">
                                            @else   
                                                @if($row->status == 5)
                                                    <tr style="background-color:#F2D7D5;">
                                                @else
                                                    <tr>
                                                @endif
                                            @endif
                                                <td style="padding: 0px 17px !important;font-size:12px;">{{$i++}}
                                                    @if($row->viewedStatus == 0)
                                                        <i class="fa fa-envelope" style="font-size:24px;color:red;"></i>
                                                    @else
                                                        <i class="fa fa-envelope-open-o" style="font-size:24px;color:green;"></i>
                                                    @endif
                                                </td>
                                                <td style="padding: 0px 17px !important;font-size:12px;">{{date('d-M', strtotime($row->requisitionDate))}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;">{{$row->requisitionNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;">{{$row->branchName}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;">{{$row->departmentName}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;" class="text-left">{{ucwords(strtolower($row->requisitionFor))}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;">{{($row->sevirity == 1)?'Normal':(($row->sevirity == 2)?'Urgent':'Very Urgent')}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;">
                                                    @if($row->status == 0)
                                                        <b style="padding: 0px 17px !important;color:#2874A6;">
                                                    @elseif($row->status == 1)
                                                        <b style="padding: 0px 17px !important;color:#1E8449;">
                                                    @elseif($row->status == 2)
                                                        <b style="padding: 0px 17px !important;color:#C70039;">
                                                    @elseif($row->status == 3)
                                                        <b style="padding: 0px 17px !important;color:#6C3483;">
                                                    @else
                                                        <b style="padding: 0px 17px !important;color:#FF5733;">
                                                    @endif

                                                    @if($row->status == 0)
                                                        Pending
                                                    @elseif($row->status == 1)
                                                        Completed
                                                    @elseif($row->status == 2)
                                                        Rejected
                                                    @elseif($row->status == 3)
                                                        Hold
                                                    @elseif($row->status == 4)
                                                        Cancel
                                                    @else
                                                        InProgress
                                                    @endif
                                                </b>
                                                </td>
                                                <td  style="padding: 0px 17px !important;font-size:12px;">
                                                    @if($userType == '91' || $userType == '501')
                                                        <a href="/requisitions/{{$row->id}}/edit" style="font-size: 18px !important;color:red;"><i class="fa fa-sign-out" aria-hidden="true"></i></a>&nbsp;
                                                    @endif 
                                                    @if($row->status == 0)
                                                        <a href="/requisitions/{{$row->id}}/deactivate" onclick="return confirm('Are you sure to Delete Requisition....?')">
                                                            <i class="fa fa-times-circle-o" style="font-size:18px;color:red;" aria-hidden="true" data-toggle="tooltip" data-original-title="Cancel Requisition"></i>
                                                        </a>&nbsp;
                                                    @endif  
                                                    <a href="/requisitions/{{$row->id}}" style="font-size: 18px !important;color:blue;" ><i class="fa fa-eye" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a>        &nbsp;                                         
                                                    <a href="/requisitions/printRequisition/{{$row->id}}" style="font-size: 18px !important;color:green;" target="_blank"  ><i class="fa fa-print" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a>                                                 
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" style="margin-top:15px;">
                                <div class='col-md-8'></div>
                                <div class='col-md-4'></div>
                            </div>
                        @else
                            <h4>Record not found</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
