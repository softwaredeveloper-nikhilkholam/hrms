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
                            <div  class="col-lg-"><b style="color:red;">Opening Stock Report</b></div>
                            <div  class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
                <style>
                    * {
                    box-sizing: border-box;
                    }

                    /*the container must be positioned relative:*/
                    .autocomplete {
                    position: relative;
                    display: inline-block;
                    }

                    input {
                    border: 1px solid transparent;
                    background-color: #f1f1f1;
                    padding: 5px;
                    font-size: 12px;
                    }

                    input[type=text] {
                    background-color: #f1f1f1;
                    width: 100%;
                    }

                    input[type=submit] {
                    background-color: DodgerBlue;
                    color: #fff;
                    cursor: pointer;
                    }

                    .autocomplete-items {
                    position: absolute;
                    border: 1px solid #d4d4d4;
                    border-bottom: none;
                    border-top: none;
                    z-index: 99;
                    /*position the autocomplete items to be the same width as the container:*/
                    top: 100%;
                    left: 0;
                    right: 0;
                    }

                    .autocomplete-items div {
                    padding: 10px;
                    cursor: pointer;
                    background-color: #fff; 
                    border-bottom: 1px solid #d4d4d4; 
                    }

                    /*when hovering an item:*/
                    .autocomplete-items div:hover {
                    background-color: #e9e9e9; 
                    }

                    /*when navigating through the items using the arrow keys:*/
                    .autocomplete-active {
                    background-color: DodgerBlue !important; 
                    color: #ffffff; 
                    }
                </style>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ReportsController@monthlyOpeningStockReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Month</label>
                                    <input type="month" name="forMonth" class="form-control" value="{{date('Y-m', strtotime($forMonth))}}" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product</label>
                                    {{Form::select('productId', $productList, $productId, ['placeholder'=>'Select Product','class'=>'form-control', 'id'=>'productId'])}}
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label></label>
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <hr>
                    @if(count($products))
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="table-responsive" width="100%">
                                    <table id="" width="100%" class="table table-bordered table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;" width="5%">No</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="20%">Product Name</th>
                                                <th style="font-size:14px;white-space: nowrap;">Opening Stock</th>
                                                <th style="font-size:14px;white-space: nowrap;">Closing Stock<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($products as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$util->numberFormatRound($row->openingStock)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$util->numberFormatRound($row->closingStock)}}</td>
                                                 </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>{{$products->links()}}</div>
                                        <div class='col-md-4'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <h4>Record not found</h4>
                    @endif
            </div>
        </div>
    </div>

@endsection

