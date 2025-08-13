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
            {!! Form::open(['action' => 'storeController\RequisitionsController@updateReqProductReturn', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
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
                <div class="row">  
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0" id="myTable1">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Image</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" class="text-center">Product Detail</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="5%" class="text-center">Delivered Qty</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="15%" class="text-center">Return Qty<?php $i=1;$total=$totalQty=0; ?></th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @foreach($prodList as $product)
                                    <?php $productDetail = $util->getProductDetail($product->productId);?>
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td><a href="/storeAdmin/productImages/{{$productDetail->image}}" target="_blank"><img class="thumbnail zoom" height="150px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="150px" alt="img" src="/storeAdmin/productImages/{{$productDetail->image}}"></a>
                                        </td>
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
                                            Return : <b style="{{($product->prodReturn == 'Yes')?'color:red;':'color:green;'}}">{{$product->prodReturn}}</b><br>
                                            <input type="text" value="" class="form-control" name="remark[]" placeholder="Return Remark">
                                        </td>
                                        <td>{{$util->numberFormat($product->requiredQty - $product->returnQty)}}&nbsp;&nbsp;{{$product->unitName}}</td>
                                        <td><input type="text" value="0" name="returnQty[]" class="form-control">
                                            <input type="hidden" value="{{$product->productId}}" name="productId[]"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                      
                </div>
                <hr>
                <input type="hidden" value="{{$requisition->id}}" name="requisitionId">
                <button type="submit" class="btn btn-success mr-2">Save</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
