<?php
 $branchId = Auth::user()->reqBranchId;
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="card" style="width: 100%;">
        <div class="card-header">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-7"><b style="color:red;">Add Inward</b></div>
                    <div  class="col-lg-5">
                        <a href="/subStores/inwList" class="btn mb-1 btn-primary" style="font-size: 14px !important;">Active List</a>
                        <a href="/subStores/inwdList" class="btn mb-1 btn-primary" style="font-size: 14px !important;">Deactive List</a>

                    </div>  
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\SubStoresController@storeInward', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Outward No</label>
                            <input type="text" value="" class="form-control" name="Outward No" placeholder="Enter Outward Number">
                        </div>
                        <div class="col-md-5">
                            <a href="/assetProducts/create" class="btn mb-1 btn-primary">Search</a>
                        </div>
                    </div>
                </div>
                <div class="row">  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Requsition Date<span class="text-red">*</span>:</label>
                            <input type="Date"  class="form-control" name="Date" placeholder="Date"readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">From Branch<span class="text-red">*</span>:</label>
                            <input type="Branch Name"  class="form-control" name="Branch Name" placeholder="Branch Name"readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">To Branch<span class="text-red">*</span>:</label>
                            <input type="Branch Name"  class="form-control" name="Branch Name" placeholder="Branch Name"readonly>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Requsition For<span class="text-red">*</span>:</label>
                            <input type="Branch Name"  class="form-control" name="Branch Name" placeholder=""readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Given By<span class="text-red">*</span>:</label>
                            <input type="Branch Name"  class="form-control" name="Branch Name" placeholder="Name"readonly>
                        </div>
                    </div> 
                        <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Handler<span class="text-red">*</span>:</label>
                            <input type="Branch Name"  class="form-control" name="Branch Name" placeholder="Name"readonly>
                        </div>
                    </div>   
                </div> 
                <div class="row <div class="col-md-3">
                <div class="table-responsive">
                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example1">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-white">Sr No</th>
                                <th class="text-white">Product Detail</th>
                                <th class="text-white">Qty</th>
                                <th class="text-white">Status</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
                </div>
                <h4 style="color:red;">Not Found Active Records.</h4>
                <div class="col-md-4">
                <div class="form-group mt-5">
                    <button type="submit" class="btn btn-success mr-2">Update</button>
                </div>
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
