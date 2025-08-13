<?php
    $username = Auth::user()->username;
    $userId = Auth::user()->id;
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
                            <div  class="col-lg-7"><b style="color:red;">Fuel Entry List</b></div>
                            <div  class="col-lg-5 text-right">
                                <a href="/fuelSystems/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/fuelSystems/dlist" class="btn mb-1 btn-primary">Deleted List</a>
                                <a href="/fuelSystems" class="btn mb-1 btn-success">Active List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($rows))
                                    <table class="table table-bordered table-hover table-striped data-table" data-page-length='25'>
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Vendor</th>
                                                <th>Branch</th>
                                                <th>Vehicles</th>
                                                <th>Total Fuel</th>
                                                <th>Total Rs.</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $status = [
                                                0 => ['label' => 'Pending', 'class' => 'bg-secondary text-white', 'icon' => 'fa-clock'],
                                                1 => ['label' => 'Quotation Generated', 'class' => 'bg-info text-white', 'icon' => 'fa-file-alt'],
                                                2 => ['label' => 'Quotation Approved', 'class' => 'bg-success text-white', 'icon' => 'fa-check-circle'],
                                                3 => ['label' => 'Payment Done', 'class' => 'bg-primary text-white', 'icon' => 'fa-credit-card']
                                            ];
                                        @endphp
                                            @php $i = 1; @endphp
                                            @foreach($rows as $row)
                                                <tr>
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($row->forDate)) }}</td>
                                                    <td>{{ $row->vendorName }}</td>
                                                    <td>{{ $row->zoneName }} ({{ $row->branchName }})</td>
                                                    <td class="text-center">{{ round($row->totalVehicle) }}</td>
                                                    <td class="text-center">{{ $row->totalPetrol + $row->totalDiesel }}</td>
                                                    <td class="text-right">{{ $util->numberFormatRound($row->totalDieselRs + $row->totalPetrolRs) }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            $statusKey = (int) $row->Status;
                                                        @endphp
                                                        @if(array_key_exists($statusKey, $status))
                                                            <span class="badge {{ $status[$statusKey]['class'] }}">
                                                                <i class="fa {{ $status[$statusKey]['icon'] }}"></i> {{ $status[$statusKey]['label'] }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-dark text-white"><i class="fa fa-question-circle"></i> Unknown</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="/fuelSystems/fuelVehicleEntry/{{ $row->id }}" class="btn btn-success" title="Vehicle Entry">
                                                                <i class="fa fa-bus"></i>
                                                            </a>&nbsp;
                                                            <a href="/fuelSystems/{{ $row->id }}/edit" class="btn btn-warning" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>&nbsp;
                                                            <a href="/fuelSystems/fuelVehicleDetails/{{ $row->id }}" class="btn btn-danger" title="Details">
                                                                <i class="fa fa-list"></i>
                                                            </a>&nbsp;
                                                            <a href="/fuelSystems/exportExcelSheet/{{ $row->id }}" class="btn btn-primary" title="Export">
                                                                <i class="fa fa-print"></i>
                                                            </a>&nbsp;
                                                            <a href="/fuelSystems/generateQuotation/{{ $row->id }}" class="btn btn-info" title="Quotation">
                                                                <i class="fa fa-file-alt"></i>
                                                            </a>&nbsp;
                                                            <a href="/fuelSystems/{{ $row->id }}/activeDeactivateFuelEntry"
                                                                class="btn {{ $row->active == 1 ? 'btn-danger' : 'btn-success' }}" 
                                                                title="{{ $row->active == 1 ? 'Deactivate' : 'Activate' }}">
                                                                <i class="fa {{ $row->active == 1 ? 'fa-trash' : 'fa-check' }}"></i>
                                                            </a>
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
