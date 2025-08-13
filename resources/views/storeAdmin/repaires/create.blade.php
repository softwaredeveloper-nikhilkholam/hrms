@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-7"><b style="color:red;">Add Repaire Product</b></div>
                        <div  class="col-lg-5">
                            <a href="{{ route('repaires.create') }}" class="btn mb-1 btn-primary">Add</a>
                            <a href="{{ route('repaires.completedList') }}" class="btn mb-1 btn-primary">Completed List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\RepairesController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <label style="font-size:12px !important;"><b>Select Category<span style="color:red;">*</span></b></label>
                            {{Form::select('reqCategoryId', $categories, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Category','class'=>'form-control', 'id'=>'reqCategoryId', 'required'])}}
                        </div> 
                        <div class="col-md-2">
                            <div style="margin-bottom: 0rem;" class="form-group">
                                <label style="font-size:12px !important;"><b>Select Sub-Category<span style="color:red;">*</span></b></label>
                                {{Form::select('reqSubCategoryId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'reqSubCategoryId', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="margin-bottom: 0rem;" class="form-group">
                                <label style="font-size:12px !important;">Select Product<span style="color:red;">*</span></b></label>
                                {{Form::select('productId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Product','class'=>'form-control', 'id'=>'reqProductId', 'required'])}}
                            </div>
                        </div>
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Date<span style="color:red;">*</span></label>
                                <input type="date" name="forDate" id="forDate" value="{{(old('forDate')?old('forDate'):date('Y-m-d'))}}" placeholder="ForDate" class="form-control" required>
                            </div>
                        </div>  
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Count<span style="color:red;">*</span></label>
                                <input type="text" name="count" id="count" value="{{old('count')}}" placeholder="Count / Qty" class="form-control" required>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-6">                      
                            <div class="form-group">
                                <label>Description<span style="color:red;">*</span></label>
                                <input type="text" name="description" id="description" value="{{old('description')}}" placeholder="Description" class="form-control" required>
                            </div>
                        </div>  
                        <div class="col-md-6">                      
                            <div class="form-group">
                                <label>Reason of Repaire<span style="color:red;">*</span></label>
                                <input type="text" name="reasonForRepaire" id="reasonForRepaire" value="{{old('reasonForRepaire')}}" placeholder="Reason of Repaire" class="form-control" required>
                            </div>
                        </div>  
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Save</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
