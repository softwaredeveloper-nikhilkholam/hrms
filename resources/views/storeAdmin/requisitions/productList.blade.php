<?php
 $username = Auth::user()->username;
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
                        <div  class="col-lg-7"><b style="color:red;">Active Product List </b></div>
                        <div  class="col-lg-5">
                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($products))
                                <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;">No</th>
                                            <th style="font-size:13px;">Name</th>
                                            <th style="font-size:13px;">Category</th>
                                            <th style="font-size:13px;">Sub Category</th>
                                            <th style="font-size:13px;">Size</th>
                                            <th style="font-size:13px;">Color</th>                                           
                                            <th style="font-size:13px;">Stock<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;" class="text-left">{{ucfirst($row->name)}}</td>
                                                <td style="padding: 0px 17px !important;">{{ucfirst($row->categoryName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{ucfirst($row->subCategoryName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->size}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->color}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($row->stock)}}</td>
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
