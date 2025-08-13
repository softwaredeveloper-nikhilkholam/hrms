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
                        <div  class="col-lg-6"><b style="color:red;">Event Requisition List</b></div>
                        <div  class="col-lg-6">
                            <a href="/eventRequisitions/create" class="btn mb-1 btn-primary">Raise Requisition</a>
                            <a href="/eventRequisitions" class="btn mb-1 btn-primary">Pending List</a>     
                            <a href="/eventRequisitions/completedReqList" class="btn mb-1 btn-success">Completed List</a>      
                            <a href="/eventRequisitions/rejectedList" class="btn mb-1 btn-primary">Rejected / Canceled List</a>      
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($requisitions))
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="5%">No</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="5%">Req. Date</th>           
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="8%">Req. No.</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Branch</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Department</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;">Requisition For</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Sevirity</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Status</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="8%">Action<?php $i=1; ?></th>
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
                                                <td style="padding: 0px 17px !important;">{{$i++}}<br>
                                                    @if($row->viewedStatus == 0)
                                                        <i class="fa fa-envelope" style="font-size:24px;color:red;"></i>
                                                    @else
                                                        <i class="fa fa-envelope-open-o" style="font-size:24px;color:green;"></i>
                                                    @endif
                                                </td>
                                                <td style="padding: 0px 17px !important;">{{date('d-M', strtotime($row->requisitionDate))}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->requisitionNo}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->branchName}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->departmentName}}</td>
                                                <td style="padding: 0px 17px !important;" class="text-left">{{ucwords(strtolower($row->requisitionFor))}}</td>
                                                <td style="padding: 0px 17px !important;">{{($row->sevirity == 1)?'Normal':(($row->sevirity == 2)?'Urgent':'Very Urgent')}}</td>
                                                <td>
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
                                                <td style="padding: 2px 17px !important;">
                                                    @if($userType == '91' || $userType == '501')
                                                        <a href="/eventRequisitions/{{$row->id}}/edit" style="font-size: 18px !important;color:red;"><i class="fa fa-sign-out" aria-hidden="true"></i></a>&nbsp;
                                                    @else
                                                        @if($row->status == 0)
                                                            <a href="/eventRequisitions/{{$row->id}}/deactivate" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:18px;color:red;" aria-hidden="true" data-toggle="tooltip" data-original-title="Cancel Requisition"></i>
                                                            </a>&nbsp;
                                                        @endif
                                                    @endif   
                                                    <a href="/eventRequisitions/{{$row->id}}" style="font-size: 18px !important;color:blue;" ><i class="fa fa-eye" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a>        &nbsp;                                         
                                                    <a href="/eventRequisitions/printRequisition/{{$row->id}}" style="font-size: 18px !important;color:green;" target="_blank"  ><i class="fa fa-print" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a>                                                 
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h4>Record not found</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
