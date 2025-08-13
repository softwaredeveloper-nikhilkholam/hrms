@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Deactive Hall List</b></div>
                            <div  class="col-lg-5">
                                <a href="/hall/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/hall/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{count($dHalls)}}</span>
                                </a>
                                <a href="/hall" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$halls}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                @if(count($dHalls))
                                    <table data-page-length='25' class="table table-bordered data-table mb-0 tbl-server-info">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Last Updated At</th>
                                                <th>Last Updated By</th>
                                                <th>Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($dHalls as $hall)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">H-{{$hall->name}}</td>
                                                    <td style="padding: 0px 17px !important;">{{date('d-m-Y h:i A', strtotime($hall->updated_at))}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$hall->updated_by}}</td>
                                                    <td style="padding: 0px 17px !important;">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/hall/{{$hall->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate"
                                                                href="/hall/{{$hall->id}}/activate"><i class="fa fa-check"></i></a>
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
