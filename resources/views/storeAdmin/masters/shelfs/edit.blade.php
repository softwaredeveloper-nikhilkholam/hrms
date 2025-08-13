@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Add Shelf</b></div>
                            <div  class="col-lg-5">
                                <a href="/shelf/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/shelf/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dShelfs}}</span>
                                </a>
                                <a href="/shelf" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$shelfs}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\ShelfsController@update', $shelf->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">     
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Select Hall<span style="color:red;">*</span></label>
                                    {{Form::select('hallId', $halls, $shelf->hallId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'hallId'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>    
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Select Rack<span style="color:red;">*</span></label>
                                    {{Form::select('rackId', $racks, $shelf->rackId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>        
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Shelf Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{$shelf->name}}" placeholder="Enter Shelf Name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                        </div>        
                        {{Form::hidden('_method', 'PUT')}}                     
                        <button type="submit" class="btn btn-success mr-2">Update Shelf</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
