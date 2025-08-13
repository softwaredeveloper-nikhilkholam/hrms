<?php
    use App\Helpers\Utility;
    $util = new Utility();
    $userType = Auth::user()->userType;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content')  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@php
    function getStatusIcon($value) {
        if ($value == 0) {
            return '<span class="text-danger"><i class="fas fa-hourglass-half"></i> <b>Pending</b></span>';
        } elseif ($value == 1) {
            return '<span class="text-warning"><i class="fas fa-spinner fa-spin"></i> <b>In Progress</b></span>';
        } else {
            return '<span class="text-success"><i class="fas fa-check-circle"></i> <b>Completed</b></span>';
        }
    }
@endphp

<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Closed NDC</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            @if($userType == '51')
                                <a href="/exitProces/apply" class="btn btn-primary mr-3">Raise Resignation</a>
                            @endif
                            <a href="/exitProces/standardProcess" class="btn btn-primary mr-3">In-Progress List</a>
                            <a href="/exitProces/archieveStandardProcess" class="btn btn-success mr-3">Archieve List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">NDC List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($resins) && count($resins) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                        <thead>
                                            <tr> 
                                                <th width="5%">No</th>
                                                <th width="7%">Type</th>
                                                <th width="12%">Name</th>
                                                <th width="5%">Reporting Authority</th>
                                                <th width="4%">Store Dept</th>
                                                <th width="4%">IT Dept</th>
                                                <th width="4%">ERP Dept</th>
                                                <th width="4%">HR Dept</th>
                                                <th width="4%">MD/CEO/COO</th>
                                                <th width="4%">Accounts Dept</th>
                                                <th width="4%">Status</th>
                                                <th width="5%">Updated</th>
                                                <th>Actions<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resins as $temp)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>
                                                        {{
                                                            ($temp->processType == 1) ? 'Standard' :
                                                            (($temp->processType == 2) ? 'Absconding' :
                                                            (($temp->processType == 3) ? 'Sabitical' : 'Termination'))
                                                        }}
                                                    </td>
                                                    <td>
                                                        <a href="/exitProces/view/{{$temp->id}}">
                                                            <h5>{{$temp->empCode}} - {{$temp->name}}</h5>
                                                            <h6>{{$temp->section}}</h6>
                                                            Raised Date: {{date('d-m-Y', strtotime($temp->applyDate))}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <b>{!! getStatusIcon($temp->reportingAuth) !!}</b><br>
                                                        <small>{{$temp->reportingAuthorityName}}</small><br>
                                                        {{ $temp->reportingAuthDate ? date('d-m-Y h:i A', strtotime($temp->reportingAuthDate)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->storeDept) !!}<br>
                                                        {{ $temp->storeDeptDate ? date('d-m-Y h:i A', strtotime($temp->storeDeptDate)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->itDept) !!}<br>
                                                        {{ $temp->itDeptDate ? date('d-m-Y h:i A', strtotime($temp->itDeptDate)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->erpDept) !!}<br>
                                                        {{ $temp->erpDeptDate ? date('d-m-Y h:i A', strtotime($temp->erpDeptDate)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->hrDept) !!}<br>
                                                        {{ $temp->hrDeptDate ? date('d-m-Y h:i A', strtotime($temp->hrDeptDate)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->finalPermission) !!}<br>
                                                        {{ $temp->updated_at ? date('d-m-Y h:i A', strtotime($temp->updated_at)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->accountDept) !!}<br>
                                                        {{ $temp->accountDeptDate ? date('d-m-Y h:i A', strtotime($temp->accountDeptDate)) : '-' }}
                                                    </td>
                                                    <td>
                                                        {!! getStatusIcon($temp->status) !!}
                                                    </td>
                                                    <td>{{ date('d-m-Y h:i A', strtotime($temp->updated_at)) }}<br>{{ $temp->updated_by }}</td>
                                                    <td>
                                                        <a href="/exitProces/view/{{$temp->id}}" class="btn btn-success btn-icon btn-sm">
                                                            <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true" data-toggle="tooltip" title="View Details"></i>
                                                        </a>
                                                        @if($userType == '51')
                                                            <a href="/hrPolicies/{{$temp->id}}/removeResignation" class="btn btn-danger btn-icon btn-sm">
                                                                <i class="fa fa-trash" style="font-size:20px;" aria-hidden="true" data-toggle="tooltip" title="Remove"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach                                      
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <a href="/exitProces/exportExcel/2" class="btn btn-success btn-icon btn-sm">
                                            <i class="fa fa-file-excel" style="font-size:20px;" aria-hidden="true" data-toggle="tooltip" title="Export to Excel"></i> Export
                                        </a>
                                    </div>
                                </div>
                            @else
                                <h4 style="color:red;">No records found.</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
