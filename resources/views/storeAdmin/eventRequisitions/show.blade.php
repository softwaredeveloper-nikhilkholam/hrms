<?php
$userType = Auth::user()->userType;
use App\Helpers\Utility;
$util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-6"><b style="color:red;">Event Requisition Details</b></div>
                    <div  class="col-lg-6">
                        <a href="/eventRequisitions/create" class="btn mb-1 btn-primary">Raise Requisition</a>
                        <a href="/eventRequisitions" class="btn mb-1 btn-primary">Pending List</a>     
                        <a href="/eventRequisitions/completedReqList" class="btn mb-1 btn-primary">Completed List</a>  
                        <a href="/eventRequisitions/rejectedList" class="btn mb-1 btn-primary">Rejected / Canceled List</a>      
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">  
                <div class="table-responsive mb-3">
                    <table class="table table-bordered mb-0" border="0">
                        <tr style="border: 1px solid #DCDFE8;">
                            <td class="text-left"><b>Event Requisition No.:</b> {{$requisition->requisitionNo}}</td>
                            <td class="text-left"></td>
                            <td class="text-left"></td>
                        </tr>
                        <tr style="border: 1px solid #DCDFE8;">
                            <td class="text-left"><b>Branch Name:</b> {{$requisition->shortName}}</td>
                            <td class="text-left"><b>Requisition Date:</b> {{date('d-m-Y', strtotime($requisition->requisitionDate))}}</td>
                            <td class="text-left"><b>Requisitioner Name:</b> {{$requisition->requisitionerName}}</td>
                        </tr>
                        <tr  style="border: 1px solid #DCDFE8;">
                            <td class="text-left"><b>Department:</b> {{$requisition->departmentName}}</td>
                            <td class="text-left"><b>Date of Requirement:</b> {{date('d-m-Y', strtotime($requisition->dateOfRequirement))}}</td>
                            <td class="text-left"><b>Sevirity:</b> {{($requisition->sevirity == 1)?'Normal':(($requisition->sevirity == 2)?'Urgent':'Very Urgent')}}</td>
                        </tr>
                        <tr style="border: 1px solid #DCDFE8;">
                            <td class="text-left"><b>Deliver To:</b> {{$deliverToBranch}}</td>
                            <td class="text-left"><b>Authority Name:</b> {{$requisition->authorityName}}</td>
                            <td class="text-left" colspan=2><b>Event Details / Requisition For:</b> {{$requisition->requisitionFor}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <hr>
            <div class="row raiseRequestion">  
                <div class="table-responsive mb-3">
                    <table class="table table-bordered mb-0" id="myTable1">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Image</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" class="text-center">Product Detail</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="12%" class="text-center">Product Type</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="5%" class="text-center">Required Qty</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Status<?php $i=1;$total=$totalQty=0; ?></th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($prodList as $product)
                                @if($product->productType == 1)
                                    <?php $productDetail = $util->getProductDetail($product->productId);?>
                                        @if($productDetail)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td><a href="/storeAdmin/productImages/{{$productDetail->image}}" target="_blank"><img class="thumbnail zoom" height="150px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/storeAdmin/productImages/{{$productDetail->image}}"></a></td>
                                                <td class="text-left">
                                                    <a target="_blank"  href="/product/{{$product->productId}}" style="color:black;font-size:14px;">{{$productDetail->productName}}</a><br>
                                                    <b style="font-size:10px;margin-bottom:2px;">Category :</b> {{$productDetail->categoryName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:10px;margin-bottom:2px;">Sub Category :</b> {{$productDetail->subCategoryName}}<br>
                                                    <b style="font-size:10px;margin-bottom:2px;">Company :</b> {{$productDetail->company}}<br>
                                                    <b style="font-size:10px;margin-bottom:2px;">Size :</b> {{$productDetail->size}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:10px;margin-bottom:2px;">Color :</b> {{$productDetail->color}}
                                                    <br>
                                                    @if($userType == '91')
                                                        <b style="font-size:10px;margin-bottom:2px;color:blue;">Hall :</b> {{$productDetail->hallName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:10px;margin-bottom:2px;color:purple;">Rack :</b> {{$productDetail->rackName}}
                                                        &nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:10px;margin-bottom:2px;color:green;">Shelf :</b> {{$productDetail->shelfName}}
                                                    @endif
                                                    Return : <b style="color:red;">YES</b>
                                                    <br>@if($product->qty > $productDetail->stock)<b style="font-size:10px;margin-bottom:2px;color:red;">Insufficient Stock, so Purchase order raise to Purchase Department for this Product</b>@endif
                                                </td>
                                                <td>Store Product</td>
                                                <td>{{$util->numberFormat($product->qty)}}&nbsp;&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->qty; ?></td>
                                                <td>{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}<br>
                                                <br><b>{{($product->status == 2)?$product->storeRejectReason:''}}</b></td>
                                            </tr>
                                        @endif
                                @else
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            @if($product->image == '')
                                                NA
                                            @else
                                                <a href="/storeAdmin/otherProductImages/{{$product->image}}" target="_blank"><img class="thumbnail zoom" height="150px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/storeAdmin/otherProductImages/{{$product->image}}"></a>
                                            @endif
                                        </td>
                                        <td class="text-left"><b style="font-size:14px;margin-bottom:2px;">{{$product->productName}}</b><br>
                                        <b style="font-size:12px;margin-bottom:2px;">Description :</b> <b style="font-size:10px;margin-bottom:2px;">{{$product->description}}</b><br>Return : <b style="color:red;">YES</b></td>
                                        <td>{{($product->productType == 2)?'New Product':'Rental Product'}}</td>
                                        <td>{{$util->numberFormat($product->qty)}}&nbsp;&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->qty; ?></td>
                                        <td>{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}<br>
                                        <br><b>{{($product->status == 2)?$product->storeRejectReason:''}}</b></td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="ligth ligth-data">
                                <th  style="padding: 5px 4px !important;font-size:13px;" colspan="4">Total</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;">{{$util->numberFormat($totalQty)}}</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>                      
            </div>
            <div class="table-responsive mb-3">
                <table class="table table-bordered mb-0" border="0">
                    <tr style="border: 1px solid #DCDFE8;">
                        <td class="text-left"><b>Updated At:</b> {{date('d-m-Y H:i', strtotime($requisition->updated_at))}}</td>
                        <td class="text-left"><b>Updated By:</b> {{$requisition->updated_by}}</td>
                        <td class="text-left">
                            @if($userType == '91')
                                <a href="/eventRequisitions/{{$requisition->id}}/edit" style="font-size: 18px !important;margin-top:30px;" class="btn btn-danger">Outward</a>
                                <a href="/eventRequisitions/printRequisition/{{$requisition->id}}" style="font-size: 18px !important;margin-top:30px;" target="_blank" class="btn btn-success" ><i class="fa fa-print" data-toggle="tooltip" data-original-title="Show Requisition Details"></i> Print</a> 
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

           
            <style>
                .steps .step {
                    display: block;
                    width: 100%;
                    margin-bottom: 35px;
                    text-align: center
                }

                .steps .step .step-icon-wrap {
                    display: block;
                    position: relative;
                    width: 100%;
                    height: 80px;
                    text-align: center
                }

                .steps .step .step-icon-wrap::before,
                .steps .step .step-icon-wrap::after {
                    display: block;
                    position: absolute;
                    top: 50%;
                    width: 50%;
                    height: 3px;
                    margin-top: -1px;
                    background-color: #e1e7ec;
                    content: '';
                    z-index: 1
                }

                .steps .step .step-icon-wrap::before {
                    left: 0
                }

                .steps .step .step-icon-wrap::after {
                    right: 0
                }

                .steps .step .step-icon {
                    display: inline-block;
                    position: relative;
                    width: 80px;
                    height: 80px;
                    border: 1px solid #e1e7ec;
                    border-radius: 50%;
                    background-color: #f5f5f5;
                    color: #374250;
                    font-size: 38px;
                    line-height: 81px;
                    z-index: 5
                }

                .steps .step .step-title {
                    margin-top: 16px;
                    margin-bottom: 0;
                    color: #606975;
                    font-size: 14px;
                    font-weight: 500
                }

                .steps .step:first-child .step-icon-wrap::before {
                    display: none
                }

                .steps .step:last-child .step-icon-wrap::after {
                    display: none
                }

                .steps .step.completed .step-icon-wrap::before,
                .steps .step.completed .step-icon-wrap::after {
                    background-color: #0da9ef
                }

                .steps .step.completed .step-icon {
                    border-color: #0da9ef;
                    background-color: #0da9ef;
                    color: #fff
                }

                @media (max-width: 576px) {
                    .flex-sm-nowrap .step .step-icon-wrap::before,
                    .flex-sm-nowrap .step .step-icon-wrap::after {
                        display: none
                    }
                }

                @media (max-width: 768px) {
                    .flex-md-nowrap .step .step-icon-wrap::before,
                    .flex-md-nowrap .step .step-icon-wrap::after {
                        display: none
                    }
                }

                @media (max-width: 991px) {
                    .flex-lg-nowrap .step .step-icon-wrap::before,
                    .flex-lg-nowrap .step .step-icon-wrap::after {
                        display: none
                    }
                }

                @media (max-width: 1200px) {
                    .flex-xl-nowrap .step .step-icon-wrap::before,
                    .flex-xl-nowrap .step .step-icon-wrap::after {
                        display: none
                    }
                }

                .bg-faded, .bg-secondary {
                    background-color: #f5f5f5 !important;
                }
            </style>
            <div class="row">
                <div class="container mb-1" style="max-width: 1800px;">
                    <div class="card mb-3">
                        <div class="p-4 text-center text-white text-lg bg-dark rounded-top"><span class="text-uppercase">Tracking Requisition No - </span><span class="text-medium">{{$requisition->requisitionNo}}</span></div>
                        <div class="card-body">
                            <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                            <div class="step completed">
                                <div class="step-icon-wrap">
                                <div class="step-icon"><i class="pe-7s-cart"></i></div>
                                </div>
                                <h4 class="step-title">Requisition Raised<br><br><b style="color:red;">{{date('d-m-Y H:i', strtotime($requisitionTracking[0]->created_at))}}</b></h4>
                            </div>
                            <div class="step {{($requisition->viewedStatus == 1)?'completed':''}}">
                                <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fa fa-eye"></i></div>
                                </div>
                                <h4 class="step-title">Requisition Viewed<br><br><b style="color:red;">{{(isset($requisitionTracking[1]))?date('d-m-Y H:i', strtotime($requisitionTracking[1]->created_at)):''}}</b></h4>
                            </div>
                            <div class="step {{(isset($outward))?'completed':''}}">
                                <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fa fa-file"></i></div>
                                </div>
                                <h4 class="step-title">Outward Generated<br><br><b style="color:red;">{{(isset($requisitionTracking[2]))?date('d-m-Y H:i', strtotime($requisitionTracking[2]->created_at)):''}}</b></h4>
                            </div>
                            @if(count($requisitionTracking) >= 4)
                                <div class="step {{(!empty($requisitionTracking))?'completed':''}}">
                                    <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="pe-7s-car"></i></div>
                                    </div>
                                    <h4 class="step-title">Out for Delivery<br><br><b style="color:red;">{{(!empty($requisitionTracking))?date('d-m-Y H:i', strtotime($requisitionTracking[0]->created_at)):''}}</b></h4>
                                </div>
                                <div class="step {{(!empty($requisitionTracking) && isset($requisitionTracking[1]))?'completed':(($requisition->status == 1)?'completed':'')}}">                        
                                    <div class="step-icon-wrap">
                                        <div class="step-icon"><i class="pe-7s-car"></i></div>
                                    </div>
                                    <h4 class="step-title">On the Way
                                        @if(!empty($requisitionTracking))
                                            <br><br><b style="color:red;">{{(isset($requisitionTracking[1]))?date('d-m-Y H:i', strtotime($requisitionTracking[1]->created_at)):''}}</b>
                                        @endif
                                    </h4>
                                </div>
                            @endif
                            <div class="step {{($requisition->status == 1)?'completed':''}}">
                                <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fa fa-handshake-o"></i></div>
                                </div>
                                <h4 class="step-title">Delivered<br><br><b style="color:red;">{{($requisition->status == 1)?date('d-m-Y H:i', strtotime($requisition->updated_at)):''}}</b></h4>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
