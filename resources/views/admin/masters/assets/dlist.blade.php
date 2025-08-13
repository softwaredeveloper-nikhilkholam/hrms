@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Assets</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/assets" class="btn btn-primary mr-3">Active List</a>
                            <a href="#" class="btn btn-success mr-3">Deactive List</a>
                            <a href="/assets/create" class="btn btn-primary mr-3">Add Asset</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <ul class="nav panel-tabs">
                                <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">LAPTOP</a></li>
                                <li><a href="#tab2" data-toggle="tab">DESKTOP PC</a></li>
                                <li><a href="#tab3" data-toggle="tab">MOBLE</a></li>
                                <li><a href="#tab4" data-toggle="tab">SIM CARD</a></li>
                                <li><a href="#tab5" data-toggle="tab">PENDRIVE</a></li>
                                <li><a href="#tab6" data-toggle="tab">HARD DISK</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-body">
                                    @if(isset($lapAssets))
                                        @if(count($lapAssets) >= 1)
                                            <div class="table-responsive">
                                                <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0 w-5">#</th>
                                                            <th class="border-bottom-0">MAC Id</th>
                                                            <th class="border-bottom-0">Motherboard</th>
                                                            <th class="border-bottom-0">Serial No</th>
                                                            <th class="border-bottom-0">Make</th>
                                                            <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($lapAssets as $asset)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$asset->MACId}}</td>
                                                                <td>{{$asset->motherboard}}</td>
                                                                <td>{{$asset->serialNo}}</td>
                                                                <td>{{$asset->make}}</td>
                                                                <td>
                                                                    <a href="/assets/1/{{$asset->id}}/editAsset" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/assets/1/{{$asset->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    <a href="/assets/1/{{$asset->id}}/showAsset" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Deactive Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab2">
                                <div class="card-body">
                                    @if(isset($deskAssets))
                                        @if(count($deskAssets) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0 w-5">#</th>
                                                            <th class="border-bottom-0">MAC Id</th>
                                                            <th class="border-bottom-0">Motherboard</th>
                                                            <th class="border-bottom-0">Serial No</th>
                                                            <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($deskAssets as $asset)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$asset->MACId}}</td>
                                                                <td>{{$asset->motherboard}}</td>
                                                                <td>{{$asset->serialNo}}</td>
                                                                <td>
                                                                    <a href="/assets/5/{{$asset->id}}/editAsset" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/assets/5/{{$asset->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    <a href="/assets/5/{{$asset->id}}/showAsset" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Deactive Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab3">
                                <div class="card-body">
                                    @if(isset($mobAssets))
                                        @if(count($mobAssets) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0 w-5">#</th>
                                                            <th class="border-bottom-0">Mobile Company Name</th>
                                                            <th class="border-bottom-0">Model Number</th>
                                                            <th class="border-bottom-0">IMEI 1</th>
                                                            <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($mobAssets as $asset)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$asset->companyName}}</td>
                                                                <td>{{$asset->modelNumber}}</td>
                                                                <td>{{$asset->IMEI1}}</td>
                                                                <td>
                                                                    <a href="/assets/2/{{$asset->id}}/editAsset" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/assets/2/{{$asset->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    <a href="/assets/2/{{$asset->id}}/showAsset" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab4">
                                <div class="card-body">
                                    @if(isset($simAssets))
                                        @if(count($simAssets) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0 w-5">#</th>
                                                            <th class="border-bottom-0">Operator Name</th>
                                                            <th class="border-bottom-0">Mobile Number</th>
                                                            <th class="border-bottom-0">Extra Material</th>
                                                            <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($simAssets as $asset)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$asset->operatComName}}</td>
                                                                <td>{{$asset->mobNumber}}</td>
                                                                <td>{{$asset->extraMat}}</td>
                                                                <td>
                                                                    <a href="/assets/3/{{$asset->id}}/editAsset" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/assets/3/{{$asset->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    <a href="/assets/3/{{$asset->id}}/showAsset" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab5">
                                <div class="card-body">
                                    @if(isset($pendAssets))
                                        @if(count($pendAssets) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0 w-5">#</th>
                                                            <th class="border-bottom-0">Company Name</th>
                                                            <th class="border-bottom-0">Storage Size</th>
                                                            <th class="border-bottom-0">Model</th>
                                                            <th class="border-bottom-0">Extra Material</th>
                                                            <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($pendAssets as $asset)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$asset->operatComName}}</td>
                                                                <td>{{$asset->storeageSize}}</td>
                                                                <td>{{$asset->modelType}}</td>
                                                                <td>{{$asset->extraMat}}</td>
                                                                <td>
                                                                    <a href="/assets/4/{{$asset->id}}/editAsset" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/assets/4/{{$asset->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    <a href="/assets/4/{{$asset->id}}/showAsset" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab6">
                                <div class="card-body">
                                    @if(isset($hardAssets))
                                        @if(count($hardAssets) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0 w-5">#</th>
                                                            <th class="border-bottom-0">Company Name</th>
                                                            <th class="border-bottom-0">Storage Size</th>
                                                            <th class="border-bottom-0">Hard Disk Type</th>
                                                            <th class="border-bottom-0">Extra Material</th>
                                                            <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($hardAssets as $asset)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$asset->operatComName}}</td>
                                                                <td>{{$asset->storeageSize}}</td>
                                                                <td>{{$asset->hardDisk}}</td>
                                                                <td>{{$asset->extraMat}}</td>
                                                                <td>
                                                                    <a href="/assets/6/{{$asset->id}}/editAsset" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/assets/6/{{$asset->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    <a href="/assets/6/{{$asset->id}}/showAsset" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
             
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
