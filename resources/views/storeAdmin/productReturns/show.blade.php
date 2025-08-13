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
                        <div  class="col-lg-6"><b style="color:red;">Product Return Detail</b></div>
                        <div  class="col-lg-6">
                            <a href="/inwards/productReturn" class="btn mb-1 btn-primary">Add Return</a>
                            <a href="/inwards/productReturnList" class="btn mb-1 btn-primary">
                                Return List <span class="badge badge-danger ml-2">{{$productReturns}}</span>
                            </a>    
                            <a href="/requisitions/reqProductReturnList" class="btn mb-1 btn-primary">Req. Product Return</a>                         
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">      
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Select Branch</b><span style="color:red;">*</span></label>
                            {{Form::select('branchId', $branches, $productReturn->branchId, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Return By Name</b></label>
                            <input type="text" value="{{$productReturn->returnBy}}" style="font-size:13px !important; height: 35px !important;" class="form-control" name="returnBy" disabled placeholder="Return By Name" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Date</b></label>
                            <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="forDate" value="{{$productReturn->forDate}}" placeholder="Date" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Remark</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="remark" value="{{$productReturn->remark}}" placeholder="Remark" disabled>
                        </div>
                    </div>
                </div> 
                    
                <hr>
                <div class="row">      
                    <div class="col-lg-12">
                        <hr>
                        <div class="row ">  
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered mb-0" id="">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th style="padding: 8px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                            <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Product Details</th>
                                            <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Return Qty<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                        @foreach($list as $temp)
                                            <tr>
                                                <td class="text-left">{{$i++}}</td>
                                                <td class="text-left">{{$temp->productName}} <br> Category: {{$temp->categoryName}} | Sub Category: {{$temp->subCategoryName}} | Size: {{$temp->size}} | Color: {{$temp->color}} | Company: {{$temp->companyName}} </td>
                                                <td class="text-left">{{$temp->qty}} {{$temp->unitName}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>                      
                        </div>
                    </div>
                </div>
                
                <div class="row">      
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Updated At</b></label>
                            <input type="text" value="{{date('d-m-Y H:i', strtotime($productReturn->created_at))}}" style="font-size:13px !important; height: 35px !important;" class="form-control" name="returnBy" disabled placeholder="Return By Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Updated By</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="remark" value="{{$productReturn->updated_by}}" placeholder="Remark" disabled>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
