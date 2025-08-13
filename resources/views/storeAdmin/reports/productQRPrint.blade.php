@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Add Asset Product</b></div>
                    <div  class="col-lg-5">
                        <a href="/assetProducts/create" class="btn mb-1 btn-primary">Add</a>
                        <a href="/assetProducts/dlist" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Deactive List</span>
                        </a>
                        <a href="/assetProducts" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Active List
                        </a>
                        <a href="/assetProducts/searchAssetProduct" class="btn mb-1 btn-success" style="font-size: 14px !important;">
                            Print QR <span class="badge badge-danger ml-2"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\AssetProductsController@generateQRCodes', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Branch Name<span style="color:red;">*</span></label>
                                    {{Form::select('branchId', $branches, null, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'required'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Department</label>
                                    {{Form::select('departmentId', $departments, null, ['placeholder'=>'Select Department','class'=>'form-control', 'id'=>'branchId'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product</label>
                                    {{Form::select('storeProduct', $products, null, ['placeholder'=>'Select Product','class'=>'form-control', 'id'=>'storeProduct'])}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Entry Date</label>
                                    <input type="date" class="form-control" value="" name="forDate" placeholder="">
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
</div>
<script>
 $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
    </script>
@endsection
