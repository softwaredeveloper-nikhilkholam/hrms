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
                        <div  class="col-lg-7"><b style="color:red;">Deactive Product List</b></div>
                        <div  class="col-lg-5">
                            <a href="/product/create" class="btn mb-1 btn-primary">Add</a>
                            <a href="/product/dlist" class="btn mb-1 btn-success">Deactive List <span class="badge badge-danger ml-2">{{$deactiveCount}}</span></a>
                            <a href="/product" class="btn mb-1 btn-primary">Active List <span class="badge badge-danger ml-2">{{$activeCount}}</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"></div>
                            {!! Form::open(['action' => 'storeController\ProductsController@dlist', 'method' => 'GET', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div  class="col-lg-5  d-flex">
                                    <input type="text" value="{{$search}}" size="100" placeholder="Search....." name="search" class="form-control">
                                    <button class="btn btn-danger" type="submit">Search</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($products))
                                <table id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;">No</th>
                                            <th style="font-size:13px;">Name</th>
                                            <th style="font-size:13px;">Category</th>
                                            <th style="font-size:13px;">Sub Category</th>
                                            <th style="font-size:13px;">Size</th>
                                            <th style="font-size:13px;">Color</th>
                                            <th style="font-size:13px;">Location <br> H/R/S</th>
                                            <th style="font-size:13px;">Stock</th>
                                            <th style="font-size:13px;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $row)
                                            <tr>
                                                @php  $stock = $util->getCurrentProductStock($row->id); @endphp
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;" class="text-left">{{ucfirst($row->name)}}</td>
                                                <td style="padding: 0px 17px !important;">{{ucfirst($row->categoryName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{ucfirst($row->subCategoryName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->size}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->color}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->hallName}} / {{$row->rackName}} / {{$row->shelfName}}</td>
                                                <td style="padding: 0px 17px !important;">{{$util->numberFormatRound($stock)}} <br>[Rs. {{$util->numberFormatRound($stock*$row->productRate)}}]</td>
                                                <td style="padding: 0px 17px !important;">
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View {{$row->name}}"
                                                        href="/product/{{$row->id}}"><i class="fa fa-eye"></i></a>
                                                        <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit {{$row->name}}"
                                                            href="/product/{{$row->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                        <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate {{$row->name}}"
                                                            href="/product/{{$row->id}}/activate"><i class="ri-delete-bin-line mr-0"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$products->links()}}</div>
                                    <div class='col-md-4'><a class="badge bg-danger mr-2 mt-4 mb-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Export Excel Sheet" href="/produdcts/exportExcelSheet/{{($search != '')?$search:'-'}}/0" target="_blank">Export Excel</a></div>
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
