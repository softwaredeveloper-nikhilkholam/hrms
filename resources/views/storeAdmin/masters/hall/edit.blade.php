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
                            <div  class="col-lg-7"><b style="color:red;">Add Hall</b></div>
                            <div  class="col-lg-5">
                                <a href="/hall/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/hall/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dHalls}}</span>
                                </a>
                                <a href="/hall" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$halls}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\HallsController@update', $hall->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">      
                            <div class="col-md-12">                      
                                <div class="form-group">
                                    <label>Hall Name *</label>
                                    <input type="text" class="form-control" value="{{$hall->name}}" name="name" placeholder="Enter Hall Name" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description <b style="color:red;">{ Max. 500 Character }</b></label>
                                    <input type="text" class="form-control" maxlength="500" value="{{$hall->description}}" name="description" placeholder="Enter Description">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                        </div>       
                        {{Form::hidden('_method', 'PUT')}}                     
                        <button type="submit" class="btn btn-success mr-2">Update Hall</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
