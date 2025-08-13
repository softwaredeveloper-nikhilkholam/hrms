<?php
 $username = Auth::user()->username;
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
                        <div  class="col-lg-7"><b style="color:red;">Purchase Product List</b></div>
                        <div  class="col-lg-5">
                            <a href="/purchaseOrder/productList" class="btn mb-1 btn-success">Pending / Approved List</a>
                            <a href="/purchaseOrder/completedProductList" class="btn mb-1 btn-primary">Purchased List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($products))
                                {!! Form::open(['action' => ['storeController\PurchaseTransactions@updateProducts'], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                    <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                        <thead>
                                            <tr class="ligth">
                                                <th style="font-size:14px;" width="5%">No</th>
                                                <th style="font-size:14px;">Product Details</th>
                                                <th style="font-size:14px;" width="10%">Current Stock</th>
                                                <th style="font-size:14px;" width="10%">Required Qty</th>
                                                <th style="font-size:14px;" width="8%">Action<?php $i=1; ?></th>
                                                <th style="font-size:14px;" width="10%">Purchase Qty</th>
                                                <th style="font-size:14px;" width="15%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:14px;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;" class="text-left">{{ucfirst($row->name)}}<br>{{ucfirst($row->categoryName)}} - {{ucfirst($row->subCategoryName)}}<br>{{$row->size}} - {{$row->color}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;">{{$util->numberFormatRound($row->stock)}} {{$row->unitName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;">{{$util->numberFormatRound($row->requiredStock)}} {{$row->unitName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;">
                                                        @if($row->requiredStockStatus == 0)
                                                            @if($userType == '501')
                                                                <input type="checkbox" style="width:15px;" class="form-control checkPurchaseQty" qtyVal="{{$row->purchaseQty}}" name="check[]" id="checkPurchaseQty" value="{{$row->id}}">
                                                            @else
                                                                <b style="color:red;">{{($row->status == 0)?'Pending':'Approved'}}</b>
                                                            @endif
                                                        @else
                                                            <b style="color:green;">Approved <br> {{date('d-m-Y H:i', strtotime($row->requiredStockTime))}}</b>
                                                        @endif
                                                    </td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;">
                                                        <input type="hidden" value="{{$row->requiredStock}}" id="requiredStock" class="form-control requiredStock" name="requiredStock[]" disabled>
                                                        <input type="text" value="{{$row->purchaseQty}}" id="purchaseQty" class="form-control purchaseQty" name="purchaseQty[]" disabled>
                                                    </td>
                                                    
                                                    <td>
                                                        @if($row->purchaseStatus == '1' && $row->purchaseId != 0 && $userType != '501')
                                                            <a class="badge bg-danger mr-2" data-toggle="tooltip" data-placement="top" title="Purchased Successfully, now Closed the request...." data-original-title="Deactivate" href="/purchaseOrder/purchasedSuccessfully/{{$row->purchaseId}}">Purchased</a>
                                                        @else
                                                            {{Form::select('status[]', ['0'=>'Pending', '1'=>'Approved', '2'=>'Rejected'], $row->purchaseStatus, ["style"=>"font-size:10px !important;", 'placeholder'=>'Select Status','class'=>'form-control prodStatus', 'id'=>'Prodstatus', 'required', 'disabled'])}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($userType == '501')
                                    <div class="row">   
                                        <div class="col-md-5">   
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success" style="margin-top:36px;">Save</button>
                                                <button type="reset" class="btn btn-danger" style="margin-top:36px;">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                {!! Form::close() !!}
                            @else
                                <h4>Record not found</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
