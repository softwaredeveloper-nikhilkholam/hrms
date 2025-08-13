<?php
 $branchId = Auth::user()->reqBranchId;
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
                        <div  class="col-lg-7"><b style="color:red;">Inward Deactive List </b></div>
                        <div  class="col-lg-5">
                            <a href="/subStores/inward" class="btn mb-1 btn-primary">Add</a>
                            <a href="/subStores/inwList" class="btn mb-1 btn-primary">
                                Active List <span class="badge badge-danger ml-2"></span>
                            </a>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;">Sr No</th>
                                            <th style="font-size:13px;">Req.Date</th>
                                            <th style="font-size:13px;">Req.No</th>
                                            <th style="font-size:13px;">Branch</th>
                                            <th style="font-size:13px;">Department</th>
                                            <th style="font-size:13px;">Requisition For</th>
                                            <th style="font-size:13px;">Sevirity</th>
                                            <th style="font-size:13px;">Inward Date</th>
                                            <th style="font-size:13px;">Status</th>
                                            <th style="font-size:13px;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td style="padding: 0px 17px !important;text-align:left;">1</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">04-Aug</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">ASCON/Aut-NonTea/2025/Aug/321</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">ASCON (Pune)</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">Authorities - Non Teaching</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">Test</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">Urgent</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">4 Aug</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">Complete</td>
                                                <td style="padding: 0px 17px !important;">
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="badge bg-primary mr-2" href=""><i class="fa fa-eye"></i></a>
                                                        
                                                        <a class="badge bg-danger mr-2" onclick="return confirm('Are you sure?')" data-original-title="Deactivate"
                                                            href=""><i class="ri-delete-bin-line mr-0"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                                <!-- <a class="badge bg-danger mr-2 mt-4 mb-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Export Excel Sheet"
                                                            href="/assetProducts/exportExcelSheet/1" target="_blank">Export Excel</a> -->
                                <h4>Record not found</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
