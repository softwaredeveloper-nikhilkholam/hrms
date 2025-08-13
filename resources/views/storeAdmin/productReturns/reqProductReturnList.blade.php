<?php
$userType = Auth::user()->userType;
use App\Helpers\Utility;
$util=new Utility(); 
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
                        <div  class="col-lg-6"><b style="color:red;">Product Return List</b></div>
                        <div  class="col-lg-6">
                            <a href="/requisitions/productReturn" class="btn mb-1 btn-primary">Add Return</a>
                            <a href="/requisitions/productReturnList" class="btn mb-1 btn-primary">
                                Return List <span class="badge badge-danger ml-2"></span>
                            </a>  
                            <a href="#" class="btn mb-1 btn-success">Req. Product Return</a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($requisitions))
                                <table id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="5%">No</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="5%">Req. Date</th>           
                                            <th style="padding: 10px 17px !important;font-size:12px;"  width="8%">Req. No.</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Branch</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Department</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;">Requisition For</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Completed At</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="3%">Status</th>
                                            <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody style="vertical-align: top;">
                                        @foreach($requisitions as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;vertical-align: top;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;vertical-align: top;">{{date('d-M', strtotime($row->requisitionDate))}}</td>
                                                <td style="padding: 0px 17px !important;vertical-align: top;">{{$row->requisitionNo}}</td>
                                                <td style="padding: 0px 17px !important;vertical-align: top;">{{$row->branchName}}</td>
                                                <td style="padding: 0px 17px !important;vertical-align: top;">{{$row->departmentName}}</td>
                                                <td style="padding: 0px 17px !important;vertical-align: top;" class="text-left">{{ucwords(strtolower($row->requisitionFor))}}<br><b style="font-size:0.1px;color:black;">{{$util->getRequisitionProducts($row->id)}}</b></td>
                                                <td style="padding: 0px 17px !important;vertical-align: top;">{{date('d-m-Y', strtotime($row->updated_at))}}</td>
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
                                                    <a href="/requisitions/reqProductReturnView/{{$row->id}}" style="font-size: 18px !important;color:blue;" ><i class="fa fa-eye" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a>                                         
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$requisitions->links()}}</div>
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
</div>
@endsection
