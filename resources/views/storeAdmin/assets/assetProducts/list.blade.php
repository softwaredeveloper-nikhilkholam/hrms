<?php
 $username = Auth::user()->username;
use App\Helpers\Utility;
$util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-7"><b style="color:red;">Asset Product List </b></div>
                        <div  class="col-lg-5">
                            <a href="/assetProducts/create" class="btn mb-1 btn-primary">Add</a>
                            <a href="/assetProducts/dlist" class="btn mb-1 btn-primary">
                                Deactive List <span class="badge badge-danger ml-2">{{$dProductCount}}</span>
                            </a>
                            <a href="/assetProducts" class="btn mb-1 btn-success">
                                Active List <span class="badge badge-danger ml-2">{{$productCount}}</span>
                            </a>
                            <a href="/assetProducts/searchAssetProduct" class="btn mb-1 btn-primary" style="font-size: 14px !important;">
                            Print QR <span class="badge badge-danger ml-2"></span>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($products))
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;">No</th>
                                            <th style="font-size:13px;">Venture Name</th>
                                            <th style="font-size:13px;">Product Name</th>
                                            <th style="font-size:13px;">Branch</th>
                                            <th style="font-size:13px;">Location</th>
                                            <th style="font-size:13px;">Department</th>
                                            <th style="font-size:13px;">Qty</th>
                                            <th style="font-size:13px;">Added At</th>
                                            <th style="font-size:13px;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->ventureName}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->productName}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->branchName}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->mainLocation}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->departmentName}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->qty}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{date('d-m-Y H:i', strtotime($row->created_at))}}</td>
                                                <td style="padding: 0px 17px !important;">
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="badge bg-primary mr-2" href="/assetProducts/{{$row->id}}"><i class="fa fa-eye"></i></a>

                                                        <a class="badge bg-success mr-2" href="/assetProducts/{{$row->id}}/edit"><i class="fa fa-pencil"></i></a>
                                                       
                                                        <a class="badge bg-danger mr-2" onclick="return confirm('Are you sure?')" data-original-title="Deactivate"
                                                            href="/assetProducts/{{$row->id}}/deactivate"><i class="ri-delete-bin-line mr-0"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- <a class="badge bg-danger mr-2 mt-4 mb-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Export Excel Sheet"
                                                            href="/assetProducts/exportExcelSheet/1" target="_blank">Export Excel</a> -->
                            @else
                                <h4>Record not found</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
