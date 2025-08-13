@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Active Fuel Entry List</b></div>
                            <div  class="col-lg-5 text-right">
                                <a href="/fuelSystems/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/fuelSystems/dlist" class="btn mb-1 btn-primary">Deactive List</a>
                                <a href="/fuelSystems" class="btn mb-1 btn-primary">Active List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php 
                        if($fuelEntryList->fuelType == 1)
                            $fuelRate = $fuelEntryList->dieselRate;
                        else
                            $fuelRate = $fuelEntryList->petrolRate;
                    @endphp 
                    <div class="row">
                        <div class="col-lg-12">
                          <h5 class="text-center">AARYANS WORLD SCHOOL "<b style="color:red;">{{$fuelEntryList->zoneName}} Zone</b>" - FUEL REPORT</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <b class="text-left" style="color:black;">Date: {{date('d-m-Y', strtotime($fuelEntryList->forDate))}}</b>
                        </div>
                        <div class="col-lg-4">
                            <b class="text-left" style="color:black;">Vendor Name: {{$fuelEntryList->vendor}}</b>
                        </div>
                        <div class="col-lg-4">
                            <b class="text-right" style="color:black;">Rate: Rs.{{$fuelRate}}</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($vehicleList))
                                    <style>
                                        table, th, td {
                                            border: 1px solid black;
                                            border-collapse: collapse;
                                            color:black;
                                            text-align: center !important;
                                            }
                                    </style>
                                    <table width="100%">
                                        <thead>
                                            <tr class="ligth">
                                                <th width="5%">No</th>
                                                <th width="10%">Bus No</th>
                                                <th width="10%">Old KM</th>
                                                <th width="10%">New KM</th>
                                                <th width="10%">KM</th>
                                                <th width="10%">No of Ltr</th>
                                                <th width="10%">Amount</th>
                                                <th width="10%">Average KM</th>
                                                <th width="10%">Speed Meter<i class="fa fa-image"></i></th>
                                                <th width="10%">Machine Reading<i class="fa fa-image"></i><?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalKM=0; @endphp
                                            @php $totalAmount=0; @endphp
                                            @php $totalLtr=0; @endphp
                                            @foreach($vehicleList as $row)
                                                @php $diffKM=0; @endphp
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td class="text-center">{{$row->busNo}}</td>
                                                    <td class="text-center">{{$row->oldKM}}</td>
                                                    <td class="text-center">{{$row->newKM}}</td>
                                                    <td class="text-center">{{$diffKM = $row->newKM - $row->oldKM}}</td>
                                                    <td class="text-center">{{$row->ltr}}</td>
                                                    <td class="text-center">{{$row->ltr * $row->fuelRate}}</td>
                                                    <td class="text-center">{{$row->average}}</td>
                                                    <td class="text-center">@if($row->image1 != '')<a href="/storeAdmin/images/fuelFillingimages/fuelReceipts/{{$row->image1}}" target="_blank">✅ Uploaded </a> @else ❌ Not Uploaded @endif</td>
                                                    <td class="text-center">@if($row->image2 != '')<a href="/storeAdmin/images/fuelFillingimages/fuelReceipts/{{$row->image2}}" target="_blank">✅ Uploaded </a> @else ❌ Not Uploaded @endif</td>
                                                </tr>
                                                @php $totalKM = $totalKM + $diffKM; @endphp
                                                @php $totalLtr = $totalLtr + $row->ltr; @endphp
                                                @php $totalAmount = $totalAmount + ($row->ltr * $row->fuelRate); @endphp
                                            @endforeach
                                        </tbody>
                                        <tfooter>
                                            <tr>
                                                <td colspan="4" class="text-center"><b>Total</b></td>
                                                <td class="text-center"><b>{{$totalKM}}</b></td>
                                                <td class="text-center"><b>{{$totalLtr}}</b></td>
                                                <td class="text-center"><b>Rs. {{$totalAmount}}</b></td>
                                                <td></td>
                                            </tr>
                                        </tfooter>
                                    </table>
                                @else
                                    <h4>Record not found</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-10"></div>
                        <div class="col-lg-2"><a href="/fuelSystems/exportExcelSheet/{{$fuelEntryList->id}}" class="btn btn-primary"><i class="fa fa-excel" style="font-size:24px"></i>Export</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
