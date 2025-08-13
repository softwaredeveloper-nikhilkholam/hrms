@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Active Users List</b></div>
                            <div  class="col-lg-5">
                                <a href="/storeUser/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/storeUser/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dUsers}}</span>
                                </a>
                                <a href="/storeUser" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{count($users)}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($users))
                                    <table data-page-length='25' class="table table-bordered data-table table-striped">
                                        <thead>
                                            <tr class="ligth">
                                                <th style="font-size:14px;white-space: nowrap;">No</th>
                                                <th style="font-size:14px;white-space: nowrap;">User</th>
                                                <th style="font-size:14px;white-space: nowrap;">Designation</th>
                                                <th style="font-size:14px;white-space: nowrap;">Department</th>
                                                <th style="font-size:14px;white-space: nowrap;">Branch</th>
                                                <th style="font-size:14px;white-space: nowrap;">Username</th>
                                                <th style="font-size:14px;white-space: nowrap;">User Type</th>
                                                <th style="font-size:14px;white-space: nowrap;">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;" class="text-left">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->designationName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->departmentName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->branchName}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->username}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->userType}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/storeUser/{{$row->id}}/edit"><i class="fa fa-pencil mr-0"></i></a>
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deactivate"
                                                                href="/storeUser/{{$row->id}}/deactivate"><i class="fa fa-recycle mr-0"></i></a>
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
