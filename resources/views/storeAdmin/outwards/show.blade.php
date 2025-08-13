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
                        <div  class="col-lg-8"><b style="color:red;">Outward List</b></div>
                        <div  class="col-lg-4">
                            <a href="/outwards" class="btn mb-1 btn-primary">Outward List <span class="badge badge-danger ml-2">{{$countOutward}}</span></a>                           
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .info-box {
                    min-height: 96px;
                    background-color: white !important;
                    text-align: center !important;
                    border: 1px solid #dee2e6;
                    border-radius: 0.375rem;
                    padding: 0.5rem 1rem;
                    margin-bottom: 1rem;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
                .info-label {
                    font-size: 12px !important;
                    font-weight: 600;
                    margin-bottom: 0.5rem;
                    color: #6c757d;
                }
                .info-value {
                    font-weight: 700;
                    font-size: 14px;
                    color: #212529;
                }
                .info-textarea {
                    background-color: white;
                    border: 1px solid #dee2e6;
                    border-radius: 0.375rem;
                    padding: 0.5rem;
                    width: 100%;
                    min-height: 70px;
                    resize: none;
                    color: #212529;
                    font-size: 14px;
                    font-family: inherit;
                }
            </style>

            <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Outward Receipt Number</label>
                                <div class="info-value">{{ $outward->receiptNo }}</div>
                            </div>
                        </div>     
                        <div class="col-md-3">
                            <div class="info-box">
                                <label class="info-label">Requisition Number</label>
                                <div class="info-value">{{ $outward->requisitionNo }}</div>
                            </div>
                        </div>     
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Date</label>
                                <div class="info-value">{{ $outward->forDate }}</div>
                            </div>
                        </div>  
                        <div class="col-md-3">
                            <div class="info-box">
                                <label class="info-label">Branch Name</label>
                                <div class="info-value">{{ $requisition->newBranchName }}</div>
                            </div>
                        </div>        
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Requisition Date</label>
                                <div class="info-value">{{ date('d-m-Y', strtotime($requisition->requisitionDate)) }}</div>
                            </div>
                        </div>   
                    </div>   

                    <div class="row mt-2">
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Requisitioner Name</label>
                                <div class="info-value">{{ $requisition->requisitionerName }}</div>
                            </div>
                        </div>   
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Department</label>
                                <div class="info-value">{{ $requisition->departmentName }}</div>
                            </div>
                        </div>   
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Date of Requirement</label>
                                <div class="info-value">{{ date('d-m-Y', strtotime($requisition->dateOfRequirement)) }}</div>
                            </div>
                        </div>   
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Severity</label>
                                @php
                                    $severityLabels = ['1' => 'NORMAL', '2' => 'URGENT', '3' => 'VERY URGENT'];
                                @endphp
                                <div class="info-value">{{ $severityLabels[$requisition->sevirity] ?? 'N/A' }}</div>
                            </div>
                        </div>   
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Deliver To</label>
                                <div class="info-value">{{ $requisition->deliveryTo }}</div>
                            </div>
                        </div>    
                        <div class="col-md-2">
                            <div class="info-box">
                                <label class="info-label">Authority Name</label>
                                <div class="info-value">{{ $requisition->authorityName }}</div>
                            </div>
                        </div>   
                    </div> 

                    <div class="row">
                        <div class="col-md-12">
                            <label class="info-label">Event Details / Requisition For</label>
                            <textarea readonly class="info-textarea">{{ $requisition->requisitionFor }}</textarea>
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
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Qty</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Total Rs<?php $i=1;$total=$totalQty=0; ?></th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @foreach($prodList as $product)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td><a href="/storeAdmin/productImages/{{$product->prodImage}}" target="_blank"><img class="thumbnail zoom" height="150px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/storeAdmin/productImages/{{$product->prodImage}}"></a></td>
                                        <td class="text-left">
                                            <a target="_blank"  href="/product/{{$product->productId}}" style="color:black;">{{$product->productMName}}</a><br>
                                            <b style="font-size:12px;margin-bottom:2px;">Category :</b> {{$product->categoryName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;">Sub Category :</b> {{$product->subCategoryName}}<br>
                                            <b style="font-size:12px;margin-bottom:2px;">Company :</b> {{$product->company}}<br>
                                            <b style="font-size:12px;margin-bottom:2px;">Size :</b> {{$product->size}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;">Color :</b> {{$product->color}}
                                            <br>
                                            @if($userType == '91')
                                                <b style="font-size:12px;margin-bottom:2px;color:blue;">Hall :</b> {{$product->hallName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;color:purple;">Rack :</b> {{$product->rackName}}
                                                &nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;color:green;">Shelf :</b> {{$product->shelfName}}
                                            @endif
                                        </td>
                                        <td>{{$util->numberFormat($product->receivedQty)}} {{$product->unitName}}<?php $totalQty = $totalQty + $product->receivedQty; ?></td>
                                        <td>{{$util->numberFormatRound($product->productRate*$product->receivedQty)}}<?php $total = $total + ($product->productRate*$product->receivedQty)?></td>                                    
                                        <td><b style="color:<?php echo ($product->status == 0)?'orange':(($product->status == 1)?'green':(($product->status == 2)?'red':'purple')); ?>">{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}</b></td>
                                    </tr>
                                @endforeach
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 5px 4px !important;font-size:13px;" colspan="3">Total</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;">{{$util->numberFormat($totalQty)}}</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;">Rs. {{$util->numberFormatRound($total)}}</th>
                                        <th></th>                                    
                                    </tr>
                            </tbody>
                        </table>
                    </div>                      
                </div>
                <div class="row">    
                    <div class="col-md-6">   
                        <div class="form-group">
                            <label>Remark</label>
                            <input type="text" class="form-control" maxlength="500" value="{{$requisition->remark}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">   
                        <div class="form-group">
                            <label>Updated At</label>
                            <input type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($requisition->created_at))}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">   
                        <div class="form-group">
                            <label>Updated By</label>
                            <input type="text" class="form-control" value="{{$requisition->userBy}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                        </div>
                    </div>
                    <?php $userType = Session()->get('userType'); ?>
                </div>
                @if(count($returnProductList))
                    <div class="row">
                        <h5>Product Return List</h5>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered mb-0" id="myTable1">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Product Name</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Qty</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Updated At</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Updated By<?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    @foreach($returnProductList as $product)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$product->name}}</td>
                                            <td>{{$util->numberFormat($product->returnQty)}} {{$product->unitName}}</td>
                                            <td>{{date('d-m-Y H:i', strtotime($product->created_at))}}</td>                                    
                                            <td>{{$product->updated_by}}</td>                                    
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>     
                    </div>
                @endif


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
</div>
@endsection
