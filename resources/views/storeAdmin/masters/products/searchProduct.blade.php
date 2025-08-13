@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Add Asset Product</b></div>
                    <div  class="col-lg-5">
                        <a href="/product/create" class="btn mb-1 btn-primary">Add</a>
                        <a href="/product/dlist" class="btn mb-1 btn-primary">Deactive List <span class="badge badge-danger ml-2"></span></a>
                        <a href="/product" class="btn mb-1 btn-primary">Active List <span class="badge badge-danger ml-2"></span></a>
                        <a href="/product/searchProduct" class="btn mb-1 btn-success" style="font-size: 14px !important;">
                            Print QR <span class="badge badge-danger ml-2"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\ProductsController@generateQRCodes', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hall<span style="color:red;">*</span></label>
                                    {{Form::select('hallId', $halls, null, ['placeholder'=>'Select Hall','class'=>'form-control', 'id'=>'hallId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Rack</label>
                                    {{Form::select('rackId', $racks, null, ['placeholder'=>'Select Rack','class'=>'form-control', 'id'=>'rackId'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Shelf</label>
                                    {{Form::select('shelfId', $shelfs, null, ['placeholder'=>'Select Shelf','class'=>'form-control', 'id'=>'shelfId'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>QR Code Size (CM)</label>
                                    {{Form::select('size', ['50'=>'50 X 50','75'=>'75 X 75','100'=>'100 X 100'], '50', ['placeholder'=>'Select QR Code Size','class'=>'form-control', 'id'=>''])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success mr-2">Generate QR Codes</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            {!! Form::close() !!}
        </div>
    </div>
<script>
 $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
    </script>
@endsection
