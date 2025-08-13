@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Create Entry</b></div>
                            <div  class="col-lg-5">
                                <a href="/shelf/Entry" class="btn mb-1 btn-primary">Add</a>
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
                                    <label>Category<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    {{Form::select('rackId', [], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>      
                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                      
                                <div class="form-group">
                                    <label>Sub Category<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    {{Form::select('rackId', [], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>     
                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                      
                                <div class="form-group">
                                    <label>Product<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    {{Form::select('rackId', [], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rackId'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 

                            <div class="col-lg-3 col-md-3 col-sm-3 col-12 mt-4"> 
                                 <a href="/shelf/Entry" class="btn mb-1 btn-primary">Search</a>  
                            </div>
                            
                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                    
                                <div class="form-group">
                                    <label>HRMS Stock Count<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    <input type="text" class="form-control" name="name" placeholder="Count" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>   

                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                     
                                <div class="form-group">
                                    <label>Audit Stock Count<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    <input type="text" class="form-control" name="name" placeholder="Count" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>   

                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                     
                                <div class="form-group">
                                    <label>Difference Stock Count<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    <input type="text" class="form-control" name="name" placeholder="Count" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>   

                            <div class="col-lg-3 col-md-3 col-sm-3 col-12">                     
                                <div class="form-group">
                                    <label>File Upload<span style="color:red;font-weight:bold;font-size:20px;">*</span>:</label>
                                    <input type="File" class="form-control" name="name" placeholder="File Upload" Style="line-height: 17px; !important" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>                                 
                        </div> 
                        <br/>                           
                        <button type="submit" class="btn btn-success mr-2">Save</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
