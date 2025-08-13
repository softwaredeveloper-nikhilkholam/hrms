<?php
 $branchId = Auth::user()->reqBranchId;
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Sub Store Outward</b></div>
                    <div  class="col-lg-5">
                        <a href="/assetProducts/create" class="btn mb-1 btn-primary">Add</a>
                        <a href="/assetProducts/outwardList" class="btn mb-1 btn-primary" style="font-size: 14px !important;">List</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\SubStoresController@storeOutward', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Branch Name</label>
                                <input type="text" value="{{$branchName}}" class="form-control" name="branchId" placeholder="Enter Invoice Number" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Issue Date</label>
                                <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="issueDate" placeholder="Enter Invoice Number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Employee Code</label>
                                <input type="text" class="form-control" name="empCode" placeholder="Enter Employee Code">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                <div class="row">      
                    <div class="col-md-3">
                        <label style="font-size:12px !important;"><b>Select Category<span style="color:red;">*</span></b></label>
                        {{Form::select('reqCategoryId', $categories, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Category','class'=>'form-control', 'id'=>'subStoreReqCategoryId', ''])}}
                    </div> 
                    <div class="col-md-3">
                        <div style="margin-bottom: 0rem;" class="form-group">
                            <label style="font-size:12px !important;"><b>Select Sub-Category<span style="color:red;">*</span></b></label>
                            {{Form::select('reqSubCategoryId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'reqSubCategoryId', ''])}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="margin-bottom: 0rem;" class="form-group">
                            <label style="font-size:12px !important;">Select Product<span style="color:red;">*</span></b></label>
                            {{Form::select('reqProductId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Product','class'=>'form-control', 'id'=>'reqProductId', ''])}}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div style="margin-bottom: 0rem;" class="form-group mt-4">
                            <label style="font-size:12px !important;"></label>
                            <button type="button" id="addSutStoreOutward" class="btn btn-danger" style="font-size:15px !important;">+&nbsp;&nbsp;Add</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <img src="/storeAdmin/images/basket.jpg" id="showProductImage" width="150px" class="responsive" height="150px">
                    </div>
                </div>                        
                <hr>
                <div class="row raiseTheRequestion">  
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0" id="myTable1" width="100%">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                    <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Product Details</th>
                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="8%"  class="text-center">Stock</th>
                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="10%" class="text-center">Issue Qty</th>                                               
                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">Action<?php $i=1; ?></th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                
                            </tbody>
                        </table>
                    </div>                      
                </div>
                </div>
            </div>
            <div class="card">
               
            </div>
                <hr>
                <button type="submit" class="btn btn-success mr-2">Save</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
 $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
    </script>
@endsection
