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
        switch ($value) {
            case 0:
                return '<span class="text-secondary"><i class="fas fa-hourglass-start text-muted"></i> <b>Pending</b></span>';
            case 1:
                return '<span class="text-warning"><i class="fas fa-spinner fa-spin text-warning"></i> <b>In Progress</b></span>';
            case 2:
                return '<span class="text-success"><i class="fas fa-check-circle text-success"></i> <b>Approved</b></span>';
            case 3:
                return '<span class="text-danger"><i class="fas fa-times-circle text-danger"></i> <b>Rejected</b></span>';
            default:
                return '<span class="text-info"><i class="fas fa-question-circle text-info"></i> <b>Unknown</b></span>';
        }
    }
@endphp

<style>
    .table-scroll-container {
        overflow-x: auto;
    }

    #hr-table th,
    #hr-table td {
        white-space: nowrap;
        border: 1px solid #dee2e6;
        background: #fff;
    }

    #hr-table th.sticky-col,
    #hr-table td.sticky-col {
        position: sticky;
        background: #fff;
        z-index: 3;
    }

    #hr-table th.sticky-col-1,
    #hr-table td.sticky-col-1 {
        left: 0;
        width: 50px;
        z-index: 5;
    }

    #hr-table th.sticky-col-2,
    #hr-table td.sticky-col-2 {
        left: 50px;
        width: 110px;
        z-index: 4;
    }

    #hr-table th.sticky-col-3,
    #hr-table td.sticky-col-3 {
        left: 150px;
        width: 170px;
        z-index: 3;
    }

    #hr-table td:hover {
        background-color: #f9f9f9;
    }
</style>

<div class="container">
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">NDC</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            @if($userType == '51')
                                <a href="/exitProces/apply" class="btn btn-primary mr-3">Raise Resignation</a>
                            @endif
                            <a href="/exitProces/standardProcess" class="btn btn-success mr-3">In-Progress List</a>
                            <a href="/exitProces/archieveStandardProcess" class="btn btn-primary mr-3">Archieve List</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">NDC List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($resins) && count($resins) >= 1)
                                @php $i = 1; @endphp
                                <div class="table-scroll-container">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                        <thead>
                                            <tr>
                                                <th class="sticky-col sticky-col-1 text-center">No</th>
                                                <th class="sticky-col sticky-col-2 text-center">Type</th>
                                                <th class="sticky-col sticky-col-3 text-center">Name</th>
                                                <th class="text-center">Reporting Authority</th>
                                                <th class="text-center">Store</th>
                                                <th class="text-center">IT</th>
                                                <th class="text-center">ERP</th>
                                                <th class="text-center">HR</th>
                                                <th class="text-center">High. Authority</th>
                                                <th class="text-center">Accounts</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resins as $temp)
                                                <tr>
                                                    <td class="sticky-col sticky-col-1">{{ $i++ }}</td>
                                                    <td class="sticky-col sticky-col-2">
                                                        {{ $temp->processType == 1 ? 'Standard' : ($temp->processType == 2 ? 'Absconding' : ($temp->processType == 3 ? 'Sabitical' : 'Termination')) }}
                                                        <br><small>{{ date('d-m-Y', strtotime($temp->created_at)) }}</small>
                                                    </td>
                                                    <td class="sticky-col sticky-col-3">
                                                        <a href="/exitProces/view/{{$temp->id}}">
                                                            <strong>{{ $temp->empCode }} - {{ $temp->name }}</strong><br>
                                                            <span style="color:{{ $temp->section == 'Teaching' ? 'red' : 'green' }}">{{ $temp->departmentName }}</span><br>
                                                            <small>{{ $temp->designationName }}</small><br>
                                                            <small>Raised: {{ date('d-m-Y', strtotime($temp->applyDate)) }}</small>
                                                        </a>
                                                    </td>

                                                    {{-- Approval Steps --}}
                                                    <td>{!! getStatusIcon($temp->reportingAuth) !!}
                                                        <br><strong>{{ $temp->reportingAuthorityName }}</strong>
                                                        <br>{{ $temp->reportingAuthDate ? date('d-m-Y h:i A', strtotime($temp->reportingAuthDate)) : '-' }}
                                                        @if($temp->reportingAuth != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->applyDate)->diffInDays($temp->reportingAuthDate) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->storeDept) !!}
                                                        <br>{{ $temp->storeDeptDate ? date('d-m-Y h:i A', strtotime($temp->storeDeptDate)) : '-' }}
                                                        @if($temp->storeDept != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->reportingAuthDate)->diffInDays($temp->storeDeptDate) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->itDept) !!}
                                                        <br>{{ $temp->itDeptDate ? date('d-m-Y h:i A', strtotime($temp->itDeptDate)) : '-' }}
                                                        @if($temp->itDept != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->storeDeptDate)->diffInDays($temp->itDeptDate) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->erpDept) !!}
                                                        <br>{{ $temp->erpDeptDate ? date('d-m-Y h:i A', strtotime($temp->erpDeptDate)) : '-' }}
                                                        @if($temp->erpDept != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->itDeptDate)->diffInDays($temp->erpDeptDate) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->hrDept) !!}
                                                        <br>{{ $temp->hrDeptDate ? date('d-m-Y h:i A', strtotime($temp->hrDeptDate)) : '-' }}
                                                        @if($temp->hrDept != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->erpDeptDate)->diffInDays($temp->hrDeptDate) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->finalPermission) !!}
                                                        <br>{{ ($temp->finalPermission == 2 || $temp->finalPermission == 3) ? date('d-m-Y h:i A', strtotime($temp->authorityUpdatedAt)) : '-' }}
                                                        @if($temp->finalPermission != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->hrDeptDate)->diffInDays($temp->authorityUpdatedAt) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->accountDept) !!}
                                                        <br>{{ $temp->accountDeptDate ? date('d-m-Y h:i A', strtotime($temp->accountDeptDate)) : '-' }}
                                                        @if($temp->accountDept != 0)
                                                            <br><small>Pending {{ \Carbon\Carbon::parse($temp->authorityUpdatedAt)->diffInDays($temp->accountDeptDate) }} Days</small>
                                                        @endif
                                                    </td>
                                                    <td>{!! getStatusIcon($temp->status) !!}</td>
                                                    <td>
                                                        <a href="/exitProces/view/{{$temp->id}}" class="btn btn-success btn-sm" title="View"><i class="fa fa-eye"></i></a>
                                                        @if($userType == '51')
                                                            <a href="/hrPolicies/{{$temp->id}}/removeResignation" class="btn btn-danger btn-sm" title="Remove"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <a href="/exitProces/exportExcel/1" class="btn btn-success btn-sm">
                                            <i class="fa fa-file-excel"></i> Export
                                        </a>
                                    </div>
                                </div>
                            @else
                                <h4 class="text-danger">No records found.</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div> <!-- End Row -->
        </div>
    </div> <!-- end app-content -->
</div>
@endsection
