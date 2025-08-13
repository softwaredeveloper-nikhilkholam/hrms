<?php
$userType = Auth::user()->userType;
$username = Auth::user()->username;
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
                            <div  class="col-lg-7"><b style="color:red;">Add Product</b></div>
                            <div  class="col-lg-5">
                                <a href="/product/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/product/dlist" class="btn mb-1 btn-primary">Deactive List <span class="badge badge-danger ml-2">{{$deactiveCount}}</span></a>
                                <a href="/product" class="btn mb-1 btn-primary">Active List <span class="badge badge-danger ml-2">{{$activeCount}}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ProductsController@updateOpeningStock', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row"> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Product Category</label>
                                    <input type="text" class="form-control productCategory" name="productCategory" id="productCategory" value="{{ $productDetail->categoryName }}" placeholder="Enter productCategory" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Product Sub-Category</label>
                                    <input type="text" class="form-control subCategoryName" name="subCategoryName" id="subCategoryName" value="{{ $productDetail->subCategoryName }}" placeholder="Enter productSubCategory" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Product Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{$productDetail->name}}" placeholder="Enter Product Name"  disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Company / Brand<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" value="{{$productDetail->company}}" name="company" placeholder="Enter Company Name"  disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text" class="form-control" name="unitName" value="{{$productDetail->unitName}}" placeholder="Enter Unit"  disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>        
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Model Number</label>
                                    <input type="text" class="form-control" name="modelNumber" value="{{$productDetail->modelNumber}}" placeholder="Enter Model Number"  disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>       
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Opening Stock</label>
                                    <input type="text" class="form-control openingStock" name="openingStock" id="openingStock" value="{{ $productDetail->openingStock }}" placeholder="Enter Opening Stock" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Opening Stock Date</label>
                                    <input type="date" class="form-control openingStock" name="openingStockForDate" id="openingStockForDate" value="{{ $productDetail->openingStockForDate }}" placeholder="Enter " required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                        </div>
                        <hr>     
                        <input type="hidden" class="form-control" name="productId" value="{{$productDetail->id}}" style="line-height: 18px !important;">
                        <button type="submit" class="btn btn-success mr-2">Update Opening Stock</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
