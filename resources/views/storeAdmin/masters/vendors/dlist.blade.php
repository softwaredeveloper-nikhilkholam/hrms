@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-8"><b style="color:red;">Active Vendor List</b></div>
                            <div  class="col-lg-4">
                                <a href="/vendor/create" class="btn mb-1 btn-primary btn-lg">Add</a>
                                <a href="/vendor/dlist" class="btn mb-1 btn-success btn-lg">Deactive List <span class="badge badge-danger ml-2">{{count($dVendors)}}</span></a>
                                <a href="/vendor" class="btn mb-1 btn-primary btn-lg">Active List <span class="badge badge-danger ml-2">{{$vendors}}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                {!! Form::open(['action' => 'storeController\VendorsController@dlist', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="autocomplete">
                                    <input id="myInputVendor" type="text" name="myInputVendorName" value="{{$myInputVendorName}}" class="form-control" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="myInputCategory">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $row)
                                        <option value="{{$row->category}}" {{($row->category == $myInputCategory)?'selected':''}}>{{strtoupper($row->category)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input id="materialProvoides" type="text" name="materialProvoides" value="{{$materialProvoides}}" class="form-control" placeholder="Material Provides">
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive rounded mb-3">
                                @if(count($dVendors))
                                    <table id="" data-page-length='25' class="table table-bordered table-stripedfont-size:12px;">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;">No</th>
                                                <th style="font-size:14px;white-space: nowrap;">Name</th>
                                                <th style="font-size:14px;white-space: nowrap;">Category</th>
                                                <th style="font-size:14px;white-space: nowrap;">Contact Person 1</th>
                                                <th style="font-size:14px;white-space: nowrap;">Material Provides</th>
                                                <th style="font-size:14px;white-space: nowrap;">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($dVendors as $vendor)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left"><a href="/vendor/{{$vendor->id}}" style="color:black;">{{$vendor->name}}</a></td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left">{{$vendor->category}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left">{{$vendor->contactPerson1}}-{{$vendor->contactPerNo1}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left">{{$vendor->materialProvider}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:14px;white-space: nowrap;" class="text-left">
                                                        <div class="d-flex align-items-center list-action">
                                                            <a class="badge bg-primary mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                                                href="/vendor/{{$vendor->id}}/edit"><i class="ri-pencil-line mr-0"></i></a>
                                                            <a class="badge bg-danger mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate"
                                                                href="/vendor/{{$vendor->id}}/activate"><i class="fa fa-check mr-0"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>
                                            {{$dVendors->links()}}
                                        </div>
                                        <div class='col-md-4'>
                                            <a class="badge bg-primary mr-2" href="/vendor/0/exportExcel"><i class="fa fa-excel mr-0"></i></a>
                                        </div>
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
