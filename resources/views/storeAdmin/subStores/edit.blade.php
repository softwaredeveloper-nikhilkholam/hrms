@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Add Asset Product</b></div>
                    <div  class="col-lg-5">
                        <a href="/assetProducts/create" class="btn mb-1 btn-primary">Add</a>
                        <a href="/assetProducts/dlist" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Deactive List <span class="badge badge-danger ml-2">{{$dProductCount}}</span>
                        </a>
                        <a href="/assetProducts" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Active List <span class="badge badge-danger ml-2">{{$productCount}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\AssetProductsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Journey</label>
                                {{Form::select('productJourney', ['1'=>'REQUISITION','2'=>'PURCHASE','3'=>'TURN KEY'], null, ['placeholder'=>'Select Product Journey','class'=>'form-control', 'id'=>'productJourney', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product</label>
                                {{Form::select('storeProduct', $products, null, ['placeholder'=>'Select Product','class'=>'form-control', 'id'=>'storeProduct', ''])}}
                            </div>
                        </div>
                        <div class="col-md-3 billNumber">
                            <div class="form-group">
                                <label>Bill Number</label>
                                <input type="text" class="form-control" name="billNumber" placeholder="Enter Bill Number">
                            </div>
                        </div>
                        <div class="col-md-3 invoiceNumber">
                            <div class="form-group">
                                <label>Invoice Number</label>
                                <input type="text" class="form-control" name="invoiceNumber" placeholder="Enter Invoice Number">
                            </div>
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Branch Name</label>
                                {{Form::select('branchId', $branches, null, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" class="form-control" name="location" placeholder="Enter Location" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Department</label>
                                {{Form::select('departmentId', $departments, null, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Location of the Product</label>
                                <input type="text" class="form-control" name="locationOfTheProduct" placeholder="Enter Location of the Product" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Category</label>
                                {{Form::select('categoryId', $categories, null, ['placeholder'=>'Select Category','class'=>'form-control', 'id'=>'categoryId', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Sub-Category</label>
                                {{Form::select('subCategoryId', $subCategories, null, ['placeholder'=>'Select Sub-Category','class'=>'form-control subCategoryId', 'id'=>'subCategoryId', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Product Name <span style="color:red;">*</span></label>
                                <input type="text" class="form-control productName" id="productName" name="name" placeholder="Enter Product Name" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Company / Brand</label>
                                <input type="text" class="form-control company" name="company" id="company" placeholder="Enter Company Name" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>      
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" class="form-control color" name="color" id="color" placeholder="Enter Color" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>      
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Size</label>
                                <input type="text" class="form-control size" id="size"  name="size" value="0" placeholder="Enter Size" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Photo uploading</label>
                                <input type="file" class="form-control" style="line-height: 15px !important;" name="photoUpload" placeholder="Enter Photo uploading" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Specification about product</label>
                                <input type="text" class="form-control" id=""  name="specificationAboutProduct" value="" placeholder="Enter Specification about product" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type of Asset</label>
                                <input type="text" class="form-control " id=""  name="typeOfAsset" value="" placeholder="Enter Type of Asset" >
                            </div>
                        </div>
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Details<span style="color:red;">*</span></label>
                                <input type="text" class="form-control " id=""  name="details" value="" placeholder="Enter Details" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Purchase Date</label>
                                <input type="date" class="form-control "  value="{{date('Y-m-d')}}"   name="purchaseDate" value="" placeholder="Enter Purchase Date" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>      
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Installation Date</label>
                                <input type="date" class="form-control " value="{{date('Y-m-d')}}"  name="installationDate" placeholder="Enter Installation Date" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>      
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control " id="" onkeypress="return isNumberKey(event)"  name="amount" value="0" placeholder="Enter Amount" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" class="form-control size" id="qty" onkeypress="return isNumberKey(event)"  name="qty" value="0" placeholder="Enter Quantity" >
                                <div class="help-block with-errors"></div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
                <hr>
                <button type="submit" class="btn btn-success mr-2">Save</button>
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
