@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Deactive Scrap Category List</b></div>
                            <div  class="col-lg-5">
                                <a href="/scrapCategory/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/scrapCategory/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dCategoriesCt}}</span>
                                </a>
                                <a href="/scrapCategory" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$categoriesCt}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                @if(count($categories))
                                    <table data-page-length='25' class="table table-bordered data-table table-striped">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th width="5%">No</th>
                                                <th>Name</th>
                                                <th width="15%">Updated At</th>
                                                <th width="15%">Updated By</th>
                                                <th width="10%">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($categories as $category)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                    <td class="text-left" style="padding: 0px 17px !important;">{{$category->name}}</td>
                                                    <td style="padding: 0px 17px !important;">{{date('d-m-Y', strtotime($category->updated_at))}}</td>
                                                    <td style="padding: 0px 17px !important;">{{$category->updated_by}}</td>
                                                    <td style="padding: 0px 17px !important;">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/scrapCategory/{{$category->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate"
                                                                href="/scrapCategory/{{$category->id}}/activate"><i class="fa fa-check mr-0"></i></a>
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
