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
                {!! Form::open(['action' => ['storeController\RepairesController@update', $repaireProduct->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <label style="font-size:12px !important;"><b>Category<span style="color:red;">*</span></b></label>
                            <br><b>{{$repaireProduct->categoryName}}</b>
                        </div> 
                        <div class="col-md-2">
                            <label style="font-size:12px !important;"><b>Sub Category<span style="color:red;">*</span></b></label>
                            <br><b>{{$repaireProduct->subCategoryName}}</b>
                        </div> 
                        <div class="col-md-2">
                            <label style="font-size:12px !important;"><b>Product<span style="color:red;">*</span></b></label>
                            <br><b>{{$repaireProduct->productName}}</b>
                        </div> 
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Date<span style="color:red;">*</span></label>
                                <br><b>{{date('d-m-Y', strtotime($repaireProduct->forDate))}}</b>
                            </div>
                        </div>  
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Count<span style="color:red;">*</span></label>
                                <br><b>{{$repaireProduct->count}}</b>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-6">                      
                            <div class="form-group">
                                <label>Description<span style="color:red;">*</span></label>
                                <br><b>{{$repaireProduct->description}}</b>
                            </div>
                        </div>  
                        <div class="col-md-6">                      
                            <div class="form-group">
                                <label>Reason of Repaire<span style="color:red;">*</span></label>
                                <br><b>{{$repaireProduct->reasonForRepaire}}</b>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Return Date<span style="color:red;">*</span></label>
                                <input type="date" name="returnDate" id="returnDate" value="{{(old('forDate')?old('forDate'):date('Y-m-d'))}}" placeholder="ForDate" class="form-control" required>
                            </div>
                        </div>  
                        <div class="col-md-2">                      
                            <div class="form-group">
                                <label>Return Count<span style="color:red;">*</span></label>
                                <input type="text" name="returnCount" id="returnCount" max="{{$repaireProduct->count}}" value="{{old('count')}}" placeholder="Count / Qty" class="form-control" required>
                            </div>
                        </div>  
                    </div>
                    {{Form::hidden('_method', 'PUT')}}              
                    <button type="submit" class="btn btn-success mr-2">Update</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
