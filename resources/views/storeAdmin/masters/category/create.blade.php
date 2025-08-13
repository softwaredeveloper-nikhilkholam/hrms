@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Add Product Category</b></div>
                            <div  class="col-lg-5">
                                <a href="/category/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/category/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dCategoriesCt}}</span>
                                </a>
                                <a href="/category" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$categoriesCt}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\CategoriesController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" class="form-control image-file" name="image" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Category Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Category Name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description <b style="color:red;">{ Max. 500 Character }</b></label>
                                    <input type="text" class="form-control" maxlength="500" name="description" placeholder="Enter Description">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                        </div>                            
                        <button type="submit" class="btn btn-success mr-2">Add category</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
