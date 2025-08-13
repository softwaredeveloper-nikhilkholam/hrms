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
                            <div  class="col-lg-6"><b style="color:red;">WorkOrder List</b></div>
                            <div  class="col-lg-6 text-right">
                                @if(Auth::user()->userType == '701' || $userType == '801')
                                    <a href="/workOrder/create" class="btn mb-1 btn-danger">Generate</a>
                                @endif
                                <a href="/workOrder" class="btn mb-1 btn-primary">Pending List</a>
                                <a href="/workOrder/approvedOrderList" class="btn mb-1 btn-success">Approved List</a>
                                <a href="/workOrder/rejectedOrderList" class="btn mb-1 btn-primary">Rejected List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\WorkOrdersController@approvedOrderList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="autocomplete">
                                    <input id="myInputVendor" type="text" name="myInputVendorName" value="{{$myInputVendorName}}" class="form-control" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{Form::select('raisedBys', $users, $raisedBys, ['placeholder'=>'Select Option','class'=>'form-control'])}}
                            </div>
                            <div class="col-md-2">
                                <input type="month" name="forMonth" value="{{(!$forMonth)?date('Y-m'):$forMonth}}" class="form-control" placeholder="For Month">
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($orders))
                                    <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:12px;" width="3%">No</th>
                                                <th style="font-size:12px;" width="3%">WO No</th>
                                                <th style="font-size:12px;" width="15%">Vendor</th>
                                                <th style="font-size:12px;" width="30%">Requisition For</th>
                                                <th style="font-size:12px;" width="5%">Final Rs.</th>
                                                <th style="font-size:12px;" width="3%">Bill No</th>
                                                <th style="font-size:12px;" width="5%">Type of Company</th>
                                                <th style="font-size:12px;" width="5%">Already Paid</th>
                                                <th style="font-size:12px;" width="10%">Updated At</th>
                                                <th style="font-size:12px;">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            <?php $orderNo=0; $temp=0; ?>
                                            @foreach($orders as $order)
                                                @if($temp != $order->commWONo)
                                                    <tr>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center;">{{$i++}}</td>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center;">{{$order->poNumber}}</td>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center;">{{$order->vendorName}}</td>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:left;">{{ucwords(strtolower($order->WOFor))}}</td>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center;">{{$util->numberFormat($order->finalRs)}}</td>
                                                        <td  style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:center;">{{$order->billNo}}</td>
                                                        <td  style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:center;">{{$order->typeOfCompanyName}}</td>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center; {{($order->alreadyPaid == 0)?'color:red;':'color:green;'}}" class="text-left"><b>{{($order->alreadyPaid == 0)?'✘':'✓'}}</b></td>
                                                       
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center;">{{date('d-m-y H:i', strtotime($order->created_at))}}<br>{{($order->raisedBy != '')?$util->getQuotRaisedBy($order->raisedBy):'-'}}</td>
                                                        <td style="padding: 0px 10px !important;font-size:10px;text-align:center;">
                                                            <div class="d-flex align-items-center list-action">
                                                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Work Order"
                                                                    href="/workOrder/{{$order->commWONo}}"><i class="fa fa-eye mr-0"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <?php $temp = $order->commWONo; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a class="badge bg-danger mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Work Order"
                                            href="/workOrder/{{(($myInputVendorName == '')?0:$myInputVendorName)}}/{{(($raisedBys == '')?0:$raisedBys)}}/Pending/exportWorkOrders"><i class="fa fa-excel mr-0"></i>Export</a>
                                        </div>
                                        <div class="col-md-6"></div>
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
