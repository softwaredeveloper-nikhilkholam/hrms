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
                            <div  class="col-lg-"><b style="color:red;">Work Order Report</b></div>
                            <div  class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ReportsController@workOrderReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-2">
                                <input type="month" name="forMonth" class="form-control" value="{{date('Y-m')}}" placeholder="Vendor Name">
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="table-responsive" width="100%">
                                @if(count($reports))
                                    <table id="datatable" width="100%" class="table table-bordered data-table  table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;" width="5%">No</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Date</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Branch</th>
                                                <th style="font-size:14px;white-space: nowrap;">Employee</th>
                                                <th style="font-size:14px;white-space: nowrap;">Vendor Name</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Payment Status</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">WO Number<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($reports as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($row->generatedDate))}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->branchName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->raisedBy}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->vendorName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;color:<?php echo (($row->poAmount - $row->paidAmount) == 0)?'green':(($row->paidAmount == 0)?'red':'orange'); ?>">{{(($row->poAmount - $row->paidAmount) == 0)?'Paid':(($row->paidAmount == 0)?'Unpaid':'Partially')}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->poNumber}}</td>
                                                </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'></div>
                                        <div class='col-md-4'>
                                            <form action="{{ route('export.workOrderReportExport') }}" method="GET">
                                                <input type="hidden" name="forMonth" value="{{ request()->forMonth }}">
                                                <button type="submit" class="btn btn-success">Export to Excel</button>
                                            </form>
                                        </div>
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

