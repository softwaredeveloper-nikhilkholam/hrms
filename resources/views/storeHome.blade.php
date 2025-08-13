<?php
    $userType = Auth::user()->userType;
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
    .nowrap {
        white-space: nowrap;
    }

    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-7"><b style="color:red;">Store Department</b></div>
                        <div  class="col-lg-5"></div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <b>Master Details</b>
                <div class="row">
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#641e16;">
                            <h6 style="color:white !important;"><i class="fa fa-list-alt"></i>&nbsp;&nbsp;Category</h6>
                            <h5>{{$storeData['category']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box" style="background-color:#78281f;">
                            <h6 style="color:white !important;"><i class="fa fa-th-list"></i>&nbsp;&nbsp;Sub Category</h6>
                            <h5>{{$storeData['subCategory']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#512e5f;">
                            <h6 style="color:white !important;"><i class="fa fa-balance-scale"></i>&nbsp;&nbsp;Units</h6>
                            <h5>{{$storeData['units']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#154360;">
                            <h6 style="color:white !important;"><i class="fa fa-building"></i>&nbsp;&nbsp;Hall</h6>
                            <h5>{{$storeData['halls']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#1b4f72;">
                            <h6 style="color:white !important;"><i class="fa fa-server"></i>&nbsp;&nbsp;Rack</h6>
                            <h5>{{$storeData['racks']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#0e6251;">
                            <h6 style="color:white !important;"><i class="fa fa-th-large"></i>&nbsp;&nbsp;Shelf</h6>
                            <h5>{{$storeData['shelfs']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#145a32;">
                            <h6 style="color:white !important;"><i class="fa fa-cube"></i>&nbsp;&nbsp;Product</h6>
                            <h5>{{$storeData['products']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#186a3b;">
                            <h6 style="color:white !important;"><i class="fa fa-user"></i>&nbsp;&nbsp;Users</h6>
                            <h5>{{$storeData['users']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#7d6608;">
                            <h6 style="color:white !important;"><i class="fa fa-recycle"></i>&nbsp;&nbsp;Scrap Category</h6>
                            <h5>{{$storeData['scrapCount']}}</h5>
                        </div>
                    </div>
                </div>
                <hr>
                <b>Transactional Details</b>
                <div class="row">
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#7CFC00;">
                            <h6 style="color:white !important;"><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;Inward</h6>
                            <h5>{{$storeData['inwards']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#7b7d7d;">
                            <h6 style="color:white !important;"><i class="fa fa-arrow-up"></i>&nbsp;&nbsp;Outward</h6>
                            <h5>{{$storeData['outwards']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:red;">
                            <h6 class="blinking-text" style="color:white !important;"><i class="fa fa-undo"></i>&nbsp;&nbsp;Return Products</h6>
                            <h5 class="blinking-text">{{round($storeData['returnProducts'])}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#5499c7;">
                            <h6 style="color:white !important;"><i class="fa fa-trash"></i>&nbsp;&nbsp;Scrap Material</h6>
                            <h5>{{$storeData['scrapCount']}}</h5>
                        </div>
                    </div>
                </div>
                <hr>
                 <b>Requisitions Details</b>
                <div class="row"> 
                    <div class="col-md-2"> 
                        <div class="dashboard-box count-box"  style="background-color:#6495ED;">
                            <h6 style="color:white !important;"><i class="fa fa-hourglass-half"></i>&nbsp;&nbsp;Pending</h6>
                            <h5>{{$storeData['reqPendingCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:green;">
                            <h6 style="color:white !important;"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;Completed</h6>
                            <h5>{{$storeData['reqCompletedCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#DE3163;">
                            <h6 style="color:white !important;"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Rejected</h6>
                            <h5>{{$storeData['reqRejectedCount']}}</h5>
                        </div>
                    </div>
                </div>
                <hr>
                <b>Purchase Requisitions Details</b>
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#6495ED;">
                            <h6 style="color:white !important;"><i class="fa fa-hourglass-half"></i>&nbsp;&nbsp;Pending</h6>
                            <h5>{{$storeData['purReqPendingCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:orange;">
                            <h6 style="color:white !important;"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;Approved</h6>
                            <h5>{{$storeData['purReqApprovedCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:green;">
                            <h6 style="color:white !important;"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;Completed</h6>
                            <h5>{{$storeData['purReqCompletedCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#DE3163;">
                            <h6 style="color:white !important;"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Rejected</h6>
                            <h5>{{$storeData['purReqRejectedCount']}}</h5>
                        </div>
                    </div>
                </div>
                <hr>
                 <b>Event Requisitions</b>
                <div class="row"> 
                    <div class="col-md-2"> 
                        <div class="dashboard-box count-box"  style="background-color:#6495ED;">
                            <h6 style="color:white !important;"><i class="fa fa-hourglass-half"></i>&nbsp;&nbsp;Pending</h6>
                            <h5>{{$storeData['eventReqPendingCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:green;">
                            <h6 style="color:white !important;"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;Completed</h6>
                            <h5>{{$storeData['eventReqCompletedCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:#DE3163;">
                            <h6 style="color:white !important;"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Rejected</h6>
                            <h5>{{$storeData['eventReqRejectedCount']}}</h5>
                        </div>
                    </div>
                </div>
                <hr>
                <b>Repaire / Maintenance</b>
                <div class="row"> 
                    <div class="col-md-2"> 
                        <div class="dashboard-box count-box"  style="background-color:#6495ED;">
                            <h6 style="color:white !important;"><i class="fa fa-hourglass-half"></i>&nbsp;&nbsp;Pending</h6>
                            <h5>{{$storeData['repairePendingCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2"> 
                        <div class="dashboard-box count-box"  style="background-color:green;">
                            <h6 style="color:white !important;"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;Completed</h6>
                            <h5>{{$storeData['repaireCompletedCount']}}</h5>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="dashboard-box count-box"  style="background-color:red;">
                            <h6 class="blinking-text" style="color:white !important;"><i class="fa fa-undo"></i>&nbsp;&nbsp;Return Products</h6>
                            <h5 class="blinking-text">{{round($storeData['returnReparieProducts'])}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    @if($userType == '501')
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
                            <div class="dashboard-box count-box"  style="background-color:#6495ED;">
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
                                        <div class="table-responsive">
                                            <table id="" data-page-length='5' class="table table-bordered data-table table-striped" style="">
                                                <thead class="bg-white text-uppercase">
                                                    <tr class="ligth ligth-data">
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">No.</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">Vendor Name</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">PO Number</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">Date</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">Amount<?php $i=1; ?></td>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white text-uppercase">
                                                @foreach($purchaseData['POreminders'] as $order)
                                                    <tr class="">
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{$i++}}</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{$order->name}}</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{$order->poNumber}}</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{$order->forDate}}</td>
                                                        <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{$util->numberFormat($order->amount)}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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
                                        <b style="color:Red;">WO Payment Reminder List</b>
                                        <div class="table-responsive">
                                            <table id="" data-page-length='5' class="table table-bordered table-striped">
                                                <thead class="bg-white text-uppercase">
                                                    <tr class="light light-data">
                                                        <th class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important; border-radius: 0 !important;">No.</th>
                                                        <th class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important; border-radius: 0 !important;">Vendor Name</th>
                                                        <th class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important; border-radius: 0 !important;">PO Number</th>
                                                        <th class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important; border-radius: 0 !important;">Date</th>
                                                        <th class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important; border-radius: 0 !important;">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white text-uppercase">
                                                    @php $i = 1; @endphp <!-- Initialize counter -->
                                                    @foreach($purchaseData['WOreminders'] as $order)
                                                        <tr>
                                                            <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{ $i++ }}</td>
                                                            <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{ $order->name }}</td>
                                                            <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{ $order->poNumber }}</td>
                                                            <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{ \Carbon\Carbon::parse($order->forDate)->format('d-M-Y') }}</td>
                                                            <td class="nowrap" style="font-size:11px;padding: 0px 5px !important; border-radius: 0 !important;">{{ number_format($order->amount, 2) }}</td>
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
    @endif
</div>
@endsection
