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
                        <div  class="col-lg-4"><b style="color:red;">Generate Quotation</b></div>
                        <div  class="col-lg-8">
                            @if(Auth::user()->userType == '701' || $userType == '801')
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
                {!! Form::open(['action' => 'storeController\PurchaseTransactions@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">  
                        @if(count($vendorDetails) >= 1)
                            <?php $temp=$vendorDetails[0]; ?>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                        <h4>Quotation 1</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">  
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Name & Address of Company</b></label>
                                                    <h5>{{$temp->name}}</h5>
                                                    <h6>Address: {{$temp->address}}</h6>
                                                    <b>Material Provider: {{$temp->materialProvider}}</b>
                                                    <input type="hidden" value="{{$temp->id}}" name="vendorId[]">
                                                </div>
                                            </div>    
                                            <div class="col-md-3">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Bank Details</b></label>
                                                    <h5>A/C No : {{$temp->accountNo}}</h5>
                                                    <h6>IFSC Code : {{$temp->IFSCCode}}</h6>
                                                    <b>{{$temp->bankBranch}}</b>
                                                </div>
                                            </div>   
                                            <div class="col-md-3"></div>
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Date</b><span style="color:red;font-size:25px;">*</span></label>
                                                    <input type="date" value="{{date('Y-m-d')}}" name="validDate[]" style="font-size:12px;" placeholder="Date" class="form-control" required>
                                                </div>
                                            </div>  
                                        </div>  
                                        <div class="row mt-4">  
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Shipping Address</b><span style="color:red;font-size:25px;">*</span></label>
                                                    {{Form::select('shippingAddress[]', $branches, NULL, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Type of Company</b><span style="color:red;font-size:25px;">*</span></label>
                                                    {{Form::select('typeOfCompany[]', $typeOfCompanies,  NULL, ['class'=>'form-control', 'placeholder'=>'Select Option', 'required', 'id'=>'typeOfCompany', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                                </div>
                                            </div>                                        
                                                    
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Advance payment (%)</b><span style="color:red;font-size:25px;">*</span></label>
                                                    <input type="text" style="font-size:12px;" maxlength="100" placeholder="Terms of payment" name="termOfPayment[]" value="" onkeypress="return isNumberKey(event)" name="termOfPayment[]" class="form-control" required>
                                                </div>
                                            </div>                                 
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Tentative delivery date</b><span style="color:red;font-size:25px;">*</span></label>
                                                    <input type="date" value="{{date('Y-m-d')}}" style="font-size:12px;" value="" name="tentativeDeliveryDate[]" placeholder="Tentative delivery date" class="form-control" required>
                                                </div>
                                            </div>   
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Already Paid</b><span style="color:red;font-size:25px;">*</span></label>
                                                    {{Form::select('alreadyPaid[]', ['1'=>'Yes','0'=>'No'], NULL, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'alreadyPaid'])}}
                                                </div>
                                            </div>   
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                                    <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy[]" placeholder="Already Paid By(Employee Code)" class="form-control">
                                                </div>
                                            </div>        
                                            <div class="col-md-8">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Quotation For</b><span style="color:red;font-size:25px;">*</span></label>
                                                    <input type="text" style="font-size:12px;" placeholder="Quotation For" name="quotationFor[]" id="quotationFor1"  class="form-control" required>
                                                </div>
                                            </div>         
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Requisition No</b><span style="color:red;font-size:25px;">*</span></label>
                                                    <input type="text" style="font-size:12px;" placeholder="Requisition No" name="requisitionNo[]"  id="requisitionNo1" value="" class="form-control" required>
                                                </div>
                                            </div>  
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Upload Quotation</b><span style="color:red;font-size:25px;">*</span></label>
                                                    <input type="file" style="font-size:12px;line-height: 18px !important;" placeholder="Requisition No" name="quotationFile[]"  id="quotationFile" value="" class="form-control" required>
                                                </div>
                                            </div>                                              
                                        </div>  
                                        <hr>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-bordered mb-0" id="quotationProductTable1">
                                                <thead class="bg-white text-uppercase">
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Products </th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Qty</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Date</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Unit Price</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">CGST %</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">SGST %</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Discount</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Amount<?php $k=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="ligth-body">
                                                    @if($prodCt = count($productId))
                                                        @for($i=0; $i<$prodCt; $i++)
                                                            <?php $productDetails = $util->getProductDetail($productId[$i]);?>
                                                            <tr>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$k++}}</td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-left">{{$productName[$i]}}<input class="form-control" type="hidden" name="productId1[]" value="{{$productId[$i]}}"><br>Category: {{$categoryName[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; SubCategory: {{$subCategoryName[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; 
                                                                {{$color[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; {{$size[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; {{$company[$i]}}<br>
                                                                <input style="height: 29px;font-size:12px;color:black;" class="form-control" type="text" id="" name="remark1[]" value="" placeholder="If any Remark">
                                                            </td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$qty[$i]}}<input class="form-control qty1"  id="qty1" type="hidden" name="qty1[]" value="{{$qty[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control" type="date" id="" name="forDate1[]" value="{{date('Y-m-d')}}" placeholder=""></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control unitPrice1" onkeypress="return isNumberKey(event)" type="text" id="unitPrice1" name="unitPrice1[]" value="{{$unitPrice[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control cgst1" onkeypress="return isNumberKey(event)" type="text" id="cgst1" name="cgst1[]" value="{{$cgst[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control sgst1" onkeypress="return isNumberKey(event)" type="text" id="sgst1" name="sgst1[]" value="{{$sgst[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control discount1" onkeypress="return isNumberKey(event)" type="text" id="discount1" name="discount1[]" value="{{$discount[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control amount1" onkeypress="return isNumberKey(event)" type="text" id="amount1" name="amount1[]" value="{{$amount[$i]}}" placeholder="amount" readonly></td>
                                                            </tr>
                                                        @endfor
                                                            <tr>
                                                                <td width="80%" colspan="8"  style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Total Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" value="" onkeypress="return isNumberKey(event)"  name="totalRs[]" class="form-control total" id="totalRs1" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Transportation Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="transportationRs[]" class="form-control" value="0" id="transportationRs1" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Loading Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="loadingRs[]" class="form-control" value="0" id="loadingRs1" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Unloading Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="unloadingRs[]" class="form-control" value="0" id="unloadingRs1" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="font-size:12px;color:black;" class="text-right"><b>Final Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" value="0" name="finalRs[]" class="form-control" id="finalRs1" readonly></td>
                                                            </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @endif                          
                    </div>  
                    <div class="row">  
                        @if(count($vendorDetails) >= 2)
                            <?php $temp=$vendorDetails[1]; ?>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                        <h4>Quotation 2</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">  
                                        <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Name & Address of Company</b></label>
                                                    <textarea class="form-control" style="font-size:12px;height: 112px !important;" placeholder="Name & Address of Company" disabled>{{$temp->name}}&#10;Address: {{$temp->address}}</textarea>
                                                    <input type="hidden" value="{{$temp->id}}" name="vendorId[]">
                                                </div>
                                            </div>   
                                            <div class="col-md-3">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Bank Details</b></label>
                                                    <textarea class="form-control" rows="3" style="font-size:12px;height: 112px !important;" placeholder="Bank Details" disabled>A/C No : {{$temp->accountNo}}&#10;IFSC Code : {{$temp->IFSCCode}}&#10;Bank Branch : {{$temp->bankBranch}}</textarea>
                                                </div>
                                            </div>   
                                            <div class="col-md-3">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Address</b></label>
                                                    <textarea class="form-control" style="font-size:12px;height: 112px !important;" name="address[]" placeholder="Address" disabled>Above hotel Shree Kateel, next to Amit Bloofield, near to Navle Bridge, Narhe, Pune, Maharashtra 411046</textarea>
                                                </div>
                                            </div>         
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Date</b></label>
                                                    <input type="date" value="{{date('Y-m-d')}}" name="validDate[]" style="font-size:12px;" placeholder="Date" class="form-control" required>
                                                </div>
                                            </div>   
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Shipping Address</b></label>
                                                    {{Form::select('shippingAddress[]', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'shippingAddress', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                                </div>
                                            </div>                                
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Type of Company</b></label>
                                                    {{Form::select('typeOfCompany[]', $typeOfCompanies, null, ['class'=>'form-control', 'placeholder'=>'Select Option', 'required', 'id'=>'typeOfCompany', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                                </div>
                                            </div>          
                                                    
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Advance payment (%)</b></label>
                                                    <input type="text" style="font-size:12px;" maxlength="100" placeholder="Terms of payment" name="termOfPayment[]" value="0" onkeypress="return isNumberKey(event)" name="termOfPayment[]" class="form-control" required>
                                                </div>
                                            </div>                                 
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Tentative delivery date</b></label>
                                                    <input type="date" value="{{date('Y-m-d')}}" style="font-size:12px;" name="tentativeDeliveryDate[]" placeholder="Tentative delivery date" class="form-control" required>
                                                </div>
                                            </div>   
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Already Paid</b></label>
                                                    {{Form::select('alreadyPaid[]', ['1'=>'Yes','0'=>'No'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'alreadyPaid'])}}
                                                </div>
                                            </div>   
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                                    <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy[]" placeholder="Already Paid By(Employee Code)" class="form-control">
                                                </div>
                                            </div>                
                                            <div class="col-md-8">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Quotation For</b></label>
                                                    <input type="text" style="font-size:12px;" placeholder="Quotation For" name="quotationFor[]" id="quotationFor2" value="{{$quotationFor}}" class="form-control" required>
                                                </div>
                                            </div>         
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Requisition No</b></label>
                                                    <input type="text" style="font-size:12px;" placeholder="Requisition No"  name="requisitionNo[]" id="requisitionNo2" value="{{$requisitionNo}}" class="form-control" required>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Upload Quotation</b></label>
                                                    <input type="file" style="font-size:12px;line-height: 18px !important;" placeholder="Requisition No" name="quotationFile[]"  id="quotationFile" value="" class="form-control">
                                                </div>
                                            </div>                                            
                                        </div> 
                                            
                                        <hr>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-bordered mb-0" id="quotationProductTable2">
                                                <thead class="bg-white text-uppercase">
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Product </th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Qty</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Date</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Unit Price</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">CGST %</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">SGST %</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Discount</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Amount<?php $k=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="ligth-body">
                                                    @if($prodCt = count($productId))
                                                        @for($i=0; $i<$prodCt; $i++)
                                                            <tr>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$k++}}</td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-left">{{$productName[$i]}}<input class="form-control" type="hidden" name="productId2[]" value="{{$productId[$i]}}"><br>Category: {{$categoryName[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; SubCategory: {{$subCategoryName[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; 
                                                                {{$color[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; {{$size[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; {{$company[$i]}}<br>
                                                                <input style="height: 29px;font-size:12px;color:black;" class="form-control" type="text" id="" name="remark2[]" value="" placeholder="If any Remark"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$qty[$i]}}<input class="form-control qty2"  id="qty2" type="hidden" name="qty2[]" value="{{$qty[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control" type="date" id="" name="forDate2[]" value="{{date('Y-m-d')}}" placeholder=""></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)"  class="form-control unitPrice2" type="text" id="unitPrice2" name="unitPrice2[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control cgst2" type="text" id="cgst2" name="cgst2[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control sgst2" type="text" id="sgst2" name="sgst2[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control discount2" type="text" id="discount2" name="discount2[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control amount2" type="text" id="amount2" name="amount2[]" value="0" placeholder="amount" readonly></td>
                                                            </tr>
                                                        @endfor
                                                            <tr>
                                                                <td width="80%" colspan="8"  style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Total Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" value="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="totalRs[]" class="form-control total" id="totalRs2" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Transportation Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="transportationRs[]" class="form-control" value="0" id="transportationRs2" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Loading Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="loadingRs[]" class="form-control" value="0" id="loadingRs2" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Unloading Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="unloadingRs[]" class="form-control" value="0" id="unloadingRs2" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="font-size:12px;color:black;" class="text-right"><b>Final Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" value="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="finalRs[]" class="form-control" id="finalRs2" readonly></td>
                                                            </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif                          
                    </div>  
                    <div class="row">  
                        @if(count($vendorDetails) >= 3)
                            <?php $temp=$vendorDetails[2]; ?>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                        <h4>Quotation 3</h4>
                                    </div>
                                    <div class="card-body">
                                    <div class="row">  
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Name & Address of Company</b></label>
                                                    <textarea class="form-control" style="font-size:12px;height: 112px !important;" placeholder="Name & Address of Company" disabled>{{$temp->name}}&#10;Address: {{$temp->address}}</textarea>
                                                    <input type="hidden" value="{{$temp->id}}" name="vendorId[]">
                                                </div>
                                            </div>   
                                            <div class="col-md-3">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Bank Details</b></label>
                                                    <textarea class="form-control" rows="3" style="font-size:12px;height: 112px !important;" placeholder="Bank Details" disabled>A/C No : {{$temp->accountNo}}&#10;IFSC Code : {{$temp->IFSCCode}}&#10;Bank Branch : {{$temp->bankBranch}}</textarea>
                                                </div>
                                            </div>   
                                            <div class="col-md-3">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Address</b></label>
                                                    <textarea class="form-control" style="font-size:12px;height: 112px !important;" name="address[]" placeholder="Address" disabled>Above hotel Shree Kateel, next to Amit Bloofield, near to Navle Bridge, Narhe, Pune, Maharashtra 411046</textarea>
                                                </div>
                                            </div>         
                                            <div class="col-md-2">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Date</b></label>
                                                    <input type="date" value="{{date('Y-m-d')}}" name="validDate[]" style="font-size:12px;" placeholder="Date" class="form-control" required>
                                                </div>
                                            </div>   
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Shipping Address</b></label>
                                                    {{Form::select('shippingAddress[]', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'shippingAddress', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                                </div>
                                            </div>                                
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Type of Company</b></label>
                                                    {{Form::select('typeOfCompany[]', $typeOfCompanies, null, ['class'=>'form-control', 'placeholder'=>'Select Option', 'required', 'id'=>'typeOfCompany', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                                </div>
                                            </div>                                         
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Advance payment (%)</b></label>
                                                    <input type="text" style="font-size:12px;" maxlength="100" placeholder="Terms of payment" name="termOfPayment[]" value="0" onkeypress="return isNumberKey(event)" name="termOfPayment[]" class="form-control" required>
                                                </div>
                                            </div>                                 
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Tentative delivery date</b></label>
                                                    <input type="date" value="{{date('Y-m-d')}}" style="font-size:12px;" name="tentativeDeliveryDate[]" placeholder="Tentative delivery date" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Already Paid</b></label>
                                                    {{Form::select('alreadyPaid[]', ['1'=>'Yes','0'=>'No'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'alreadyPaid'])}}
                                                </div>
                                            </div>   
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                                    <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy[]" placeholder="Already Paid By(Employee Code)" class="form-control">
                                                </div>
                                            </div>                   
                                            <div class="col-md-8">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Quotation For</b></label>
                                                    <input type="text" style="font-size:12px;" placeholder="Quotation For" name="quotationFor[]" id="quotationFor3" value="{{$quotationFor}}" class="form-control" required>
                                                </div>
                                            </div>         
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Requisition No</b></label>
                                                    <input type="text" style="font-size:12px;" placeholder="Requisition No"  name="requisitionNo[]"  id="requisitionNo3" value="{{$requisitionNo}}" class="form-control" required>
                                                </div>
                                            </div>  
                                            <div class="col-md-4">
                                                <div style="margin-bottom: 0rem;" class="form-group">
                                                    <label style="font-size:12px;"><b>Upload Quotation</b></label>
                                                    <input type="file" style="font-size:12px;line-height: 18px !important;" placeholder="Requisition No" name="quotationFile[]"  id="quotationFile" value="" class="form-control">
                                                </div>
                                            </div>                                           
                                        </div>  
                                            
                                        <hr>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-bordered mb-0" id="quotationProductTable3">
                                                <thead class="bg-white text-uppercase">
                                                    <tr class="ligth ligth-data">
                                                        <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Product </th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Qty</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Date</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Unit Price</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">CGST %</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">SGST %</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Discount</th>
                                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Amount<?php $k=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="ligth-body">
                                                    @if($prodCt = count($productId))
                                                        @for($i=0; $i<$prodCt; $i++)
                                                            <tr>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$k++}}</td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-left">{{$productName[$i]}}<input class="form-control" type="hidden" name="productId3[]" value="{{$productId[$i]}}"><br>Category: {{$categoryName[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; SubCategory: {{$subCategoryName[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp;
                                                                {{$color[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; {{$size[$i]}}&nbsp;&nbsp; | &nbsp;&nbsp; {{$company[$i]}}<br>
                                                                <input style="height: 29px;font-size:12px;color:black;" class="form-control" type="text" id="" name="remark3[]" value="" placeholder="If any Remark"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$qty[$i]}}<input class="form-control qty3"  id="qty3" type="hidden" name="qty3[]" onkeypress="return isNumberKey(event)" value="{{$qty[$i]}}" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" class="form-control" type="date" id="" name="forDate3[]" value="{{date('Y-m-d')}}" placeholder=""></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control unitPrice3" type="text" id="unitPrice3" name="unitPrice3[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control cgst3" type="text" id="cgst3" name="cgst3[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control sgst3" type="text" id="sgst3" name="sgst3[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control discount3" type="text" id="discount3" name="discount3[]" value="0" placeholder="Product Qty"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;" width="8%"><input style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" class="form-control amount3" type="text" id="amount3" name="amount3[]" value="0" placeholder="amount" readonly></td>
                                                            </tr>
                                                        @endfor
                                                            <tr>
                                                                <td width="80%" colspan="8"  style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Total Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" value="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="totalRs[]" class="form-control total" id="totalRs3" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Transportation Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="transportationRs[]" class="form-control" value="0" id="transportationRs3" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Loading Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="loadingRs[]" class="form-control" value="0" id="loadingRs3" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="padding: 0px 4px !important;font-size:12px;" class="text-right"><b>Unloading Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="unloadingRs[]" class="form-control" value="0" id="unloadingRs3" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td  width="80%" colspan="8" style="font-size:12px;color:black;" class="text-right"><b>Final Rs.</b></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" value="0" style="height: 29px;font-size:12px;text-align:right;color:black;" onkeypress="return isNumberKey(event)" name="finalRs[]" class="form-control" id="finalRs3" readonly></td>
                                                            </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @endif                          
                    </div>  
                    <div class="row">  
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <button type="submit" name="buttonStatus" value="save" class="btn btn-success mr-2">Save Current List</button>
                            <button type="submit" name="buttonStatus" value="generate" class="btn btn-primary mr-2">Generate Quotation</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection