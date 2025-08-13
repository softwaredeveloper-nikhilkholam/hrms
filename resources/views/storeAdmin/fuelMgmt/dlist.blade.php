<?php
    $username = Auth::user()->username;
    $userId = Auth::user()->id;
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
                            <div  class="col-lg-7"><b style="color:red;">Deleted Fuel Entry List</b></div>
                            <div  class="col-lg-5 text-right">
                                <a href="/fuelSystems/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/fuelSystems/dlist" class="btn mb-1 btn-success">Deleted List</a>
                                <a href="/fuelSystems" class="btn mb-1 btn-primary">Active List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($rows))
                                    <table data-page-length='25' class="table table-bordered data-table table-striped">
                                        <thead>
                                            <tr class="ligth">
                                                <th style="font-size:14px;white-space: nowrap;" width="5%">No</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Date</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Vendor</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Branch</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Vehicle Count</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Total Fuel</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Total Rs.</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Status</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rows as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$row->vendor}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$row->zoneName}} ({{$row->branchName}})</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{round($row->totalVehicle)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$row->totalPetrol + $row->totalDiesel}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$util->numberFormatRound($row->totalDieselRs + $row->totalPetrolRs)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$row->Status}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-danger mr-2" href="/fuelSystems/fuelVehicleDetails/{{$row->id}}  "><i class="fa fa-list"></i></a>
                                                            <a class="badge bg-primary mr-2" href="/fuelSystems/exportExcelSheet/{{$row->id}}"><i class="fa fa-print"></i></a>
                                                            @if($row->active == 1)
                                                                <a class="badge bg-danger mr-2" href="/fuelSystems/{{$row->id}}/activeDeactivateFuelEntry"><i class="fa fa-trash-o"></i></a>
                                                            @else
                                                                <a class="badge bg-success mr-2" href="/fuelSystems/{{$row->id}}/activeDeactivateFuelEntry"><i class="fa fa-check"></i></a>
                                                            @endif
                                                        </div>
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
