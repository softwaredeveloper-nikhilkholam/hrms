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
                            <a href="/outwards" class="btn mb-1 btn-primary">
                            Outward List <span class="badge badge-danger ml-2">{{$countOutward}}</span>
                            </a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Outward Receipt Number</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="requisitionNo" value="{{$outward->receiptNo}}" placeholder="Requsition Number"  disabled>
                        </div>
                    </div>     
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Requisition Number</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="requisitionNo" value="{{$outward->requisitionNo}}" placeholder="Requsition Number"  disabled>
                        </div>
                    </div>     
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Date</b></label>
                            <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="forDate" value="{{$outward->forDate}}" placeholder="Date"  disabled>
                        </div>
                    </div>
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
                </div> 
                <div class="row">    
                    <div class="col-md-12">   
                        <div class="form-group">
                            <label>Event Details / Requisition For</label>
                            <textarea class="form-control" maxlength="2000" style="height: 70px !important;" name="requisitionFor" disabled>{{$requisition->requisitionFor}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row raiseRequestion">  
                    <div class="table-responsive mb-3">
                        {!! Form::open(['action' => 'storeController\OutwardsController@updateReturnProduct', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                            <table class="table table-bordered mb-0" id="myTable1">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Image</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" class="text-center">Product Detail</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Qty</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Return Qty</th>
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
                                                <br>@if($product->requiredQty > $product->stock)<b style="font-size:12px;margin-bottom:2px;color:red;">Insufficient Stock, so Purchase order raise to Purchase Department for this Product</b>@endif
                                            </td>
                                            <td>{{$util->numberFormat($product->requiredQty)}} <?php $totalQty = $totalQty + $product->requiredQty; ?>{{$product->unitName}}
                                            @if($product->status == 1)
                                            <input type="hidden" value="{{$product->requiredQty}}" class="form-control" name="qty[]">
                                            <input type="hidden" value="{{$product->id}}" class="form-control" name="outwardProductId[]"></td>
                                            <input type="hidden" value="{{$product->productId}}" class="form-control" name="productId[]"></td>
                                            @endif
                                            <td><input type="text" value="0" maxlength="{{$product->requiredQty}}" class="form-control" name="returnQty[]" {{($product->status == 1)?'required':'disabled'}}>{{$product->unitName}}</td>
                                            <td>{{$util->numberFormatRound($product->productRate*$product->requiredQty)}}<?php $total = $total + ($product->productRate*$product->requiredQty)?></td>                                    
                                            <td>{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 5px 4px !important;font-size:13px;" colspan="4">Total</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;">{{$util->numberFormat($totalQty)}}</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;">Rs. {{$util->numberFormatRound($total)}}</th>
                                        <th></th>                                    
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" value="{{$outward->id}}" name="outwardId">
                            <button type="submit" class="btn btn-success mr-2 mt-4">Product Return</button>
                            <button type="reset" class="btn btn-danger  mt-4">Reset</button>
                        {!! Form::close() !!}
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
            </div>
        </div>
    </div>
</div>
@endsection
