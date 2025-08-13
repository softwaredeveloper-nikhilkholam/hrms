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
                            <div  class="col-lg-8"><b style="color:red;">Purchase List ({{count($orders)}})</b></div>
                            <div  class="col-lg-4 text-right">
                                <a href="/purchaseOrder/purchaseOrderList" class="btn mb-1 btn-success">Unpaid PO</a>
                                <a href="/purchaseOrder/paidPurchaseOrderList" class="btn mb-1 btn-primary">Paid PO</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\PurchaseTransactions@purchaseOrderList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="autocomplete">
                                    <input id="myInputVendor" type="text" name="vendorName" value="{{$vendorName}}" class="form-control" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="poNumber" value="{{$poNumber}}" class="form-control" placeholder="PO Number">
                            </div>
                            <div class="col-md-2">
                                {{Form::select('raisedBys', $users, $raisedBys, ['placeholder'=>'Select Option','class'=>'form-control'])}}
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
                                @if(count($orders))
                                    <table  data-page-length='25' class="table table-bordered table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;">No</th>
                                                <th  style="font-size:14px;white-space: nowrap;">Gen. Date</th>
                                                <th  style="font-size:14px;white-space: nowrap;">Raised By</th>
                                                <th  style="font-size:14px;white-space: nowrap;">PO Number</th>
                                                <th  style="font-size:14px;white-space: nowrap;">Vendor Name</th>
                                                <th  style="font-size:14px;white-space: nowrap;">PO Rs.</th>
                                                <th  style="font-size:14px;white-space: nowrap;">Paid Rs</th>
                                                <th  style="font-size:14px;white-space: nowrap;">Bal. Rs</th>
                                                <th  style="font-size:14px;white-space: nowrap;">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($orders as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-left">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-left">{{date('d-M-Y', strtotime($row->created_at))}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-left">{{$util->getQuotRaisedBy($row->raisedBy)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-left">{{$row->poNumber}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-left">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-right">{{$util->numberFormatRound($row->poAmount)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-right">{{$util->numberFormatRound($row->paidAmount)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;{{(($row->poAmount-$row->paidAmount) != 0)?'color:red':''}};" class="text-right">{{$util->numberFormatRound($row->poAmount-$row->paidAmount)}}</td>
                                                    <td  style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Quotation"
                                                                href="/purchaseOrder/viewPO/{{$row->id}}"><i class="fa fa-eye mr-0"></i></a>
                                                            <a class="badge bg-danger mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print Quotation"
                                                                href="/purchaseOrder/viewPO/{{$row->id}}"><i class="fa fa-print mr-0"></i></a> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>
                                            {{$orders->links()}}
                                        </div>
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
