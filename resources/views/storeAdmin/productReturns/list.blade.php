@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-6"><b style="color:red;">Product Return List</b></div>
                        <div  class="col-lg-6">
                            <a href="/inwards/productReturn" class="btn mb-1 btn-primary">Add Return</a>
                            <a href="/inwards/productReturnList" class="btn mb-1 btn-success">
                                Return List <span class="badge badge-danger ml-2">{{count($productReturns)}}</span>
                            </a>  
                            <a href="/requisitions/reqProductReturnList" class="btn mb-1 btn-primary">Req. Product Return</a>                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($productReturns))
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="padding: 10px 17px !important;"  width="5%">No</th>
                                            <th style="padding: 10px 17px !important;"  width="8%">Date</th>
                                            <th style="padding: 10px 17px !important;"  width="10%">Branch</th>
                                            <th style="padding: 10px 17px !important;"  width="15%">Return By</th>                                           
                                            <th style="padding: 10px 17px !important;">Remark</th>
                                            <th style="padding: 10px 17px !important;" width="15%">Updated</th>
                                            <th style="padding: 10px 17px !important;" width="5%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($productReturns as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->branchName}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->returnBy}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->remark}}</td>
                                                <td style="padding: 0px 17px !important;">{{date('d-m-Y H:i', strtotime($row->created_at))}}<br>{{$row->updated_by}}</td>
                                                <td style="padding: 0px 17px !important;">
                                                    <a href="/inwards/productReturnView/{{$row->id}}" style="font-size: 12px !important;" class="btn btn-danger">Show</a>                                                    
                                                </td>
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
