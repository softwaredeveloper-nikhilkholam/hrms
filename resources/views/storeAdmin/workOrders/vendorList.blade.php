@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="col-lg-8"><b style="color:red;">Generate Work Order</b></div>
                            <div class="col-lg-4 text-right">
                                <a href="/workOrder" class="btn mb-1 btn-primary" style="font-size: 14px !important;background-color: #4432ea !important;border-color: #4432ea !important;">Generate Work Order</a>
                                <a href="/workOrder/orderList" class="btn mb-1 btn-primary"  style="font-size: 14px !important;background-color: #4432ea !important;border-color: #4432ea !important;">
                                    Work List <span class="badge badge-danger ml-2">{{count($wOrders)}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\WorkOrdersController@generateWorkOrder', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
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
                        <button type="submit" class="btn btn-success btn-lg mr-2 mt-2">Next</button>
                        <button type="reset" class="btn btn-danger btn-lg mr-2 mt-2">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
