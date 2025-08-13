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
                        <div  class="col-lg-6"><b style="color:red;">Add Product Return</b></div>
                        <div  class="col-lg-6">
                            <a href="/inwards/productReturn" class="btn mb-1 btn-success">Add Return</a>
                            <a href="/inwards/productReturnList" class="btn mb-1 btn-primary">
                                Return List <span class="badge badge-danger ml-2">{{$productReturns}}</span>
                            </a> 
                            <a href="/requisitions/reqProductReturnList" class="btn mb-1 btn-primary">Req. Product Return</a>                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                    {!! Form::open(['action' => 'storeController\InwardsController@productReturnStore', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Select Branch</b><span style="color:red;">*</span></label>
                                    {{Form::select('branchId', $branches, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Return By Name</b></label>
                                    <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="returnBy" value="" placeholder="Return By Name" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Date</b></label>
                                    <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="forDate" value="{{date('Y-m-d')}}" placeholder="Date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Remark</b></label>
                                    <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="remark" value="" placeholder="Remark" required>
                                </div>
                            </div>
                        </div> 
                         
                        <hr>
                        <div class="row">      
                            <div class="col-lg-12">
                                <hr>
                                <div class="row">      
                                    <div class="col-md-2">
                                        <label style="font-size:12px !important;"><b>Select Category<span style="color:red;">*</span></b></label>
                                        {{Form::select('quotationCategoryId', $categories, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Category','class'=>'form-control', 'id'=>'quotationCategoryId', ''])}}
                                    </div> 
                                    <div class="col-md-2">
                                        <div style="margin-bottom: 0rem;" class="form-group">
                                            <label style="font-size:12px !important;"><b>Select Sub-Category<span style="color:red;">*</span></b></label>
                                            {{Form::select('quotationSubCategoryId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'quotationSubCategoryId', ''])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div style="margin-bottom: 0rem;" class="form-group">
                                            <label style="font-size:12px !important;">Select Product<span style="color:red;">*</span></b></label>
                                            {{Form::select('quotationProductId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Product','class'=>'form-control', 'id'=>'quotationProductId', ''])}}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div style="margin-bottom: 0rem;" class="form-group mt-4">
                                            <label style="font-size:12px !important;"></label>
                                            <button type="button" id="addReturnProductRow" class="btn btn-danger" style="font-size:15px !important;">+&nbsp;&nbsp;Add</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <img src="/storeAdmin/images/basket.jpg" id="showProductImage" width="150px" class="responsive" height="150px">
                                    </div>
                                </div>                        
                                <hr>
                                <div class="row returnProductList">  
                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered mb-0" id="returnProductTable">
                                            <thead class="bg-white text-uppercase">
                                                <tr class="ligth ligth-data">
                                                    <th style="padding: 8px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                                    <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Product Details</th>
                                                    <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Stock</th>
                                                    <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Return Qty</th>
                                                    <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="ligth-body">
                                                
                                            </tbody>
                                        </table>
                                    </div>                      
                                </div>
                            </div>
                        </div>   
                        <hr>
                        <button type="submit" class="btn btn-success mr-2 returnProductButtonSave">Save</button>
                        <button type="reset" class="btn btn-danger  returnProductButtonReset">Reset</button>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
