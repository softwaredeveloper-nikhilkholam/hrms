@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-9"><b style="color:red;">Scrap List</b></div>
                        <div  class="col-lg-3">
                            <a href="/scraps/create" class="btn mb-1 btn-primary">Add Scrap</a>
                            <a href="/scraps" class="btn mb-1 btn-primary">
                                Scrap List <span class="badge badge-danger ml-2">{{$countScraps}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\ScrapsController@update', $scrap->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Scrap Category</label>
                                    {{Form::select('scrapCategoryId', $scrapCategories, $scrap->scrapCategoryId, ['placeholder'=>'Select Scrap Category','class'=>'form-control', 'id'=>'scrapCategoryId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product Category</label>
                                    {{Form::select('categoryId', $categories, $scrap->categoryId, ['placeholder'=>'Select Category','class'=>'form-control', 'id'=>'categoryId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product Sub-Category</label>
                                    {{Form::select('subCategoryId', $subCategories, $scrap->subCategoryId, ['placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'subCategoryId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" name="productName" value="{{$scrap->productName}}" placeholder="Product Name" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control" name="qty" value="{{$scrap->qty}}" placeholder="Qty" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Unit</label>
                                    {{Form::select('unitId', $units, $scrap->unitId, ['placeholder'=>'Select Unit','class'=>'form-control', 'id'=>'unitId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total</label>
                                    <input type="text" class="form-control" name="total" value="{{$scrap->total}}" placeholder="Total" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="description" value="{{$scrap->description}}" placeholder="Description" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date of Scrap</label>
                                    <input type="date" class="form-control" name="dateOfScrap" value="{{$scrap->dateOfScrap}}" placeholder="Date of Scrap" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Estimated Price</label>
                                    <input type="text" class="form-control" name="estimatedPrice" value="{{$scrap->estimatedPrice}}" placeholder="Estimated Price" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Actual Price</label>
                                    <input type="text" class="form-control" name="actualPrice" value="{{$scrap->actualPrice}}" placeholder="Actual Price" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" class="form-control" name="amount" value="{{$scrap->amount}}" placeholder="Amount" required>
                                </div>
                            </div>
                        </div> 
                        {{Form::hidden('_method', 'PUT')}}                       
                        <button type="submit" name="addScrap" value="1" class="btn btn-success mr-2">Update Scrap</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
