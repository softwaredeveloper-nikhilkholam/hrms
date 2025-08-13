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
                            <a href="/outwards/create" class="btn mb-1 btn-primary">Add Outward</a>
                            <a href="/outwards" class="btn mb-1 btn-primary">
                            Outward List <span class="badge badge-danger ml-2">{{$countOutward}}</span>
                            </a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\OutwardsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">      
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>Date</b></label>
                                <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="forDate" value="{{date('Y-m-d')}}" placeholder="Date" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>Requsition Number</b></label>
                                <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="requisitionNo" value="" placeholder="Requsition Number" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>Date Of Requisition</b></label>
                                <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="dateOfRequisition" value="{{date('Y-m-d')}}" placeholder="Date" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>Branch Name</b></label>
                                <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="branchName" value="" placeholder="Branch Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>Requisition For</b></label>
                                <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="requisitionFor" value="" placeholder="Requisition For">
                            </div>
                        </div> 
                    </div> 
                    <hr>
                    <div class="row">      
                        <div class="col-md-2">
                            <label style="font-size:12px !important;"><b>Select Category<span style="color:red;">*</span></b></label>
                            {{Form::select('categoryId', $categories, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Category','class'=>'form-control', 'id'=>'categoryId', ''])}}
                        </div>
                        <div class="col-md-2">
                            <div style="margin-bottom: 0rem;" class="form-group">
                                <label style="font-size:12px !important;"><b>Select Sub-Category<span style="color:red;">*</span></b></label>
                                {{Form::select('subCategoryId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'subCategoryId', ''])}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="margin-bottom: 0rem;" class="form-group">
                                <label style="font-size:12px !important;">Select Product<span style="color:red;">*</span></b></label>
                                {{Form::select('productId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Product','class'=>'form-control productId', 'id'=>'productId', ''])}}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div style="margin-bottom: 0rem;" class="form-group mt-5">
                                <label style="font-size:12px !important;"></label>
                                <button type="button" id="addOutwardNewRow" class="btn btn-danger" style="font-size:12px !important;">Add</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <img src="" id="showProductImage" width="150px" class="responsive" height="150px">
                        </div>
                    </div>                        
                    <hr>
                    <div class="row generateQuotation1">  
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered mb-0" id="myTable1">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Product Name</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Category</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Sub Category</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Size</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Color</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Company</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Stock</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Qty</th>                                               
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Returnable</th>                                               
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Due Date</th>                                               
                                        <th  style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">Action<?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                  
                    <div class="row">    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>Narration</b></label>
                                <input style="font-size:13px !important; height: 35px !important;" type="text" class="form-control" name="narration" value="" placeholder="Narration" required>
                            </div>
                        </div>
                    </div> 
                    <button type="submit" class="btn btn-success mr-2">Add Outward</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
