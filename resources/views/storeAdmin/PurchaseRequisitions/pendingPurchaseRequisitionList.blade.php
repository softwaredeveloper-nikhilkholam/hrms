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
                        <div  class="col-lg-4"><b style="color:red;">Pending Purchase Requisition List</b></div>
                        <div  class="col-lg-8 text-right">
                            <a href="/requisitions/raisePurchaseRequisition" class="btn mb-1 btn-primary">Raise Requisition</a>
                            <a href="/requisitions/purchaseRequisitionList" class="btn mb-1 btn-success">Pending List</a>                           
                            <a href="/requisitions/approvedPurchaseRequisitionList" class="btn mb-1 btn-primary">Approved List</a>                           
                            <a href="/requisitions/completedPurchaseRequisitionList" class="btn mb-1 btn-primary">Completed List</a>                           
                            <a href="/requisitions/rejectedPurchaseRequisitionList" class="btn mb-1 btn-primary">Rejected List</a>                           
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
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="10%">Requisition Date</th>           
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="10%">Requisition No.</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="14%">Branch</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Department</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;">Requisition For</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Sevirity</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Status</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requisitions as $row)
                                            @if($row->viewedStatus == 0)
                                                <tr style="background-color:#c2dbff;">
                                            @else
                                                <tr>
                                            @endif
                                                <td style="padding: 0px 17px !important;">{{$i++}}<input type="hidden" value="aaaaaaaaaa">
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
                                                <td style="padding: 0px 17px !important;">
                                                    @if($row->status == 0)
                                                        Pending
                                                    @elseif($row->status == 1)
                                                        Completed
                                                    @elseif($row->status == 3)
                                                        Rejected
                                                    @elseif($row->status == 5)
                                                        Approved
                                                    @else
                                                        Hold
                                                    @endif
                                                </td>
                                                <td style="padding: 2px 17px !important;">
                                                    @if(($userType != '91' || $userType != '501') && $row->status == 0)
                                                        <a href="/requisitions/deactivatePurchaseRequisition/{{$row->id}}" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true" data-toggle="tooltip" data-original-title="Cancel Requisition"></i>
                                                        </a>
                                                    @endif      
                                                    <a href="/requisitions/purchaseRequisitionView/{{$row->id}}/{{(str_contains($row->requisitionNo, 'EVENT'))?'1':'2'}}" style="font-size: 12px !important;" class="btn btn-primary" ><i class="fa fa-eye" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a>                                                 
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
