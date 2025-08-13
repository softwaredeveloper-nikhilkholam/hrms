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
                        <div  class="col-lg-8"><b style="color:red;">Inward / GRN Details</b></div>
                        <div  class="col-lg-4">
                            <a href="/inwards/create" class="btn mb-1 btn-primary">Add Inward / GRN</a>
                            <a href="/inwards" class="btn mb-1 btn-primary">
                                Inward / GRN List <span class="badge badge-danger ml-2">{{$countInwards}}</span>
                            </a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">      
                    <div class="col-md-3">
                        <label style="font-size:12px !important;"><b>Select Vendor</b><span style="color:red;">*</span></label>
                        {{Form::select('inwardVendorId', $vendors, $inward->vendorId, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Vendor','class'=>'form-control', 'id'=>'inwardVendorId', 'disabled'])}}
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Vendor Address</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" id="" name="vendorAddress" value="{{$inward->vendorAddress}}" placeholder="Vendor Address" disabled>
                        </div>
                    </div>
                </div> 
                <div class="row">      
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Date</b></label>
                            <input type="date" style="font-size:13px !important; height: 35px !important;" class="form-control" name="forDate" value="{{$inward->forDate}}" placeholder="Date" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>PO No</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="poNumber" value="{{$inward->poNumber}}" placeholder="PO No" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Bill No</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="billNo" value="{{$inward->billNo}}" placeholder="Bill No" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Security Gate No.</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="securityGateNo" value="{{$inward->securityGateNo}}" placeholder="Security Gate No" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Invoice No</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="billNo" value="{{$inward->invoiceNo}}" placeholder="Bill No" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>GRN Number</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="reqNo" value="{{$inward->id}}" placeholder="Requsition Number" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Requsition Number</b></label>
                            <input type="text" style="font-size:13px !important; height: 35px !important;" class="form-control" name="reqNo" value="{{$inward->reqNo}}" placeholder="Requsition Number" disabled>
                        </div>
                    </div>
                </div> 
                <hr>
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered mb-0" id="">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th style="padding: 8px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center">Product Details</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">Expiry</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">HSN CODE</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">Unit</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="7%">Qty</th> 
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">Rate</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="7%">Gross Rs.</th>   
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">CGST %</th>   
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">CGST Rs.</th>
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">SGST %</th>   
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">SGST Rs.</th> 
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">IGST %</th>   
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">IGST Rs.</th>     
                                        <th  style="padding: 8px 4px !important;font-size:13px;" class="text-center" width="5%">Total<?php $k=1;$tot=$totgrossAmount=$totCGSTRs=$totSGSTRs=$totIGSTRs=0; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body" id="" style="font-size:12px;">
                                    @if(count($productList))
                                        @foreach($productList as $product)
                                            <tr  class="success">
                                                <td style="padding: 7px 7px !important;">{{$k++}}<input type="hidden" value="{{$product->productId}}" name="productId[]"></td>
                                                <td style="padding: 7px 7px !important;" class="text-left">{{$product->name}}<br>Company: {{$product->company}}<br>Color: {{$product->color}} | Size: {{$product->size}}
                                            <br>Hall: {{$product->hallName}} | Rack: {{$product->rackName}} | Shelf: {{$product->shelfName}}</td>
                                                <td style="padding: 7px 7px !important;">{{($product->expiryDate == '')?'-':date('d-m-Y', strtotime($product->expiryDate))}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->HSNCode}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->unitName}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->qty}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->rate}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->grossAmount}}<?php $totgrossAmount = $totgrossAmount + $product->grossAmount; ?></td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->CGSTPercent}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->CGSTRs}}<?php $totCGSTRs = $totCGSTRs + $product->CGSTRs; ?></td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->SGSTPercent}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->SGSTRs}}<?php $totSGSTRs = $totSGSTRs + $product->SGSTRs; ?></td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->IGSTPercent}}</td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->IGSTRs}}<?php $totIGSTRs = $totIGSTRs + $product->IGSTRs; ?></td>                                    
                                                <td style="padding: 7px 7px !important;">{{$product->total}}<?php $tot = $tot + $product->total; ?></td>                                    
                                            </tr>
                                        @endforeach
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" width="3%" class="text-center" colspan="7">Total</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="7%">{{$totgrossAmount}}</th>   
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%"></th>   
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$totCGSTRs}}</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%"></th>   
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$totSGSTRs}}</th> 
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%"></th>   
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$totIGSTRs}}</th>     
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$tot}}</th>
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="12"></th>
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Sub Total</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$tot}}</th>
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="12"></th>
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Discount</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$inward->discount}}</th>
                                            </tr> 
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="12"></th>
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">GST Rs.</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$inward->gstRs}}</th>
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="12"></th>
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Labour Charages</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$inward->labCharges}}</th>
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="12"></th>
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Other Charges</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$inward->otherCharges}}</th>
                                            </tr>
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="12"></th>
                                                <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Total</th>
                                                <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="5%">{{$inward->netTotal}}</th>
                                            </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>                               
                    </div>
                </div> 
                <hr>
                <div class="row">    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Invoice Image</b></label><br>
                            <b><a href="/storeAdmin/inwardImages/{{$inward->billImage}}" target="_blank">Click here</a></b>
                        </div>
                    </div>  
                    <div class="col-md-10">
                        <div class="form-group">
                            <label style="font-size:12px !important;"><b>Narration</b></label>
                            <input style="font-size:13px !important; height: 35px !important;" type="text" class="form-control" name="narration" value="{{$inward->narration}}" placeholder="Narration" readonly>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
