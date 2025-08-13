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
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ReportsController@openingStockReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="startDate" class="form-control" value="{{date('Y-m-d', strtotime($startDate))}}" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="endDate" class="form-control" value="{{date('Y-m-d', strtotime($endDate))}}" max="{{date('Y-m-d')}}" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                {{ Form::select('productId', $products, $productId, [
                                    'placeholder' => 'Select Product',
                                    'class' => 'form-control select2',
                                    'id' => 'productId'
                                ]) }}
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <hr/>
                    @if(count($ledgers))
                        <div class="row">
                            <div class="col-md-2">
                                <label style="color:red;">Opening Stock : {{$util->numberFormatRound($openingStock)}}</label>
                            </div>
                            <div class="col-md-3">
                                <label  style="color:red;">Opening Stock Date : {{date('d-m-Y', strtotime($product->openingStockForDate))}}</label>
                            </div>
                            <div class="col-md-7">
                                <label  style="color:red;">Product Name : <a href="/product/{{$product->id}}" target="_blank">{{$product->name}}</a></label>
                            </div>
                        </div>
                        <hr/>
                        <div class="row  justify-content-center">
                            <div class="col-lg-10 text-center">
                                <div class="table-responsive" width="100%">
                                    <table id="" width="100%" class="table table-bordered text-center table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;" width="5%">No</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="20%">Date</th>
                                                <th style="font-size:14px;white-space: nowrap;">Inward Qty</th>
                                                <th style="font-size:14px;white-space: nowrap;">Outward Qty</th>
                                                <th style="font-size:14px;white-space: nowrap;">Return Qty</th>
                                                <th style="font-size:14px;white-space: nowrap;">Closing Stock</th>
                                                <th style="font-size:14px;white-space: nowrap;">Updated At<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            <?php $closingStock = $openingStock; ?>
                                            @foreach($ledgers as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->inwardQty}}&nbsp;{{$row->unitName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->outwardQty}}&nbsp;{{$row->unitName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->returnQty}}&nbsp;{{$row->unitName}}</td>
                                                    <?php $closingStock =  $closingStock + ($row->inwardQty +  $row->returnQty) - $row->outwardQty; ?>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$closingStock}}&nbsp;{{$row->unitName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y H:i', strtotime($row->created_at))}}</td>
                                                </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-6"><a href="{{ route('reports.openingStocExportToExcel', ['startDate'=>encrypt($startDate), 'endDate'=>encrypt($endDate), 'productId'=>encrypt($productId)]) }}" class="btn btn-primary">Export</a></div>
                            <div class="col-md-6"></div>
                        </div>
                    @else
                        <h5>Record not found</h5>
                    @endif
            </div>
        </div>
    </div>

@endsection

