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
                    <div  class="col-lg-8"><b style="color:red;">Purchase Req. List</b></div>
                    <div  class="col-lg-4">
                        <a href="/requisitions/raisePurchaseRequisition" class="btn mb-1 btn-primary">Raise Purchase Req.</a>
                        <a href="/requisitions/purchaseRequisitionList" class="btn mb-1 btn-primary">
                            Purchase Req. List <span class="badge badge-danger ml-2">{{$countRequisition}}</span>
                        </a>                           
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
            </div> 
            <div class="row">    
                <div class="col-md-6">   
                    <div class="form-group">
                        <label>Event Details / Requisition For</label>
                        <textarea class="form-control" maxlength="2000" style="height: 96px !important;" name="requisitionFor" disabled>{{$requisition->requisitionFor}}</textarea>
                    </div>
                </div>
                <div class="col-md-3">                      
                    <div class="form-group">
                        <label>Deliver To<span style="color:red;"></span></label>
                        <input type="text" class="form-control" value="{{$requisition->deliveryTo}}" name="deliverTo" placeholder="Enter Authority Name" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>    
                <div class="col-md-3">                      
                    <div class="form-group">
                        <label>Authority Name<span style="color:red;"></span></label>
                        <input type="text" class="form-control" value="{{$requisition->authorityName}}" name="authorityName" placeholder="Enter Authority Name" disabled>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  
            </div>
            @if(count($products))
                <div class="table-responsive">
                    <table id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                        <thead>
                            <tr class="ligth">
                                <th style="padding: 10px 17px !important;font-size:12px;" width="5%">No</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="30%">Product Name</th>           
                                <th style="padding: 10px 17px !important;font-size:12px;" width="15%">Qty</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="40%">Description</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Product Image<?php $i=1; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$product->prodName}}</td>
                                    <td>{{$product->qty}}</td>
                                    <td>{{$product->description}}</td>
                                    <td>
                                        @if($product->image == '' || $product->image == null)
                                            <img src="/storeAdmin/images/demoImage.jpg" height="80px" width="100px">
                                        @else
                                            <a href="/storeAdmin/otherProductImages/{{$product->image}}" target="_blank"><img src="/storeAdmin/otherProductImages/{{$product->image}}" height="80px" width="100px"></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <hr>
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
            </div>
            @if($userType == '501')
                {!! Form::open(['action' => 'storeController\RequisitionsController@approvePurchaseProduct', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                    <div class="row">    
                        <div class="col-md-6">   
                            <div class="form-group">
                                <label>Status</label>
                                {{Form::select('status', ['0'=>'Pending','5'=>'Approved','3'=>'Rejected'], $requisition->status, ['class'=>'form-control', 'placeholder'=>'Select a Status', 'required', 'id'=>'status', ($requisition->status == 0 || $requisition->status == 4)?'required':'disabled'])}}
                            </div>
                        </div>
                        @if($requisition->status == 0 || $requisition->status == 4)
                            <div class="col-md-4">
                                <input type="hidden" value="{{$requisition->id}}" name="reqId" >
                                <input type="submit" value="Save" class="btn btn-primary mt-4">
                            </div> 
                        @else
                            <div class="col-md-3">   
                                <div class="form-group">
                                    <label>Updated by</label>
                                    <input type="text" class="form-control" value="{{$requisition->updated_by}}" name="reqId" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">   
                                <div class="form-group">
                                    <label>Updated At</label>
                                    <input type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($requisition->updated_at))}}" name="reqId" disabled>
                                </div>
                            </div>
                        @endif 
                    </div>
                {!! Form::close() !!} 
            @else
                <div class="row">    
                    <div class="col-md-6">   
                        <div class="form-group">
                            <label>Status</label>
                            {{Form::select('status', ['0'=>'Pending','5'=>'Approved','3'=>'Rejected'], $requisition->status, ['class'=>'form-control', 'placeholder'=>'Select a Status', 'required', 'id'=>'status', 'disabled'])}}
                        </div>
                    </div>
                    <div class="col-md-3">   
                        <div class="form-group">
                            <label>Updated by</label>
                            <input type="text" class="form-control" value="{{$requisition->updated_by}}" name="reqId" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">   
                        <div class="form-group">
                            <label>Updated At</label>
                            <input type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($requisition->updated_at))}}" name="reqId" disabled>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
