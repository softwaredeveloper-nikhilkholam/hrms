@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Product List</b></div>
                            <div  class="col-lg-5">
                                <a href="/audits/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/audits/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2"></span>
                                </a>
                                <a href="/audits" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                    <table data-page-length='25' class="table table-bordered data-table mb-0 tbl-server-info">
                                        <thead>
                                            <tr class="ligth">
                                                <th width="5%">Sr No</th>
                                                <th width="10%">Product List</th>
                                                <th width="10%">HRMS Stock Count</th>
                                                <th width="10%">Audit Stock Count</th>
                                                <th width="10%">Difference Stock Count</th> 
                                                <th width="10%">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr class="ligth">
                                                <td width="5%">1</td>
                                                <td width="10%">Blue Pens</td>
                                                <td width="10%">5</td>
                                                <td width="10%">4</td>
                                                <td width="10%">1</td> 
                                                <td width="20%">
                                                    <a href="/audits/create" class="btn btn-warning  btn-sm" style="height:35px;font-size: 14px !important;
                                                    background-color: purple !important;
                                                    border-color: purple !important;
                                                    color: white !important;">Edit</a>
                                                    <a href="/audits/auditProductEntry" class="btn btn-danger">Delete</a>
                                                </td> 
                                            </tr>
                                        </thead>
                                        
                                    </table>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
