@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Deactive Users List</b></div>
                            <div  class="col-lg-5">
                                <a href="/storeUser/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/storeUser/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{count($users)}}</span>
                                </a>
                                <a href="/storeUser" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$aciveUsers}}</span>
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
                                                <th width="5%">No</th>
                                                <th>User</th>
                                                <th width="15%">Designation</th>
                                                <th width="15%">Department</th>
                                                <th width="10%">Branch</th>
                                                <th width="10%">Username</th>
                                                <th width="12%">Updated At</th>
                                                <th width="10%">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;" class="text-left">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->designationName}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->departmentName}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->branchName}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->username}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->updated_by}}<br>{{date('d-m-Y', strtotime($row->updated_at))}}</td>
                                                    <td style="padding: 0px 17px !important;">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/storeUser/{{$row->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deactivate"
                                                                href="/storeUser/{{$row->id}}/activate"><i class="ri-delete-bin-line mr-0"></i></a>
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
