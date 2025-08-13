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
                            <div  class="col-lg-"><b style="color:red;">Unpaid List({{count($payments)}})</b></div>
                            <div  class="col-lg-4">
                                <a href="/payments/POPaidPayments" class="btn mb-1 btn-primary">Paid List</a>
                                <a href="/payments/POPayment" class="btn mb-1 btn-success">Unpaid List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                                @if(count($payments))
                                    <div class="table-responsive">
                                        <table id="datatable" data-page-length='50' width="100%" class="table table-bordered data-table table-striped" style="">
                                            <thead class="bg-white text-uppercase">
                                                <tr class="ligth ligth-data">
                                                    <th style="padding: 5px 17px !important;" width="5%">No</th>
                                                    <th style="padding: 5px 17px !important;" width="10%">PO No</th>
                                                    <th style="padding: 5px 17px !important;">Vendor Name</th>
                                                    <th style="padding: 5px 17px !important;" width="10%">Pay Date</th>
                                                    <th style="padding: 5px 17px !important;" width="8%">Percent %</th>
                                                    <th style="padding: 5px 17px !important;" width="8%">Rs.</th>
                                                    <th style="padding: 5px 17px !important;" width="10%">Updated At<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="ligth-body">
                                                <?php $quotNO=0; ?>
                                                @foreach($payments as $payment)
                                                    <tr>
                                                        <td style="padding: 0px 17px !important;" class="text-left">{{$i++}}</td>
                                                        <td style="padding: 0px 17px !important;" class="text-left"><a href="/purchaseOrder/viewPONumber/{{$payment->poNumber}}" target="_blank">{{$payment->poNumber}}</a></td>
                                                        <td style="padding: 0px 17px !important;" class="text-left"><a href="/vendor/{{$payment->vendorId}}" target="_blank">{{$payment->vendorName}}</a></td>
                                                        <td style="padding: 0px 17px !important;" class="text-left">{{date('d-m-Y', strtotime($payment->forDate))}}</td>
                                                        <td style="padding: 0px 17px !important;" class="text-right">{{round($payment->percent, 0)}}%</td>
                                                        <td style="padding: 0px 17px !important;" class="text-right">{{$util->numberFormatRound($payment->amount)}}</td>
                                                        <td style="padding: 0px 17px !important;" class="text-left">{{date('d-m-Y H:i', strtotime($payment->updated_at))}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="/payments/0/1/exportPOPayments" class="btn mb-1 btn-success mt-5">Export Excel<i class="fa fa-excel-o"></i></a>
                                @else
                                    <h4>Record not found</h4>
                                @endif
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
