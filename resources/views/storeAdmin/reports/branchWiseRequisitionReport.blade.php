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
                            <div  class="col-lg-"><b style="color:red;">Branch Wise Requisition Report</b></div>
                            <div  class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ReportsController@branchWiseRequisitionReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                    <div class="row mt-2">
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Branches:</label>
                                {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Month:</label>
                                <input type="month" class="form-control" value="{{(isset($month))?$month:''}}" name="forMonth" required>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!} 
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive" width="10%">
                            @if(count($reports))
                                <table id="datatable" data-page-length='25' width="100%" class="table table-bordered data-table table-striped" style="">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th style="padding: 5px 17px !important;" width="5%">No</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Date</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Pending Req.</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Completed Req.</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Rejected Req.</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Cancelled Req.</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Hold Req.</th>
                                            <th style="padding: 5px 17px !important;" width="12%">Total Req.<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                        <?php $totalPending=$totalcompleted=$totalrejected=$totalcancel=$totalhold=$total=0;?>
                                        @foreach($reports as $report)
                                            <tr>
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;" >{{$report['forDate']}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($report['pending'])}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($report['completed'])}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($report['rejected'])}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($report['cancel'])}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($report['hold'])}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($report['hold']+$report['cancel']+$report['pending']+$report['completed']+$report['rejected'])}}</td>
                                                <?php
                                                    $totalPending=$totalPending+$report['pending'];
                                                    $totalcompleted=$totalcompleted+$report['completed'];
                                                    $totalrejected=$totalrejected+$report['rejected'];
                                                    $totalcancel=$totalcancel+$report['cancel'];
                                                    $totalhold=$totalhold+$report['hold'];
                                                    $total=$total+$report['hold']+$report['cancel']+$report['pending']+$report['completed']+$report['rejected'];
                                                ?>
                                            </tr>
                                        @endforeach
                                        <tr class="ligth ligth-data">
                                            <th style="padding: 5px 17px !important;" width="5%" colspan="2">Total</th>
                                            <th style="padding: 5px 17px !important;" width="12%">{{$util->numberFormatRound($totalPending)}}</th>
                                            <th style="padding: 5px 17px !important;" width="12%">{{$util->numberFormatRound($totalcompleted)}}</th>
                                            <th style="padding: 5px 17px !important;" width="12%">{{$util->numberFormatRound($totalrejected)}}</th>
                                            <th style="padding: 5px 17px !important;" width="12%">{{$util->numberFormatRound($totalcancel)}}</th>
                                            <th style="padding: 5px 17px !important;" width="12%">{{$util->numberFormatRound($totalhold)}}</th>
                                            <th style="padding: 5px 17px !important;" width="12%">{{$util->numberFormatRound($total)}}</th>
                                        </tr>
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
@endsection
