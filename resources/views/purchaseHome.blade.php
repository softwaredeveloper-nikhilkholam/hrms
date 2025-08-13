<?php
    use App\Helpers\Utility;
    $util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <style>
        .dashboard-box {
            padding: 10px;
            border-radius: 10px;
            color: white;
            text-align: center;
            margin-bottom: 10px;
            size:15px;
        }
        h5, h6{
            color:white;
        }
        .blinking-text {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-7"><b style="color:red;">Purchase Department</b></div>
                        <div  class="col-lg-5"></div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#641e16;">
                            <h6 style="color:white !important;"><i class="fa fa-money fa-2x"></i>&nbsp;&nbsp;Total Outstanding</h6>
                            <h5>Rs. {{$util->numberFormat($purchaseData['outstandingRs'])}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#186a3b;">
                            <h6 style="color:white !important;"><i class="fa fa-user"></i>&nbsp;&nbsp;Vendors</h6>
                            <h5>{{$purchaseData['vendorCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box" style="background-color:#78281f;">
                            <h6 style="color:white !important;"><i class="fa fa-shopping-cart fa-2x"></i>&nbsp;&nbsp;PO Outstanding</h6>
                            <h5>Rs. {{$util->numberFormat($purchaseData['outstandingPORs'])}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#512e5f;">
                            <h6 style="color:white !important;"><i class="fa fa-shopping-cart fa-2x"></i>&nbsp;&nbsp;WO Outstanding</h6>
                            <h5>Rs. {{$util->numberFormat($purchaseData['outstandingWORs'])}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#154360;">
                            <h6 style="color:white !important;"><i class="fa fa-check-circle fa-2x"></i>&nbsp;&nbsp;Paid WO</h6>
                            <h5>Rs. {{$util->numberFormat($purchaseData['paidWORs'])}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#1b4f72;">
                            <h6 style="color:white !important;"><i class="fa fa-check-circle fa-2x"></i>&nbsp;&nbsp;Paid PO</h6>
                            <h5>Rs. {{$util->numberFormat($purchaseData['paidPORs'])}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#0e6251;">
                            <h6 style="color:white !important;"><i class="fa fa-hourglass-half fa-2x"></i>&nbsp;&nbsp;Pending WO</h6>
                            <h5>{{$purchaseData['pendingWorkOrderCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-box count-box"  style="background-color:#145a02;">
                            <h6 style="color:white !important;"><i class="fa fa-hourglass-half fa-2x"></i>&nbsp;&nbsp;Pending PO</h6>
                            <h5>{{$purchaseData['pendingQuotationCount']}}</h5>
                        </div>
                    </div>                   
                </div>
                <div class="row">
                    <div class="col-lg-6">  
                        <div class="card card-block card-stretch card-height-helf">
                            <div class="card-body">
                                @if(count($purchaseData['POreminders']))
                                <h5 style="color:Red;">PO Payment Reminder List</h5>
                                    <table id="datatable" data-page-length='5' class="table table-bordered data-table table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <td>No.</td>
                                                <td>Vendor Name</td>
                                                <td>PO Number</td>
                                                <td>Date</td>
                                                <td>Amount<?php $i=1; ?></td>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white text-uppercase">
                                        @foreach($purchaseData['POreminders'] as $order)
                                            <tr class="">
                                                <td>{{$i++}}</td>
                                                <td>{{$order->name}}</td>
                                                <td>{{$order->poNumber}}</td>
                                                <td>{{$order->forDate}}</td>
                                                <td>{{$util->numberFormat($order->amount)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h6 style="color:Red;">Not Found Purchase order Payment Record...</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">  
                        <div class="card card-block card-stretch card-height-helf">
                            <div class="card-body">
                                @if(count($purchaseData['WOreminders']))
                                <h5 style="color:Red;">WO Payment Reminder List</h5>
                                    <div class="table-responsive">
                                        <table id="datatable" data-page-length='5' class="table table-bordered data-table table-striped" style="">
                                            <thead class="bg-white">
                                                <tr class="ligth ligth-data">
                                                    <td width="3%" style="font-size:12px;">No.</td>
                                                    <td style="font-size:12px;">Vendor Name</td>
                                                    <td width="10%" style="font-size:12px;">PO Number</td>
                                                    <td width="18%" style="font-size:12px;">Date</td>
                                                    <td width="10%"style="font-size:12px;">Amount<?php $i=1; ?></td>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                            @foreach($purchaseData['WOreminders'] as $order)
                                                <tr class="">
                                                    <td style="font-size:10px;">{{$i++}}</td>
                                                    <td style="font-size:10px;">{{$order->name}}</td>
                                                    <td style="font-size:10px;">{{$order->poNumber}}</td>
                                                    <td style="font-size:10px;">{{date('d-m-Y', strtotime($order->forDate))}}</td>
                                                    <td style="font-size:10px;">{{$util->numberFormat($order->amount)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h6 style="color:Red;">Not Found Work order Payment Record...</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
