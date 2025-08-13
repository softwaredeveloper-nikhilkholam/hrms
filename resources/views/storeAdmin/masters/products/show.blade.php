<?php
 $username = Auth::user()->username;
 $userId = Auth::user()->id;
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
                            <div  class="col-lg-7"><b style="color:red;">Show Product Details</b></div>
                            <div  class="col-lg-5">
                                <a href="/product/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/product/dlist" class="btn mb-1 btn-primary">Deactive List <span class="badge badge-danger ml-2">{{$deactiveCount}}</span></a>
                                <a href="/product" class="btn mb-1 btn-primary">Active List <span class="badge badge-danger ml-2">{{$activeCount}}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">  
                        <div class="col-md-9">        
                            <div class="row">  
                                <div class="col-md-2">                   
                                    <div class="form-group">
                                        <label>Select Category<span style="color:red;">*</span></label>
                                        {{Form::select('categoryId', $categories, $product->categoryId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'categoryId', 'disabled'])}}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Select Sub Category<span style="color:red;">*</span></label>
                                        {{Form::select('subCategoryId', $subCategories, $product->subCategoryId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'subCategoryId', 'disabled'])}}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Product Name <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{$product->name}}" placeholder="Enter Product Name" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Company / Brand<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" value="{{$product->company}}" name="company" placeholder="Enter Company Name" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>     
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Model Number</label>
                                        <input type="text" class="form-control" name="modelNumber" value="{{$product->modelNumber}}" placeholder="Enter Model Number" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Select Unit</label>
                                        {{Form::select('unitId', $units, $product->unitId, ['class'=>'form-control unitId', 'placeholder'=>'Select a Option', 'id'=>'unitId', 'disabled'])}}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Opening Stock</label>
                                        <input type="text" class="form-control" name="openingStock" value="{{$product->openingStock}}" placeholder="Enter Opening Stock"  disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Opening Stock Date</label>
                                        <input type="text" class="form-control" name="openingStockDate" value="{{date('d-m-Y H:i', strtotime($product->openingStockForDate))}}" placeholder="Enter Opening Stock"  disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Closing Stock</label>
                                        <input type="text" class="form-control" name="openingStock" value="{{$util->getCurrentProductStock($product->id)}}" placeholder="Enter Opening Stock"  disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input type="text" class="form-control" value="{{$product->color}}" name="color" placeholder="Enter Product Color" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Size</label>
                                        <input type="text" class="form-control" value="{{$product->size}}" name="size" placeholder="Enter Product Size" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Fuel Type(If Product is Vehicle)</label>
                                        {{Form::select('fuelType', ['1'=>'Diesel', '2'=>'Petrol', '3'=>'Other'], $product->fuelType, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Return Status<span style="color:red;">*</span></label>
                                        {{Form::select('returnStatus', ['Yes'=>'Yes', 'No'=>'No'], $product->returnStatus, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'disabled'])}}
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                         
                                    <div class="form-group">
                                        <label>Reorder Level<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" value="{{round($product->reorderLevel)}}" name="reorderLevel" value="0" placeholder="Enter ReOrder Level" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>  
                                <div class="col-md-2">                         
                                    <div class="form-group">
                                        <label>Maxmimum Level</label>
                                        <input type="text" class="form-control" name="maximumLevel"  value="{{round($product->maximumLevel)}}" placeholder="Enter Maxmimum Level" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>  
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>Description<span style="color:red;">* [max. 500 character]</span></label>
                                        <textarea class="form-control" name="description" maxlength="500" style="height: 100px !important;" disabled> {{$product->description}}</textarea>
                                    </div>
                                </div>                    
                            </div>
                        </div>   
                        <div class="col-md-3"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Image<span style="color:red;">*</span></label>
                                        <a href="/storeAdmin/productImages/{{ $product->image }}" target="_blank"><img src="/storeAdmin/productImages/{{ $product->image }}" style="height: 200px !important;width:300px;" class="form-control" name="image"></a>
                                    </div>                                    
                                </div>
                            <div class="row">
                            </div>
                                <div class="col-md-12">     
                                    <div class="form-group">
                                        <label>Product Code<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control productCode" value="{{$product->productCode}}" name="productCode" placeholder="Enter Product Code" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>                                   
                                </div>     
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Manufacturing Date</label>
                                <input type="date" class="form-control" name="manuDate" value="{{$product->manuDate}}" placeholder="Enter Product Manufacturing Date" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="date" class="form-control" name="expiryDate"  value="{{$product->expiryDate}}" placeholder="Enter Product Expiry Date" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>CGST</label>
                                <input type="text" class="form-control" value="{{$product->CGST}}" name="CGST" placeholder="Enter Product CGST" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>SGST</label>
                                <input type="text" class="form-control" value="{{$product->SGST}}" name="SGST" placeholder="Enter Product SGST" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>IGST</label>
                                <input type="text" class="form-control" value="{{$product->IGST}}" name="IGST" placeholder="Enter Product IGST" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Rate (Per Pice)</label>
                                <input type="text" class="form-control" name="productRate" value="{{$product->productRate}}" placeholder="Enter Rate (Per Pice)" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Current Valuation (Round figure)</label>
                                <input type="text" class="form-control" name="" value="{{round($product->productRate*$product->openingStock)}}" placeholder="Enter Rate (Per Pice)" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Select Hall<span style="color:red;">*</span></label>
                                {{Form::select('hallId', $halls, $product->hallId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'hallId', 'disabled'])}}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>    
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Select Rack<span style="color:red;">*</span></label>
                                {{Form::select('rackId', $racks, $product->rackId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId', 'disabled'])}}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Select Shelf<span style="color:red;">*</span></label>
                                {{Form::select('shelfId', $shelfs, $product->shelfId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'shelfId', 'disabled'])}}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>   
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Added By</label>
                                <input type="text" class="form-control" value="{{$product->updated_by}}" name="SGST" placeholder="Enter Product SGST" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Added At</label>
                                <input type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($product->created_at))}}" name="IGST" placeholder="Enter Product IGST" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <a href="/reports/openingStockReport?startDate={{$product->openingStockForDate}}&endDate={{date('Y-m-d')}}&productId={{$product->id}}" target="_blank">Opening Stock Report you can click here.....</label>
                            </div>
                        </div> 
                        
                    </div>  
                </div>
            </div>
        </div>
    </div>
@endsection
