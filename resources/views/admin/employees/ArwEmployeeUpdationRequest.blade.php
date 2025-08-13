@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Profile Add Request</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                       <div class="card-header  border-0">
                            <div class="page-title">
                                <h4 class="page-title">Add Request</h4>
                            </div>
                            <div class="page-rightheader ml-md-auto">
                                <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                                    <div class="btn-list">
                                        <a href="/employees/profileRequestList" class="btn btn-primary mr-3">Update Request</a>
                                        <a href="#" class="btn btn-success mr-3">Add Request</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(isset($tempEmployees))
                                @if(count($tempEmployees) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-10">Requested At</th>
                                                    <th class="border-bottom-0">Name</th>
                                                    <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tempEmployees as $index => $tempEmp)
                                                    @php
                                                        $formData = is_array($tempEmp->form_data) ? $tempEmp->form_data : json_decode($tempEmp->form_data, true);
                                                    @endphp
                                                    <tr>
                                                        <th>{{ $index + 1 }}</th>
                                                        <td>{{ date('d-m-Y H:i', strtotime($tempEmp->created_at)) }}</td>
                                                        <td>{{ $formData['firstName'] ?? '-' }} {{ $formData['middleName'] ?? '-' }} {{ $formData['lastName'] ?? '-' }}</td>
                                                        <td>
                                                            <!-- View Button -->
                                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#viewModal{{ $index }}">
                                                                <i class="fa fa-eye"></i>
                                                            </button>

                                                            <!-- Modal for Viewing Full Data -->
                                                            <div class="modal fade" id="viewModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $index }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Employee Detail</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body" id="printArea{{ $index }}">
                                                                            <table class="table table-bordered table-striped">
                                                                                @foreach($formData as $key => $value)
                                                                                    <tr>
                                                                                        <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                                                                                        <td>
                                                                                            {{ is_array($value) ? json_encode($value) : $value }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button class="btn btn-primary" onclick="printDiv('printArea{{ $index }}')">Print</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a href="/employees/create?searchAadharCardNo={{$formData['aadhaarCardNo']}}" class="btn btn-danger btn-sm">Add Employee</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Records.</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
function printDiv(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // Reload after print to restore event bindings
}
</script>

