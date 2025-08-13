@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Add Unit</b></div>
                            <div  class="col-lg-5">
                                <a href="/unit/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/unit/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dUnits}}</span>
                                </a>
                                <a href="/unit" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$units}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\UnitsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Unit Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Unit Name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                                                        
                        </div>                            
                        <button type="submit" class="btn btn-success mr-2">Add Unit</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
