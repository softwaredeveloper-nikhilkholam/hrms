<?php
 use App\Helpers\Utility;
 $util=new Utility(); 
 $userType = Auth::user()->userType;
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
                        <div  class="col-lg-8"><b style="color:red;">Inward / GRN List</b></div>
                        <div  class="col-lg-4">
                            @if($userType != '61')
                                <a href="/inwards/create" class="btn mb-1 btn-primary">Add Inward / GRN</a>
                            @endif
                            <a href="/inwards" class="btn mb-1 btn-success">
                                Inward / GRN List <span class="badge badge-danger ml-2">{{$countInwards}}</span>
                            </a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\InwardsController@index', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="autocomplete">
                                <input id="myInputVendor" type="text" name="vendorName" value="{{$vendorName}}" class="form-control" placeholder="Vendor Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="poNumber" value="{{$poNumber}}" class="form-control" placeholder="PO Number">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="invoiceNo" value="{{$invoiceNo}}" class="form-control" placeholder="Invoice Number">
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!} 
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($inwards))
                                <table id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:14px;white-space: nowrap;"  width="5%">No</th>
                                            <th style="font-size:14px;white-space: nowrap;"  width="10%">Date</th>
                                            <th style="font-size:14px;white-space: nowrap;">Vendor</th>
                                            <th style="font-size:14px;white-space: nowrap;"  width="15%">PO No.</th>                                           
                                            <th style="font-size:14px;white-space: nowrap;" width="15%">Invoice No.</th>
                                            <th style="font-size:14px;white-space: nowrap;" width="10%">Bill No.</th>
                                            <th style="font-size:14px;white-space: nowrap;" width="10%">Grand Total</th>
                                            <th style="font-size:14px;white-space: nowrap;" width="10%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inwards as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->vendorName}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;"><a href="/purchaseOrder/viewPO/{{$row->purchaseOrderId}}" target="_blank">{{$row->poNumber}}</a></td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->invoiceNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->billNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$util->numberFormat($row->netTotal)}}</td>
                                                <td  style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">
                                                    <a href="/inwards/{{$row->id}}" style="font-size: 8px !important;" class="btn btn-primary"><i class="fa fa-eye"></i></a>  
                                                    <a href="/inwards/printInward/{{$row->id}}" style="font-size: 8px !important;" class="btn btn-success" target="_blank"><i class="fa fa-print"></i></a>                                                   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$inwards->links()}}</div>
                                    <div class='col-md-4'></div>
                                </div>
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
