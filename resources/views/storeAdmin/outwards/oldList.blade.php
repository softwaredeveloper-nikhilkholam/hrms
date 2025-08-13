@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-8"><b style="color:red;">Old Outward List ({{count($outwards)}})</b></div>
                        <div  class="col-lg-4">
                            <a href="/outwards" class="btn mb-1 btn-primary">List</a>                           
                            <a href="/outwards/oldList" class="btn mb-1 btn-success">Old List</a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\OutwardsController@oldList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="receiptNo" value="{{$receiptNo}}" class="form-control" placeholder="Receipt Number">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="requisitionNo" value="{{$requisitionNo}}" class="form-control" placeholder="Requition Number">
                        </div>
                        <div class="col-md-2">
                            {{Form::select('branchId', $branches, $branchId, ['placeholder'=>'Select Branch','class'=>'form-control'])}}
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <input type="month" class="form-control" value="{{(isset($forMonth))?$forMonth:''}}" name="forMonth" required>
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
                            @if(count($outwards))
                                <table id="data-table" data-page-length='25' class="table data-table table-bordered table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:14px;white-space: nowrap;"  width="3%">No</th>
                                            <th style="font-size:14px;white-space: nowrap;">Date</th>
                                            <th style="font-size:14px;white-space: nowrap;">Receipt No.</th>
                                            <th style="font-size:14px;white-space: nowrap;">Req. No.</th>                                           
                                            <th style="font-size:14px;white-space: nowrap;">Requisition For</th>                                           
                                            <th style="font-size:14px;white-space: nowrap;">Requ. Date</th>
                                            <th style="font-size:14px;white-space: nowrap;">Branch</th>
                                            <th style="font-size:14px;white-space: nowrap;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($outwards as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->receiptNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->requisitionNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$row->requisitionFor}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->dateOfRequisition}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->newBranchName}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">
                                                    <a href="/outwards/{{$row->id}}" class="btn btn-primary" style="font-size:10px !important;"><i class="fa fa-eye"></i></a>
                                                    <a href="/outwards/printOutward/{{$row->id}}" target="_blank" style="font-size:10px !important;" class="btn btn-success"><i class="fa fa-print"></i></a>
                                                    <a href="/outwards/productReturn/{{$row->id}}" target="_blank" style="font-size:10px !important;" class="btn btn-danger"><i class="fa fa-exchange"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'></div>
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
