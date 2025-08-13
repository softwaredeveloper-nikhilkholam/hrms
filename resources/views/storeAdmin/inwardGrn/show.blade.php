@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-3"><b style="color:red;">Inward Details</b></div>
                        <div  class="col-lg-4">
                            <a href="/inwards/create" class="btn mb-1 btn-primary">Add Inward</a>
                            <a href="/inwards" class="btn mb-1 btn-primary">
                                Inward List <span class="badge badge-danger ml-2">{{$countInwards}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                <div class="card-body">
                    <div class="row">      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="forDate" value="{{$inward->forDate}}" placeholder="Date" disabled>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>PO Number</label>
                                <input type="text" class="form-control" name="PONo" value="{{$inward->PONo}}" placeholder="PO Number" disabled>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Bill No</label>
                                <input type="text" class="form-control" name="billNo" value="{{$inward->billNo}}" placeholder="Bill No" disabled>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Requsition Number</label>
                                <input type="text" class="form-control" name="requisionNo" value="{{$inward->requisionNo}}" placeholder="Requsition Number" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Select Vendor<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="requisionNo" value="{{$inward->name}}" placeholder="Requsition Number" disabled>
                        </div>
                    </div> 
                    <hr>
                        
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered mb-0" id="addInProduct">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th style="font-size:13px;" width="5%" class="text-left">Sr. No</th>
                                            <th style="font-size:13px;" width="10%" class="text-left">Category</th>
                                            <th style="font-size:13px;" width="10%" class="text-left">Sub Category</th>
                                            <th style="font-size:13px;" width="15%" class="text-left">Product Name</th>
                                            <th style="font-size:13px;" width="15%" class="text-left">Company Name</th>
                                            <th style="font-size:13px;" width="10%" class="text-left">Unit</th>
                                            <th style="font-size:13px;" width="10%" class="text-left">Size</th>
                                            <th style="font-size:13px;" width="10%" class="text-left">Color</th>
                                            <th style="font-size:13px;" width="10%" class="text-left">Expiry</th>
                                            <th style="font-size:13px;" width="15%" class="text-left">Qty<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body" id="addInProductBody" style="font-size:12px;">
                                        @foreach($productList as $list)
                                            <tr>
                                                <td style="font-size:12px;" class="text-left">{{$i++}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->categoryName}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->subCategoryName}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->name}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->company}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->unitName}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->size}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->color}}</td>
                                                <td style="font-size:12px;" class="text-left">{{($list->expiryDate != '')?date('d-m-Y', strtotime($list->expiryDate)):'-'}}</td>
                                                <td style="font-size:12px;" class="text-left">{{$list->qty}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="table table-border mb-0" id="addInProduct">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th width="80%" colspan="7" class="text-right">Discount If any</th>
                                            <th width="15%"><input class="form-control discount" type="number" name="discount" value="{{$inward->discount}}" placeholder="Discount If any" disabled></th>
                                            <th width="5%"></th>
                                        </tr>
                                        <tr class="ligth ligth-data">
                                            <th width="80%" colspan="7" class="text-right">GST Amount</th>
                                            <th width="15%"><input class="form-control gst" type="text" name="gst" value="{{$inward->gst}}" placeholder="Discount If any" disabled></th>
                                            <th width="5%"></th> 
                                        </tr>
                                        <tr class="ligth ligth-data">
                                            <th width="80%" colspan="7" class="text-right">Grand Total</th>
                                            <th width="15%"><input class="form-control grandTotal" type="text" name="grandTotal" value="{{$inward->grandTotal}}" placeholder="Discount If any" disabled></th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <hr>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Narration</label>
                                <input type="text" class="form-control" name="narration" value="{{$inward->narration}}" placeholder="Narration" disabled>
                            </div>
                        </div>
                    </div>  
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Added At</label>
                                <input type="text" class="form-control" name="narration" value="{{date('d-m-Y H:i', strtotime($inward->created_at))}}" placeholder="Narration" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Added By</label>
                                <input type="text" class="form-control" name="narration" value="{{$inward->updated_by}}" placeholder="Narration" disabled>
                            </div>
                        </div>
                    </div>                               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
