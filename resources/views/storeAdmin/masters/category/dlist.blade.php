@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Deactive Product Category List</b></div>
                            <div  class="col-lg-5">
                                <a href="/category/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/category/dlist" class="btn mb-1 btn-primary">Deactive List <span class="badge badge-danger ml-2">{{$dCategoriesCt}}</span></a>
                                <a href="/category" class="btn mb-1 btn-primary">Active List <span class="badge badge-danger ml-2">{{$categoriesCt}}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                @if(count($categories))
                                <table class="data-table table table-bordered  mb-0 tbl-server-info">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;">No</th>
                                                <th style="font-size:14px;white-space: nowrap;">Name</th>
                                                <th style="font-size:14px;white-space: nowrap;">Updated At</th>
                                                <th style="font-size:14px;white-space: nowrap;">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($categories as $category)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$category->name}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{date('d-m-Y', strtotime($category->updated_at))}}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/category/{{$category->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate"
                                                                href="/category/{{$category->id}}/activate"><i class="fa fa-check mr-0"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'></div>
                                        <div class='col-md-4'><a class="btn btn-danger"  href="/categories/exportToExcel/0" target="_blank">Export Excel</a></div>
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
