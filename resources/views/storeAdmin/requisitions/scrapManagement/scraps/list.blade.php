<?php
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
                        <div  class="col-lg-9"><b style="color:red;">Scrap List</b></div>
                        <div  class="col-lg-3">
                            <a href="/scraps/create" class="btn mb-1 btn-primary">Add Scrap</a>
                            <a href="/scraps" class="btn mb-1 btn-primary">
                                Scrap List <span class="badge badge-danger ml-2">{{$countScraps}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($scraps))
                                <table data-page-length='25' class="table table-bordered data-table table-striped">
                                    <thead>
                                        <tr class="ligth">
                                            <th width="5%">No</th>
                                            <th>Product Name</th>
                                            <th width="15%">Scrap Type</th>
                                            <th width="10%">Date Of Scrap</th>
                                            <th width="10%">Count</th>
                                            <th width="10%">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($scraps as $row)
                                            <tr>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->productName}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$row->scrapCategoryName}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{date('d-m-Y', strtotime($row->dateOfScrap))}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">{{$util->numberFormat($row->qty)}}</td>
                                                <td style="padding: 0px 17px !important;text-align:left;">
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                            href="/scraps/{{$row->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deactivate"
                                                            href="/scraps/{{$row->id}}"><i class="fa fa-eye mr-0"></i></a>
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
