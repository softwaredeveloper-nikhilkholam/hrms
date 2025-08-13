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
                            <div  class="col-lg-"><b style="color:red;">Product Wise Report</b></div>
                            <div  class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ReportsController@productWiseReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
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
                                    {{Form::select('productId', $products, $productId, ['placeholder'=>'Select Product','class'=>'form-control', 'id'=>'productId'])}}
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
                    @if(count($reports))
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="table-responsive" width="100%">
                                    <table id="datatable" width="100%" class="table table-bordered data-table  table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;" width="5%">No</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="20%">Date</th>
                                                <th style="font-size:14px;white-space: nowrap;">Branch</th>
                                                <th style="font-size:14px;white-space: nowrap;">Emoloyee</th>
                                                <th style="font-size:14px;white-space: nowrap;">Outward No</th>
                                                <th style="font-size:14px;white-space: nowrap;">Status<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($reports as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->branchName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->receiptNo}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{($row->status == 0)?'Pending':(($row->status == 1)?'Outward Generated':(($row->status == 2)?'Rejected':'InProgress'))}}</td>
                                                </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'></div>
                                        <div class='col-md-4'>
                                            <form action="{{ route('export.outwardReportExport') }}" method="GET">
                                                <input type="hidden" name="forMonth" value="{{ request()->forMonth }}">
                                                <button type="submit" class="btn btn-success">Export to Excel</button>
                                            </form>
                                        </div>
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

