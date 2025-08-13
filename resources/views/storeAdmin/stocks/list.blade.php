<?php
$userType = Auth::user()->userType;
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
                            <div  class="col-lg-"><b style="color:red;">Stock List</b></div>
                            <div  class="col-lg-4">
                                <a href="/purchaseOrder/purchaseOrderList" class="btn mb-1 btn-primary">
                                    List <span class="badge badge-danger ml-2">{{count($stocks)}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($stocks))
                                    <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="padding: 0px 17px !important;" width="5%">No</th>
                                                <th style="padding: 0px 17px !important;" width="10%">Category</th>
                                                <th style="padding: 0px 17px !important;" width="10%">SubCategory</th>
                                                <th style="padding: 0px 17px !important;" width="15%">Product</th>
                                                <th style="padding: 0px 17px !important;" width="10%">Size</th>
                                                <th style="padding: 0px 17px !important;" width="10%">Color</th>
                                                <th style="padding: 0px 17px !important;" width="10%">Rate</th>
                                                <th style="padding: 0px 17px !important;" width="10%">Qty</th>
                                                <th style="padding: 0px 17px !important;" width="10%">Total<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($stocks as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$row->category}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$row->subCategory}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$row->productName}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$row->size}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$row->color}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-right">{{$util->numberFormatRound($row->productRate)}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-right">{{$util->numberFormatRound($row->stock)}}</td>
                                                    <td style="padding: 5px 17px !important;" class="text-right">{{$util->numberFormatRound($row->productRate*$row->stock)}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
