@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-4"><b style="color:red;">Generate Quotation</b></div>
                            <div  class="col-lg-8 text-right">
                                @if(Auth::user()->userType == '701' || Auth::user()->userType == '801')
                                    <a href="/quotation" class="btn mb-1 btn-success">Generate</a>
                                @endif
                                <a href="/quotation/quotationList" class="btn mb-1 btn-primary">Pending List</a>
                                <a href="/quotation/approvedQuotationList" class="btn mb-1 btn-primary">Approved List</a>
                                <a href="/quotation/rejectedQuotationList" class="btn mb-1 btn-primary">Rejected List</a>
                                <a href="/quotation/saveList" class="btn mb-1 btn-primary">Save List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\PurchaseTransactions@generateQuot', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                       <div class="row">
                            <div class="col-md-12">
                                <label style="font-size:12px !important;"><b>Select Vendors<span style="color:red;">*</span></b></label>
                                <select name="vendorId[]" style="width: auto !important;" id="field2" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="console.log(this.selectedOptions)" required>
                                    @foreach($vendors as $vendor)
                                        <option value="{{$vendor->id}}">{{$vendor->name}} - {{$vendor->category}} [{{$vendor->materialProvider}}]</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                                            <button type="button" id="addQuotationProductRow" class="btn btn-danger" style="font-size:15px !important;">+&nbsp;&nbsp;Add</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <img src="/storeAdmin/images/basket.jpg" id="showProductImage" width="150px" class="responsive" height="150px">
                                    </div>
                                </div>                        
                                <hr>
                                <div class="row quotationProductList">  
                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered mb-0" id="quotationProductTable">
                                            <thead class="bg-white text-uppercase">
                                                <tr class="ligth ligth-data">
                                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                                    <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Product Details</th>
                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Company</th>
                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Stock</th>
                                                    <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Qty</th>                                               
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
                        <button type="submit" class="btn btn-success btn-lg mr-2 mt-2">Next</button>
                        <button type="reset" class="btn btn-danger btn-lg mr-2 mt-2">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
