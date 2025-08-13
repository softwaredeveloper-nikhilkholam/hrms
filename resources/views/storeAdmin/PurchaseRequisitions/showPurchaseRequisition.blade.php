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
                    <div  class="col-lg-4"><b style="color:red;">Show Purchase Requisition Details</b></div>
                    <div  class="col-lg-8">
                        <a href="/requisitions/raisePurchaseRequisition" class="btn mb-1 btn-primary">Raise Requisition</a>
                        <a href="/requisitions/purchaseRequisitionList" class="btn mb-1 btn-primary">Pending List</a>                           
                        <a href="/requisitions/approvedPurchaseRequisitionList" class="btn mb-1 btn-primary">Approved List</a>                           
                        <a href="/requisitions/completedPurchaseRequisitionList" class="btn mb-1 btn-primary">Completed List</a>                           
                        <a href="/requisitions/rejectedPurchaseRequisitionList" class="btn mb-1 btn-primary">Rejected List</a>                            
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
                            <td class="text-left" colspan=2><b>Requisition Number:</b> {{$requisition->requisitionNo}}</td>
                        </tr>
                        <tr style="border: 1px solid #DCDFE8;">
                            <td class="text-left" colspan=4><b>Event Details / Requisition For:</b> {{$requisition->requisitionFor}}</td>
                        </tr>
                    </table>
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
                                    <td>{{$product->productName}}</td>
                                    <td>{{$product->qty}}</td>
                                    <td>{{$product->description}}</td>
                                    <td>
                                        @if($product->image == '' || $product->image == null)
                                            NA
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
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0" border="0">
                            <tr style="border: 1px solid #DCDFE8;">
                                <td class="text-left"><b>Status:</b> {{($requisition->status == 0)?'Pending':(($requisition->status == 5)?'Approved':'Rejected')}}</td>
                                <td class="text-left"><b>Updated At:</b> {{date('d-m-Y H:i', strtotime($requisition->updated_at))}}</td>
                                <td class="text-left"><b>Updated by:</b> {{$requisition->updated_by}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
