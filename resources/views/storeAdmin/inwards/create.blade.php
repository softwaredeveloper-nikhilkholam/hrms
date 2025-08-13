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
                        <div  class="col-lg-8"><b style="color:red;">Inward / GRN List</b></div>
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
                {!! Form::open(['action' => 'storeController\InwardsController@create', 'method' => 'GET', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-size:12px !important;"><b>PO Number</b></label>
                                <input type="text" style="font-size:10px !important; height: 30px !important;" class="form-control" id="poNumber" name="poNumber" value="" placeholder="Enter PO Number & Search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success"  style="margin-top: 35px !important;">Search PO</button>
                        </div>
                    </div> 
                {!! Form::close() !!}
                @if(!empty($poNumber))
                    {!! Form::open(['action' => 'storeController\InwardsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-2">
                                <label style="font-size:12px !important;"><b>Select Vendor</b><span style="color:red;">*</span></label>
                                {{Form::select('inwardVendorId', $vendors, $pOrder->vendorId, ["style"=>"font-size:10px !important; height: 30px !important;", 'placeholder'=>'Select Vendor','class'=>'form-control', 'id'=>'inwardVendorId', 'readonly'])}}
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Vendor Address</b></label>
                                    <input type="text" style="font-size:10px !important; height: 30px !important;" class="form-control" id="" name="vendorAddress" value="{{$pOrder->address}}" placeholder="Vendor Address" readonly>
                                    <input type="hidden" value="{{$pOrder->vendorId}}" name="vendorId">
                                </div>
                            </div>
                        </div> 
                        <div class="row">      
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Date</b></label>
                                    <input type="date" style="font-size:10px !important; height: 30px !important;" class="form-control" name="forDate" value="{{date('Y-m-d')}}" placeholder="Date" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>PO No</b></label>
                                    <input type="text" style="font-size:10px !important; height: 30px !important;" class="form-control" name="poNumber" value="{{$poNumber}}" placeholder="PO No" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Bill No</b></label>
                                    <input type="text" style="font-size:10px !important; height: 30px !important;" class="form-control" name="billNo" value="" placeholder="Bill No" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Security Gate No.</b></label>
                                    <input type="text" style="font-size:10px !important; height: 30px !important;" class="form-control" name="securityGateNo" value="" placeholder="Security Gate No">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Requsition Number</b></label>
                                    <input type="text" style="font-size:10px !important; height: 30px !important;" class="form-control" name="reqNo" value="{{$pOrder->reqNo}}" placeholder="Requsition Number" readonly>
                                </div>
                            </div>
                        </div> 

                        <hr>
                        <div class="ro4 mt-4">
                            <div class="col-lg-12">
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered mb-0" id="addInProduct">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth4data">
                                                <th style="padding: 8px 4px !important;font-size:12px;" width="3%" class="text-center">No</th>
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center">Product</th>
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center" width="12%">Qty & Rate</th> 
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center" width="12%">Gross Rs.</th>   
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center" width="12%">CGST % & Rs.</th>   
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center" width="12%">SGST % & Rs.</th>   
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center" width="12%">IGST % & Rs.</th>   
                                                <th  style="padding: 8px 4px !important;font-size:12px;" class="text-center" width="12%">Total<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body" id="" style="font-size:12px;">
                                            <?php $products = $util->getQuotProdList($pOrder->quotationId); $k=1;$total=0; ?>
                                            @if(count($products))
                                                @foreach($products as $product)
                                                    <tr  class="success">
                                                        <td style="padding: 7px 7px !important;">{{$k++}}<input type="hidden" value="{{$product->productId}}" name="productId[]"></td>
                                                        <td style="padding: 7px 7px !important;">{{$product->name}}
                                                            Company: {{$product->company}}<br>
                                                            Color: {{$product->color}}&nbsp;&nbsp; | &nbsp;&nbsp; Size: {{$product->size}}
                                                            <br><b>Expiry:</b> <input type="date" min="{{date('Y-m-d')}}" style="font-size:10px !important; height: 30px !important;" class="form-control" name="expiry[]" placeholder="" >
                                                        <input type="hidden" value="{{$product->qty}}" style="font-size:10px !important; height: 30px !important;" class="form-control" name="qty[]" placeholder="Actual Qty" ></td>
                                                        <td style="padding: 7px 7px !important;">
                                                            <input type="text" value="{{$product->qty}}" style="font-size:10px !important; height: 30px !important;" class="form-control" name="actualQty[]" placeholder="Actual Qty" ><br>Rs. {{$util->numberFormat($product->unitPrice)}}<br>Per {{$product->unitName}}
                                                        <input type="hidden" value="{{$product->unitPrice}}" name="unitPrice[]"></td>
                                                        <td style="padding: 7px 7px !important;">Rs. <input type="text" value="{{$product->unitPrice*$product->qty}}" style="font-size:10px !important; height: 30px !important;" class="form-control" name="grossAmount[]" placeholder="HSN Code" ></td>
                                                        <td style="padding: 7px 7px !important;">
                                                            Percent %<br><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="CGSTPercent[]" placeholder="HSN Code" >
                                                            <hr>Rs.<br><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="CGSTRs[]" placeholder="HSN Code" >
                                                        </td>
                                                        <td style="padding: 7px 7px !important;">
                                                            Percent %<input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="SGSTPercent[]" placeholder="HSN Code" >
                                                            <hr>Rs.<br><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="SGSTRs[]" placeholder="HSN Code" >
                                                        </td>
                                                        <td style="padding: 7px 7px !important;">
                                                            Percent %<input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="IGSTPercent[]" placeholder="HSN Code" >
                                                            <hr>Rs.<br><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="IGSTRs[]" placeholder="HSN Code" >
                                                        </td>
                                                        <td style="padding: 7px 7px !important;">
                                                            Rs. <input type="text" value="{{$product->unitPrice*$product->qty}}" style="font-size:10px !important; height: 30px !important;" class="form-control" name="total[]" ><?php $total = $total + ($product->unitPrice*$product->qty); ?>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="5"></th>
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Sub Total</th>
                                                        <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="8%"><input type="text" value="{{$total}}" style="font-size:10px !important; height: 30px !important;" class="form-control" name="subTotal" placeholder="Sub Total" ></th>
                                                    </tr>
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="5"></th>
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Discount</th>
                                                        <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="8%"><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="discount" placeholder="Discount" ></th>
                                                    </tr> 
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="5"></th>
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">GST Rs.</th>
                                                        <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="8%"><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="gstRs" placeholder="GSTRs" ></th>
                                                    </tr>
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="5"></th>
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Labour Charages</th>
                                                        <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="8%"><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="labCharges" placeholder="labCharges" ></th>
                                                    </tr>
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="5"></th>
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Other Charges</th>
                                                        <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="8%"><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="otherCharges" placeholder="otherCharges" ></th>
                                                    </tr>
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="5"></th>
                                                        <th style="padding: 10px 10px !important;font-size:13px;" class="text-center" colspan="2">Total</th>
                                                        <th  style="padding: 10px 10px !important;font-size:13px;" class="text-center" width="8%"><input type="text" value="0" style="font-size:10px !important; height: 30px !important;" class="form-control" name="netTotal" placeholder="Total" ></th>
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
                                    <label style="font-size:12px !important;"><b>Invoice Image</b></label>
                                    <input type="file" accept="image/*" style="font-size:10px !important; height: 30px !important;line-height: 19px !important;" class="form-control" name="billImage" value="" placeholder="billImage">
                                </div>
                            </div>  
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label style="font-size:12px !important;"><b>Narration</b></label>
                                    <input style="font-size:10px !important; height: 30px !important;" type="text" class="form-control" name="narration" value="" placeholder="Narration" required>
                                </div>
                            </div>
                        </div> 

                        <button type="submit" class="btn btn-success mr-2">Add Inward</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
