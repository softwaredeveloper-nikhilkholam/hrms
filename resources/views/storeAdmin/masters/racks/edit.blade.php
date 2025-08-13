@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Add Rack</b></div>
                            <div  class="col-lg-5">
                                <a href="/rack/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/rack/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dRacks}}</span>
                                </a>
                                <a href="/rack" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$racks}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\RacksController@update', $rack->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">  
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Select Hall<span style="color:red;">*</span></label>
                                    {{Form::select('hallId', $halls, $rack->hallId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>          
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Rack Name *</label>
                                    <input type="text" class="form-control" value="{{$rack->name}}" name="name" placeholder="Enter Rack Name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                        </div>       
                        {{Form::hidden('_method', 'PUT')}}                     
                        <button type="submit" class="btn btn-success mr-2">Update Rack</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
