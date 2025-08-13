<?php
$userType = Auth::user()->userType;
use App\Helpers\Utility;
$util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-4 col-md-4"></div>
            <div class="col-lg-4 col-md-4"><h5 style="color:red;">Sub Store</h6></div>
            <div class="col-lg-4 col-md-4"></div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <div class="card card-block card-stretch card-height" style="margin-bottom: 0px !important;height: calc(110% - 30px) !important;">
                        <div class="card-body" style="padding: 4px !important;">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-success-light">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Category</p>
                                    <h6>{{$categoryCount}}</h6>
                                </div>
                            </div>                                
                        
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="card card-block card-stretch card-height" style="margin-bottom: 0px !important;height: calc(110% - 30px) !important;">
                        <div class="card-body" style="padding: 4px !important;">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-success-light">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Sub Category</p>
                                    <h6>{{$subCategoryCount}}</h6>
                                </div>
                            </div>                                
                        
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="card card-block card-stretch card-height" style="margin-bottom: 0px !important;height: calc(110% - 30px) !important;">
                        <div class="card-body" style="padding: 4px !important;">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-info-light">
                                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <p class="mb-2">Products</p>
                                    <h6>{{$productCount}}</h6>
                                </div>
                            </div>                                
                        
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
