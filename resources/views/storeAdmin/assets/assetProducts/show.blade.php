@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Asset Product Details</b></div>
                    <div  class="col-lg-5">
                        <a href="/assetProducts/create" class="btn mb-1 btn-primary">Add</a>
                        <a href="/assetProducts/dlist" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Deactive List <span class="badge badge-danger ml-2">{{$dProductCount}}</span>
                        </a>
                        <a href="/assetProducts" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Active List <span class="badge badge-danger ml-2">{{$productCount}}</span>
                        </a>
                        <a href="/assetProducts/searchAssetProduct" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Print QR <span class="badge badge-danger ml-2"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Journey&nbsp;&nbsp;<b style="color:red;">*</b></label>
                                {{Form::select('productJourney', ['1'=>'REQUISITION','2'=>'PURCHASE','3'=>'TURN KEY'], $assetProduct->productJourney, ['placeholder'=>'Select Product Journey','class'=>'form-control', 'id'=>'productJourney', 'disabled'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Venture &nbsp;&nbsp;<b style="color:red;">*</b></label>
                                {{Form::select('ventureName', ['Aaryans World School'=>'Aaryans World School','Ellora Medical And Educational Foundation'=>'Ellora Medical And Educational Foundation','Snayraa Educational AID And Research Foundation '=>'Snayraa Educational AID And Research Foundation','Tejasha Educational and Research Foundation'=>'Tejasha Educational and Research Foundation','Tejasha Agricultural Farmining and Educating the students society'=>'Tejasha Agricultural Farmining and Educating the students society','Aaryans Animal, Birds, Fish, Reptiles Rescue, Rehabilitation and Educational Society'=>'Aaryans Animal, Birds, Fish, Reptiles Rescue, Rehabilitation and Educational Society','Akshara Food Court'=>'Akshara Food Court','YO Bhajiwala'=>'YO Bhajiwala','Aaryans Farm Fresh'=>'Aaryans Farm Fresh','Aaryans Dairy Farm'=>'Aaryans Dairy Farm','YO Bhajiwala'=>'YO Bhajiwala','Aaryans farm'=>'Aaryans farm','Aaryans farming Society'=>'Aaryans farming Society','Aaryans River Wood Resort'=>'Aaryans River Wood Resort','Aaryans Edutainment'=>'Aaryans Edutainment','Aaryans Hathway Farm'=>'Aaryans Hathway Farm', 'Milind Ladge'=>'Milind Ladge', 'Pratik Ladge'=>'Pratik Ladge', 'Pranav Ladge'=>'Pranav Ladge'], $assetProduct->ventureName, ['class'=>'form-control', 'placeholder'=>'Select Option', 'disabled', 'id'=>'ventureName'])}}
                            </div>
                        </div>
                        <div class="col-md-3 billNumber">
                            <div class="form-group">
                                <label>Bill Number</label>
                                <input type="text" class="form-control" value="{{$assetProduct->billNumber}}" name="billNumber" placeholder="Enter Bill Number">
                            </div>
                        </div>
                        <div class="col-md-3 invoiceNumber">
                            <div class="form-group">
                                <label>Invoice Number</label>
                                <input type="text" class="form-control" value="{{$assetProduct->invoiceNumber}}" name="invoiceNumber" placeholder="Enter Invoice Number">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Branch Name&nbsp;&nbsp;<b style="color:red;">*</b></label>
                                {{Form::select('branchId', $branches, $assetProduct->branchId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Department</label>
                                {{Form::select('departmentId', $departments, $assetProduct->departmentId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product</label>
                                {{Form::select('productId', $products, $assetProduct->productId, ['placeholder'=>'Select Product','class'=>'form-control', 'id'=>'storeProduct', 'disabled'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Category</label>
                                <input type="text" class="form-control" value="{{$assetProduct->category}}" name="category" placeholder="Enter Product Category" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Sub-Category</label>
                                <input type="text" class="form-control" value="{{$assetProduct->subCategory}}" name="subCategory" placeholder="Enter Product Sub-Category" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Main Location</label>
                                <input type="text" class="form-control" value="{{$assetProduct->mainLocation}}" name="mainLocation" placeholder="Enter Main Location" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Location in Department</label>
                                <input type="text" class="form-control" value="{{$assetProduct->locationInDepartment}}" name="locationInDepartment" placeholder="Enter Location In Department" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Location of the Product</label>
                                <input type="text" class="form-control" value="{{$assetProduct->productLocation}}" name="productLocation" placeholder="Enter Location of the Product" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Company / Brand</label>
                                <input type="text" class="form-control company" value="{{$assetProduct->companyName}}" name="companyName" id="company" placeholder="Enter Company Name" disabled >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>      
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" class="form-control color" name="color" value="{{$assetProduct->color}}" id="color" placeholder="Enter Color"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>     
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Product Size</label>
                                <input type="text" class="form-control size" id="size"  name="size"  value="{{$assetProduct->size}}" placeholder="Enter Size"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Specification Of The Product</label>
                                <input type="text" class="form-control specificationProduct" id="specificationProduct"  name="specificationProduct" value="{{$assetProduct->specificationProduct}}" placeholder="Enter Size"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Type Of Assest</label>
                                <input type="text" class="form-control typeOfAsset" id="typeOfAsset"  name="typeOfAsset"  value="{{$assetProduct->typeOfAsset}}" placeholder="Enter Size"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Purchase Date</label>
                                <input type="date" class="form-control "  name="purchaseDate"  value="{{$assetProduct->purchaseDate}}" placeholder="Enter Purchase Date"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>      
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Installation Date</label>
                                <input type="date" class="form-control "  value="{{$assetProduct->installationDate}}"  name="installationDate" placeholder="Enter Installation Date"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" class="form-control size" id="qty" onkeypress="return isNumberKey(event)"  name="qty" value="{{$assetProduct->qty}}" placeholder="Enter Quantity"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>   
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Expriy Date</label>
                                <input type="date" class="form-control "  value="{{$assetProduct->expiryDate}}"  name="expiryDate" placeholder="Enter Installation Date"  disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label></label>
                                <a href="/storeAdmin/assetProducts/{{$assetProduct->productPhoto}}" target="_blank"><img src="/storeAdmin/assetProducts/{{$assetProduct->productPhoto}}" height="100px" width="100px"></a>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($productList))
                                    <table class="table table-bordered table-striped" style="font-size:12px;">
                                        <thead>
                                            <tr class="ligth">
                                                <th style="font-size:13px;" width="5%">No</th>
                                                <th style="font-size:13px;">Product Code</th>
                                                <th style="font-size:13px;" width="10%">QR Code</th>
                                                <th style="font-size:13px;" width="20%">Added At<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($productList as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->assetProductCode}}</td>
                                                    <td style="padding: 3px 17px !important;" class="text-left">{!! QrCode::size(100)->generate($row->assetProductCodeForQR); !!}</td>
                                                    <td style="padding: 0px 17px !important;">{{date('d-m-Y H:i', strtotime($row->created_at))}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- <a class="badge bg-danger mr-2 mt-4 mb-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Export Excel Sheet"
                                                                href="/assetProducts/exportExcelSheet/1" target="_blank">Export Excel</a> -->
                                @else
                                    <h4>Record not found</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
