@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Deactive Sub Category List</b></div>
                            <div  class="col-lg-5">
                                <a href="/subCategory/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/subCategory/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dSubCategoryCt}}</span>
                                </a>
                                <a href="/subCategory" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$subCategoryCt}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($dSubCategory))
                                    <table data-page-length='25' class="table table-bordered data-table table-striped">
                                        <thead>
                                            <tr class="ligth">
                                                <th width="5%">No</th>
                                                <th>Category Name</th>
                                                <th>Sub Category Name</th>
                                                <th width="10%">Updated At</th>
                                                <th width="20%">Updated By</th>
                                                <th width="10%">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dSubCategory as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->categoryName}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;">{{date('d-m-Y', strtotime($row->updated_at))}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$row->updated_by}}</td>
                                                    <td style="padding: 0px 17px !important;">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/subCategory/{{$row->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deactivate"
                                                                href="/subCategory/{{$row->id}}/activate"><i class="fa fa-check mr-0"></i></a>
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
