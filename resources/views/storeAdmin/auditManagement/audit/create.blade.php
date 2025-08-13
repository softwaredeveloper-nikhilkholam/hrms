@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Audit Entry</b></div>
                            <div  class="col-lg-5">
                                <a href="/shelf/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/shelf/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2"></span>
                                </a>
                                <a href="/shelf" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ShelfsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">     
                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                <div class="form-group">
                                    <label>Date<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    <input type="date" name="Date"  id="Date" value="{{date('Y-m-d')}}" placeholder="Date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                      
                                <div class="form-group">
                                    <label>Category<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    {{Form::select('rackId', [], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>        
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Remarks<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    <input type="text" class="form-control" name="name" placeholder="Remarks" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                        </div>                            
                        <button type="submit" class="btn btn-success mr-2">Start</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
