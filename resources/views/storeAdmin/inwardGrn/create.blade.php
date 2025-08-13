@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-8"><b style="color:red;">Inward GRN List </b></div>
                            <div  class="col-lg-4">
                                <a href="/inwards/create" class="btn mb-1 btn-primary">Add GRN Inward</a>
                                <a href="/inwards" class="btn mb-1 btn-primary">
                                Inward List <span class="badge badge-danger ml-2">{{$countGRNInwards}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\InwardGrnsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Date</b></label>
                                    <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="forDate" value="{{date('Y-m-d')}}" placeholder="Date" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Invoice No</b></label>
                                    <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="invoiceNo" value="{{$inwardDetail->invoiceNo}}" placeholder="Bill No" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Bill No</b></label>
                                    <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="billNo" value="{{$inwardDetail->billNo}}" placeholder="Bill No" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Requsition Number</b></label>
                                    <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="requisionNo" value="{{$inwardDetail->requisionNo}}" placeholder="Requsition Number" disabled>
                                </div>
                            </div>
                        </div> 
                        <hr>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered mb-0" id="addInProduct">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 0px 6px !important;font-size:13px;" width="3%" class="text-left">No</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;" class="text-left">Product Name</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;" class="text-left">Category</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;" class="text-left">Sub Category</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;"  class="text-left">Company</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(202, 244, 202);" class="text-left">Inward Qty</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(202, 244, 202);" class="text-left">Inward Unit</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(202, 244, 202);" class="text-left">Inward Total</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(239, 194, 194);" class="text-left">Return Qty</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(239, 194, 194);" class="text-left">Return Unit</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(239, 194, 194);" class="text-left">Return Total</th>
                                                <th  style="padding: 0px 6px !important;font-size:13px;" class="text-left">Reason for Return<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body" style="font-size:12px;">
                                            @foreach($inwardProdList as $row)
                                                <tr class="ligth ligth-data">
                                                    <td style="padding: 0px 6px !important;font-size:13px;" width="3%" class="text-left">{{$i++}}<input type="hidden" value="{{$row->productId}}" name="productId[]"></td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;" class="text-left">{{$row->productName}}</td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;" class="text-left">{{$row->categoryName}}</td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;" class="text-left">{{$row->subCategoryName}}</td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;"  class="text-left">{{$row->company}}</td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(202, 244, 202);" class="text-left"><input style="font-size:11px !important; height: 35px !important;" class="form-control" id="inwardQty" type="text" name="inwardQty[]" value="{{$row->qty}}" placeholder="Qty" required></td>                                  
                                                    <td  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(202, 244, 202);" class="text-left">{{Form::select('inwardUnitId', $units, null, ['style'=>'font-size:11px !important; height: 35px !important;', 'class'=>'form-control inwardUnitId', 'placeholder'=>'Select option', 'id'=>'inwardUnitId'])}}</td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(202, 244, 202);" class="text-left"><input style="font-size:11px !important; height: 35px !important;" class="form-control" id="inwardTotal" type="text" name="inwardTotal[]" value="{{$row->amount}}" placeholder="Total" required></td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(239, 194, 194);" class="text-left"><input style="font-size:11px !important; height: 35px !important;" class="form-control" id="returnQty" type="text" name="returnQty[]" value="0" placeholder="Qty" required></td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(239, 194, 194);" class="text-left">{{Form::select('returnUnitId', $units, null, ['style'=>'font-size:11px !important; height: 35px !important;','class'=>'form-control returnUnitId', 'placeholder'=>'Select a Option', 'id'=>'returnUnitId'])}}</td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;background-color:rgb(239, 194, 194);" class="text-left"><input style="font-size:11px !important; height: 35px !important;" class="form-control" id="returnTotal" type="text" name="returnTotal[]" value="0" placeholder="Qty" required></td>
                                                    <td  style="padding: 0px 6px !important;font-size:13px;" class="text-left"><input style="font-size:11px !important; height: 35px !important;" class="form-control" id="returnReason" type="text" name="returnReason[]" value="" placeholder="Reason" required></td>
                                                </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive mb-3">
                                    <table class="table table-border mb-0" id="addInProduct">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="80%" colspan="7" style="font-size:12px !important;" class="text-right">Discount if Any</th>
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="15%"><input style="font-size:13px !important; height: 35px !important;" class="form-control gst" type="text" name="discount" value="0" placeholder="Discount If any" required></th>
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="5%"></th> 
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="80%" colspan="7" style="font-size:12px !important;" class="text-right">GST Amount</th>
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="15%"><input style="font-size:13px !important; height: 35px !important;" class="form-control gst" type="text" name="gst" value="0" placeholder="Discount If any" required></th>
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="5%"></th> 
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="80%" colspan="7" style="font-size:12px !important;" class="text-right">Grand Total</th>
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="15%"><input style="font-size:13px !important; height: 35px !important;" class="form-control grandTotal" type="text" name="grandTotal" value="0" placeholder="Discount If any" required></th>
                                                <th style="padding: 0px 4px !important;font-size:13px;" width="5%"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div> 
                        <hr>
                        <div class="row">    
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label style="font-size:12px !important;">Narration</label>
                                    <input style="font-size:13px !important; height: 35px !important;" type="text" class="form-control" name="narration" value="" placeholder="Narration" required>
                                </div>
                            </div>
                        </div>       
                        <input type="hidden" value="{{$inwardDetail->id}}" name="inwardId">                   
                        <button type="submit" class="btn btn-success mr-2">Save</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
