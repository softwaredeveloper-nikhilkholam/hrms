@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Add Product</b></div>
                    <div  class="col-lg-5">
                        <a href="/product/create" class="btn mb-1 btn-success">Add</a>
                        <a href="/product/dlist" class="btn mb-1 btn-primary">Deactive List <span class="badge badge-danger ml-2">{{$deactiveCount}}</span></a>
                        <a href="/product" class="btn mb-1 btn-primary">Active List <span class="badge badge-danger ml-2">{{$activeCount}}</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\ProductsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Product Category</label>
                            {{Form::select('categoryId', $categories, null, ['placeholder'=>'Select Category','class'=>'form-control', 'id'=>'categoryId', 'required'])}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Product Sub-Category</label>
                            {{Form::select('subCategoryId', [], null, ['placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'subCategoryId', 'required'])}}
                        </div>
                    </div>
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Product Name <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Product Name" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Company / Brand</label>
                            <input type="text" class="form-control" name="company" placeholder="Enter Company Name" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>      
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Model Number</label>
                            <input type="text" class="form-control" name="modelNumber" placeholder="Enter Model Number" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>      
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Select Unit</label>
                            {{Form::select('unitId', $units, null, ['class'=>'form-control unitId', 'placeholder'=>'Select a Option', 'id'=>'unitId'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Opening Stock<span style="color:red;">*</span></label>
                            <input type="text" class="form-control openingStock" id="openingStock"  name="openingStock" value="0" placeholder="Enter Opening Stock" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Opening Stock Date<span style="color:red;">*</span></label>
                            <input type="date" class="form-control openingStockForDate" id="openingStockForDate"  name="openingStockForDate" value="0" placeholder="Enter Opening Stock Date" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control" name="color" placeholder="Enter Product Color" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Size</label>
                            <input type="text" class="form-control" name="size" placeholder="Enter Product Size" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Fuel Type(If Product is Vehicle)</label>
                            {{Form::select('fuelType', ['1'=>'Diesel', '2'=>'Petrol', '3'=>'Other'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Return Status<span style="color:red;">*</span></label>
                            {{Form::select('returnStatus', ['Yes'=>'Yes', 'No'=>'No'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                         
                        <div class="form-group">
                            <label>Reorder Level<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="reorderLevel" value="0" placeholder="Enter ReOrder Level" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                         
                        <div class="form-group">
                            <label>Maxmimum Level</label>
                            <input type="text" class="form-control" name="maximumLevel" value="0" placeholder="Enter Maxmimum Level" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                  
                        <div class="form-group">
                            <label>Image<span style="color:red;">*</span></label>
                            <input type="file" class="form-control" name="image" style="line-height: 18px !important;" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Manufacturing Date</label>
                            <input type="date" class="form-control" name="manuDate" placeholder="Enter Product Manufacturing Date" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" class="form-control" name="expiryDate" placeholder="Enter Product Expiry Date" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>CGST</label>
                            <input type="text" class="form-control" name="CGST" placeholder="Enter Product CGST" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>SGST</label>
                            <input type="text" class="form-control" name="SGST" placeholder="Enter Product SGST" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>IGST</label>
                            <input type="text" class="form-control" name="IGST" placeholder="Enter Product IGST" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Rate (Per Pice)</label>
                            <input type="text" class="form-control productRate" id="productRate" name="productRate" value="0" placeholder="Enter Rate (Per Pice)" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Current Valuation</label>
                            <input type="text" class="form-control prodValuation" id="prodValuation" name="" value="0" placeholder="Enter Rate (Per Pice)" readonly>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Select Hall<span style="color:red;">*</span></label>
                            {{Form::select('hallId', $halls, null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'hallId'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>    
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Select Rack<span style="color:red;">*</span></label>
                            {{Form::select('rackId', [], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Select Shelf<span style="color:red;">*</span></label>
                            {{Form::select('shelfId', [], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'shelfId'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div> 
                </div>
                <div class="row">              
                    <div class="col-md-12">                      
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control description" id="description" name="description" value="" placeholder="Enter Description" >
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                </div>  
                <hr>
                <button type="submit" class="btn btn-success mr-2">Add Product</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            {!! Form::close() !!}
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
