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
                        <div  class="col-lg-7"><b style="color:red;">Outward Entry</b></div>
                        <div  class="col-lg-5">
                            <a href="/requisitions/raiseRequisition" class="btn mb-1 btn-primary">Raise Requisition</a>
                            <a href="/requisitions" class="btn mb-1 btn-primary">Requisition List <span class="badge badge-danger ml-2">0</span>
                            <a href="/outwards" class="btn mb-1 btn-primary">Outward List <span class="badge badge-danger ml-2">0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">  
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0" border="0">
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
                {!! Form::open(['action' => ['storeController\EventRequisitionsController@update', $requisition->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row raiseRequestion">  
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered mb-0" id="outwardTable">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="25%" class="text-center">Product Detail</th>
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="7%" class="text-center">Stock</th>                                               
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="7%" class="text-center">Qty</th> 
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="7%" class="text-center">Total Rs.</th>                                                    
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="12%" class="text-center">Return & Due Date</th>                                               
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="10%" class="text-center">Status</th>                                               
                                        <th  style="padding: 0px 6px !important;font-size:13px;" width="20%" class="text-center">Reason&nbsp;<b style="color:red;">{ Max. 500 Character }</b><?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    @foreach($prodList as $product)                                        
                                        <?php $productDetail = $util->getProductDetail($product->productId);?>
                                        <tr>
                                            <td  style="padding: 0px 6px !important;font-size:13px;">{{$i++}}<input type="hidden" value="{{$productDetail->productId}}" name="productId[]" {{($product->status == 0 || $product->status == 3)?'readonly':'disabled'}}>
                                                <input type="hidden" value="{{$product->id}}" name="productListId[]" readonly></td>
                                            <td  style="padding: 0px 6px !important;font-size:13px;" class="text-left"><a target="_blank" href="/product/{{$productDetail->productId}}"  style="color:black;">{{$productDetail->productName}}</a><br>
                                                <b style="font-size:12px;margin-bottom:2px;">Category : {{$productDetail->categoryName}}</b>&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;">Sub Category : {{$product->subCategoryName}}</b><br>
                                                <b style="font-size:12px;margin-bottom:2px;">Company :{{$productDetail->company}}</b><br>
                                                <b style="font-size:12px;margin-bottom:2px;">Size :{{$productDetail->size}}</b>&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;">Color :</b> {{$productDetail->color}}
                                                <br><b style="font-size:12px;margin-bottom:2px;color:blue;">Hall :</b> {{$productDetail->hallName}}&nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;color:purple;">Rack :</b> {{$productDetail->rackName}}
                                                &nbsp;&nbsp;|&nbsp;&nbsp; <b style="font-size:12px;margin-bottom:2px;color:green;">Shelf :</b> {{$productDetail->shelfName}}
                                                <br>@if($product->qty > $productDetail->stock)<b style="font-size:12px;margin-bottom:2px;color:red;">Insufficient Stock, so Purchase order raise to Purchase Department for this Product</b>@endif
                                            </td>
                                            <td  style="padding: 0px 6px !important;font-size:13px;">{{$util->numberFormat($productDetail->stock)}} {{$productDetail->unitName}}</td>
                                            <td  style="padding: 0px 6px !important;font-size:13px;"><input type="text" class="form-control" value="{{$product->qty}}" name="requiredQty[]" required>{{$productDetail->unitName}}</td>
                                            <td  style="padding: 0px 6px !important;font-size:13px;">{{$util->numberFormatRound($product->productRate*$product->qty)}}</td>        
                                            <td  style="padding: 0px 6px !important;font-size:13px;">{{Form::select('returnStatus[]', ['Yes'=>'Yes', 'No'=>'No'], ($product->reqType == 1)?$product->retStatus:'Yes', ['class'=>'form-control','id'=>'returnStatus',  'placeholder'=>'Select a Option', ($product->status == 0 || $product->status == 3)?'required':'disabled'])}}
                                            <br><input type="date" name="dueDate[]" id="dueDate" value="{{date('Y-m-d', strtotime('+7 days'))}}" class="form-control" required></td>
                                            <td  style="padding: 0px 6px !important;font-size:13px;">{{Form::select('status[]', ['0'=>'Pending', '1'=>'Accepted', '2'=>'Rejected'], ($product->qty > $productDetail->stock)?'3':$product->status, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'id'=>'status', ($product->status == 0 || $product->status == 3)?'required':'disabled'])}}</td>
                                            <td  style="padding: 0px 6px !important;font-size:13px;"><textarea name="productReason[]"  style="height: 70px !important;" id="productReason" class="form-control" {{($product->status == 0 || $product->status == 3)?'readonly':'disabled'}}>{{$product->storeRejectReason}}</textarea>
                                                <h6 style="color:red;" id="showRejectReason" style="font-size:10px;">if Reject, Please Update the Reject Reason</h6>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                      
                    </div>
                   
                    <hr>
                    <div class="row">  
                        <div class="col-md-2">   
                            <div class="form-group">
                                <label>Status</label>
                                {{Form::select('reqStatus', ['0'=>'Pending', '1'=>'Completed', '2'=>'Rejected'], $requisition->status, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}  
                            </div>
                        </div>
                        <div class="col-md-5">   
                            <div class="form-group">
                                <label>Store Remark</label>
                                <input type="text" class="form-control" value="" name="storeRemark">
                            </div>
                        </div>
                        <div class="col-md-5">   
                            <div class="form-group">
                                {{Form::hidden('_method', 'PUT')}}       
                                <input type="hidden" value="{{$requisition->id}}" name="requisitionId">
                                <input type="hidden" value="{{$requisition->requisitionNo}}" name="requisitionNo">
                                <input type="hidden" value="{{$requisition->dateOfRequirement}}" name="dateOfRequisition">
                                <input type="hidden" value="{{$requisition->deliveryTo}}" name="branchName">
                                <input type="hidden" value="{{$requisition->requisitionFor}}" name="requisitionFor">
                                <button type="submit" class="btn btn-success" style="margin-top:36px;">Save</button>
                                <button type="reset" class="btn btn-danger" style="margin-top:36px;">Reset</button>
                            </div>
                        </div>
                    </div>
                    <script>
                       
                    </script>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
