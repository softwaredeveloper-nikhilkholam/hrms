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
                    <div  class="col-lg-4"><b style="color:red;">Requisition List</b></div>
                    <div  class="col-lg-8">
                        <a href="/requisitions/create" class="btn mb-1 btn-primary">Raise Requisition</a>
                        <a href="/requisitions" class="btn mb-1 btn-primary">Pending Req. List</a>     
                        <a href="/requisitions/completedReqList" class="btn mb-1 btn-primary">Completed Req. List</a>  
                        <a href="/requisitions/oldEventReqList" class="btn mb-1 btn-primary">Old Event List</a>                           
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">  
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Branch Name<span style="color:red;"></span></label>
                        {{Form::select('branchId', $branches, $requisition->branchId, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'branchId', 'disabled'])}}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>    
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Requisition Date<span style="color:red;"></span></label>
                        <input type="date" class="form-control" value="{{date('Y-m-d', strtotime($requisition->requisitionDate))}}" name="requisitionDate" placeholder="Enter Requisition Date" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Requisitioner Name<span style="color:red;"></span></label>
                        <input type="text" class="form-control" value="{{$requisition->requisitionerName}}" name="requisitionerName" placeholder="Enter Requisition Date" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Department<span style="color:red;"></span></label>
                        {{Form::select('departmentId', $departments, $requisition->departmentId, ['class'=>'form-control', 'placeholder'=>'Select a Department', 'required', 'id'=>'departmentId', 'disabled'])}}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Date of Requirement<span style="color:red;"></span></label>
                        <input type="date" class="form-control" name="dateOfRequirement" value="{{$requisition->dateOfRequirement}}" placeholder="Enter Requisition Date" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Select Sevirity<span style="color:red;"></span></label>
                        {{Form::select('sevirity', ['1'=>'NORMAL','2'=>'URGENT','3'=>'VERY URGENT'], $requisition->sevirity, ['class'=>'form-control', 'placeholder'=>'Select a Sevirity', 'required', 'id'=>'sevirity', 'disabled'])}}
                        <div class="help-block with-errors"></div>
                    </div>
                </div> 
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Deliver To<span style="color:red;"></span></label>
                        <input type="text" class="form-control" value="{{$requisition->deliveryTo}}" name="deliverTo" placeholder="Enter Authority Name" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>    
                <div class="col-md-2">                      
                    <div class="form-group">
                        <label>Authority Name<span style="color:red;"></span></label>
                        <input type="text" class="form-control" value="{{$requisition->authorityName}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>    
                <div class="col-md-8">   
                    <div class="form-group">
                        <label>Event Details / Requisition For</label>
                        <textarea class="form-control" maxlength="2000" style="height:70px !important;" name="requisitionFor" disabled>{{$requisition->requisitionFor}}</textarea>
                    </div>
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
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="5%" class="text-center">Required Qty</th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Total Rs<?php $i=1;$total=$totalQty=0; ?></th>
                                <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($prodList as $product)
                                @if($product->reqProductType == 1)
                                    <?php $productDetail = $util->getProductDetail($product->productId);?>
                                        @if($productDetail)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td><a href="/storeAdmin/productImages/{{$productDetail->image}}" target="_blank"><img class="thumbnail zoom" height="150px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/storeAdmin/productImages/{{$productDetail->image}}"></a></td>
                                                <td class="text-left">
                                                    <a target="_blank"  href="/product/{{$product->productId}}" style="color:black;">{{$productDetail->productName}}</a><br>
                                                    <b style="font-size:12px;margin-bottom:2px;">Category :</b> {{$productDetail->categoryName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;">Sub Category :</b> {{$productDetail->subCategoryName}}<br>
                                                    <b style="font-size:12px;margin-bottom:2px;">Company :</b> {{$productDetail->company}}<br>
                                                    <b style="font-size:12px;margin-bottom:2px;">Size :</b> {{$productDetail->size}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;">Color :</b> {{$productDetail->color}}
                                                    <br>
                                                    @if($userType == '91')
                                                        <b style="font-size:12px;margin-bottom:2px;color:blue;">Hall :</b> {{$productDetail->hallName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;color:purple;">Rack :</b> {{$productDetail->rackName}}
                                                        &nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;color:green;">Shelf :</b> {{$productDetail->shelfName}}
                                                    @endif
                                                    Return : <b style="{{($product->prodReturn == 'Yes')?'color:red;':'color:green;'}}">{{$product->prodReturn}}</b>
                                                    <br>@if($product->requiredQty > $productDetail->stock)<b style="font-size:12px;margin-bottom:2px;color:red;">Insufficient Stock, so Purchase order raise to Purchase Department for this Product</b>@endif
                                                </td>
                                                <td>{{$util->numberFormat($product->requiredQty)}}&nbsp;&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->requiredQty; ?></td>
                                                <td>{{$util->numberFormatRound($product->currentRate*$product->requiredQty)}}<?php $total = $total + ($product->currentRate*$product->requiredQty)?></td>                                    
                                                <td>{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}<br>
                                                <br><b>{{($product->status == 2)?$product->storeRejectReason:''}}</b></td>
                                            </tr>
                                        @endif
                                @else
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td><a href="/storeAdmin/otherProductImages/{{$product->image}}" target="_blank"><img class="thumbnail zoom" height="150px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/storeAdmin/otherProductImages/{{$product->image}}"></a></td>
                                        <td class="text-left"><b style="font-size:12px;margin-bottom:2px;">{{$product->productName}}</b><br><b style="font-size:12px;margin-bottom:2px;">Description :</b> {{$product->description}}</td>
                                        <td>{{$util->numberFormat($product->requiredQty)}}&nbsp;&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->requiredQty; ?></td>
                                        <td>{{$util->numberFormatRound($product->productRate*$product->requiredQty)}}<?php $total = $total + ($product->productRate*$product->requiredQty)?></td>                                    
                                        <td>{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}<br>
                                        <br><b>{{($product->status == 2)?$product->storeRejectReason:''}}</b></td>
                                    </tr>
                                @endif
                            @endforeach
                                <tr class="ligth ligth-data">
                                    <th  style="padding: 5px 4px !important;font-size:13px;" colspan="3">Total</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;">{{$util->numberFormat($totalQty)}}</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;">Rs. {{$util->numberFormatRound($total)}}</th>   
                                    <th  style="padding: 5px 4px !important;font-size:13px;"></th>
                                </tr>
                        </tbody>
                    </table>
                </div>                      
            </div>
           
            <div class="row">    
                <div class="col-md-4">   
                    <div class="form-group">
                        <label>Updated At</label>
                        <input type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($requisition->created_at))}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                    </div>
                </div>
                <div class="col-md-4">   
                    <div class="form-group">
                        <label>Updated By</label>
                        <input type="text" class="form-control" value="{{$requisition->userBy}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                    </div>
                </div>
                <?php $userType = Session()->get('userType'); ?>
                @if($userType == '91')
                    <div class="col-md-2">   
                        <div class="form-group">
                            <a href="/requisitions/{{$requisition->id}}/edit" style="font-size: 18px !important;margin-top:30px;" class="btn btn-danger">Outward</a>
                            <a href="/requisitions/printRequisition/{{$requisition->id}}" style="font-size: 18px !important;margin-top:30px;" target="_blank" class="btn btn-success" ><i class="fa fa-print" data-toggle="tooltip" data-original-title="Show Requisition Details"></i></a> 
                        </div>
                    </div>
                @endif
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

            @if(count($trackingHistories))
                <div class="row">
                    <div class="container mb-1" style="max-width: 1800px;">
                        <div class="card mb-3">
                            <div class="p-4 text-center text-white text-lg bg-dark rounded-top"><span class="text-uppercase">Tracking Requisition No - </span><span class="text-medium">{{$requisition->requisitionNo}}</span></div>
                            <div class="card-body">
                                <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                                    @foreach($trackingHistories as $track)
                                        @if($track->remark == 'Requisition Raised')
                                            <div class="step completed">
                                                <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="pe-7s-cart"></i></div>
                                                </div>
                                                <h4 class="step-title">Requisition Raised<br><br><b style="color:red;">{{date('d-m-Y H:i', strtotime($track->created_at))}}</b></h4>
                                            </div>
                                        @endif

                                        @if($track->remark == 'Requisition Viewed')
                                            <div class="step completed">
                                                <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fa fa-eye"></i></div>
                                                </div>
                                                <h4 class="step-title">Requisition Viewed<br><br><b style="color:red;">{{date('d-m-Y H:i', strtotime($track->created_at))}}</b></h4>
                                            </div>
                                        @endif

                                        @if($track->remark == 'Outward Generated')
                                            <div class="step completed">
                                                <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fa fa-file"></i></div>
                                                </div>
                                                <h4 class="step-title">Outward Generated<br><br><b style="color:red;">{{date('d-m-Y H:i', strtotime($track->created_at))}}</b></h4>
                                            </div>
                                        @endif
                                        
                                        @if($track->remark == 'Out for Delivery')
                                            <div class="step completed">
                                                <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="pe-7s-car"></i></div>
                                                </div>
                                                <h4 class="step-title">Out for Delivery<br><br><b style="color:red;">{{date('d-m-Y H:i', strtotime($track->created_at))}}</b></h4>
                                            </div>
                                        @endif

                                        @if($track->remark == 'On the Way')
                                            <div class="step completed">
                                                <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="pe-7s-car"></i></div>
                                                </div>
                                                <h4 class="step-title">On the Way<br><br><b style="color:red;">{{date('d-m-Y H:i', strtotime($track->created_at))}}</b></h4>
                                            </div>
                                        @endif

                                        @if($track->remark == 'Requisition Delivered')
                                            <div class="step {{($requisition->status == 1)?'completed':''}}">
                                                <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fa fa-handshake-o"></i></div>
                                                </div>
                                                <h4 class="step-title">Delivered<br><br><b style="color:red;">{{($requisition->status == 1)?date('d-m-Y H:i', strtotime($requisition->updated_at)):''}}</b></h4>
                                            </div>
                                        @endif
                                        
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
