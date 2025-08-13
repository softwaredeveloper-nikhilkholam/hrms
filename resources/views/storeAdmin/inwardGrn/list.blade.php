@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-8"><b style="color:red;">Inward GRN List </b></div>
                        <div  class="col-lg-4">
                            <a href="/inwards/create" class="btn mb-1 btn-primary">Add GRN Inward</a>
                            <a href="/inwards" class="btn mb-1 btn-primary">
                               Inward List <span class="badge badge-danger ml-2">{{count($inwardGrns)}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($inwardGrns))
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th width="5%">No</th>
                                            <th width="8%">Date</th>
                                            <th width="14%">Invoice Number</th>
                                            <th width="10%">Discount</th>
                                            <th width="10%">GST</th>
                                            <th width="10%">Grand Total</th>
                                            <th width="10%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inwardGrns as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->invoiceNo}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->discount}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->gst}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->grandTotal}}</td>
                                                <td style="padding: 0px 17px !important;"><a href="/inwards/{{$row->id}}" style="font-size: 12px !important;" class="btn btn-primary">Show</a></td>
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
