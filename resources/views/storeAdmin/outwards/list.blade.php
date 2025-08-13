@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-8"><b style="color:red;">Outward List ({{count($outwards)}})</b></div>
                        <div  class="col-lg-4 text-right">
                            <a href="/outwards" class="btn mb-1 btn-success">List</a>                           
                            <a href="/outwards/oldList" class="btn mb-1 btn-primary">Old List</a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\OutwardsController@index', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="productName" value="{{ $productName }}" class="form-control" placeholder="Product Name">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="receiptNo" value="{{$receiptNo}}" class="form-control" placeholder="Receipt Number">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="requisitionNo" value="{{$requisitionNo}}" class="form-control" placeholder="Requition Number">
                        </div>
                        <div class="col-md-2">
                            {{Form::select('branchId', $branches, $branchId, ['placeholder'=>'Select Branch','class'=>'form-control'])}}
                        </div>
                        <div class="col-md-2">
                            <input type="month" class="form-control" value="{{(isset($forMonth))?$forMonth:date('Y-m')}}" name="forMonth">
                        </div>
                        <div class="col-md-2">
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
                                            <th style="font-size:12px;" width="5%">No</th>
                                            <th style="font-size:12px;" width="8%">Date</th>
                                            <th style="font-size:12px;" width="12%">Receipt No.</th>
                                            <th style="font-size:12px;" width="4%">Req. No.</th>                                           
                                            <th style="font-size:12px;">Requisition For</th>                                           
                                            <th style="font-size:12px;" width="4%">Requ. Date</th>
                                            <th style="font-size:12px;" width="4%">Branch</th>
                                            <th style="font-size:12px;" width="15%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($outwards as $row)
                                            <tr style="{{($row->normalOrEventReq == 2)?'background-color:#eee;':'background-color:white;'}}">
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>">{{$row->receiptNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>">{{$row->requisitionNo}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>" class="text-left">{{$row->requisitionFor}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>">{{date('d-m-Y', strtotime($row->dateOfRequisition))}}</td>
                                                <td style="padding: 0px 17px !important;font-size:12px;text-align:left;color:<?php echo ($row->forDate == date('Y-m-d'))?'red':'black';?>">{{$row->newBranchName}}</td>
                                                <td style="padding: 0px 17px !important;font-size:10px;text-align:left;">
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
