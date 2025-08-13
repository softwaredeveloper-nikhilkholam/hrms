@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-7"><b style="color:red;">Pending Repaire Product List</b></div>
                        <div  class="col-lg-5">
                            <a href="{{ route('repaires.create') }}" class="btn mb-1 btn-primary">Add</a>
                            <a href="{{ route('repaires.completedList') }}" class="btn mb-1 btn-primary">Completed List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($repaireProducts))
                                <table id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;">No</th>
                                            <th style="font-size:13px;">Date</th>
                                            <th style="font-size:13px;">Product</th>
                                            <th style="font-size:13px;">Description</th>
                                            <th style="font-size:13px;">Reason For Repaire</th>
                                            <th style="font-size:13px;">Product Count</th>
                                            <th style="font-size:13px;">Updated At</th>
                                            <th style="font-size:13px;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($repaireProducts as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;">{{date('d-m-Y', strtotime($row->forDate))}}</td>
                                                <td style="padding: 0px 17px !important;" class="text-left">{{ucfirst($row->productName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->description}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->reasonForRepaire}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->count}}</td>
                                                <td style="padding: 0px 17px !important;">{{date('d-m-Y H:i', strtotime($row->updated_at))}}</td>
                                                <td style="padding: 0px 17px !important;">
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="btn btn-primary btn-sm mr-2" href="{{ route('repaires.edit', $row->id) }}"><i class="fa fa-pencil mr-0"></i></a>
                                                        <!-- <a class="btn btn-success  btn-sm mr-2" href="{{ route('repaires.show', $row->id) }}"><i class="fa fa-pencil mr-0"></i></a> -->
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
