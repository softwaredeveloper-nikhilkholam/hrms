<?php
use App\Helpers\Utility;
$util = new Utility();
$authorityStatus = Auth::user()->username;
$userType = Session()->get('userType');
$user = Auth::user();
?>
@extends('layouts.master')
@section('title', 'Non Teaching Employees')
@section('content')
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* Card and Table Styling */
    .employee-card {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        border: none;
    }

    .employee-card .card-header {
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .employee-card .card-title {
        font-weight: 600;
        color: #343a40;
        display: flex;
        align-items: center;
    }
    
    .page-title {
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .page-title i {
        margin-right: 10px;
        color: #007bff;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9 !important;
        transform: scale(1.01);
        transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .table thead th {
        background: green; /* As per your request */
        color: #ffffff;
        font-weight: 600;
        border-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table thead th i {
        margin-right: 8px;
        font-size: 0.9em;
    }

    .table td, .table th {
        vertical-align: middle;
        padding: 0.95rem;
    }

    /* Employee Name & Info Styling */
    .employee-info .employee-name {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0;
    }
    .employee-info .employee-username {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 2px solid #fff;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    
    /* Status Indicators */
    .status-badge {
        padding: 0.25em 0.6em;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-left {
        background-color: #f8d7da;
        color: #721c24;
    }
    .status-incomplete {
        color: #ffc107;
        font-size: 1.1rem;
        cursor: pointer;
    }

    /* Verify Icons */
    .verify-icon {
        font-size: 1.75rem;
    }
    .verify-success { color: #28a745; }
    .verify-fail { color: #dc3545; }

    /* Action Buttons */
    .action-btn {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .action-btn i {
        font-size: 1rem;
    }
    .btn-warning.action-btn { background-color: #ffc107; border-color: #ffc107; color: #212529; }
    .btn-success.action-btn { background-color: #28a745; border-color: #28a745; }
    .btn-warning.action-btn:hover { background-color: #e0a800; }
    .btn-success.action-btn:hover { background-color: #218838; }

</style>

<div class="container" style="max-width: 100% !important;">
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                     <h4 class="page-title"><i class="fas fa-users"></i>Left Employees</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card employee-card">
                        <div class="card-body">
                            @if(isset($employees) && count($employees) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-hover card-table table-vcenter text-nowrap mb-0" id="example">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-check-circle"></i>Last Date</th>
                                                <th><i class="fas fa-hashtag"></i>Emp Code</th>
                                                <th><i class="fas fa-user"></i>Employee Name</th>
                                                <th><i class="fas fa-user-tie"></i>Designation</th>
                                                <th><i class="fas fa-building"></i>Department</th>
                                                <th><i class="fas fa-store"></i>Branch</th>
                                                @if($userType == '61')
                                                    <th><i class="fas fa-rupee-sign"></i>Salary</th>
                                                @endif
                                                <th><i class="fas fa-sitemap"></i>Organisation</th>
                                                <th><i class="fas fa-clock"></i>Time In/Out</th>
                                                <th><i class="fas fa-phone"></i>Contact</th>
                                                @if($authorityStatus == 1)
                                                    <th><i class="fas fa-user-plus"></i>Added By</th>
                                                    <th><i class="fas fa-user-edit"></i>Updated By</th>
                                                @endif
                                                <th><i class="fas fa-cogs"></i>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employees as $employee)
                                                <tr>
                                                    <td class="text-center">
                                                        {{date('d-m-Y', strtotime($employee->lastDate))}}
                                                    </td>
                                                    <td><span class="font-weight-bold">{{$employee->empCode}}</span></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('admin/profilePhotos/' . ($employee->profilePhoto ?? 'default.png')) }}" alt="Avatar" class="employee-avatar">
                                                            <div class="employee-info">
                                                                <h6 class="employee-name">
                                                                    <a href="/employees/{{$employee->id}}">{{$employee->name}}</a>
                                                                </h6>
                                                                <p class="employee-username">{{$employee->username}}</p>
                                                            </div>
                                                            @if($employee->designationId == null || $employee->departmentId == null || $employee->PANNo == null || $employee->bankAccountNo == null || $employee->salaryScale == null)
                                                                &nbsp;<i class="fas fa-exclamation-circle status-incomplete" data-toggle="tooltip" title="Profile Incomplete"></i>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>{{$employee->designationName}}</td>
                                                    <td>{{$employee->departmentName}}</td>
                                                    <td>{{$employee->branchName}}</td>
                                                    @if($userType == '61')
                                                        <td>{{($employee->salaryScale == 0)?'₹0':'₹'.$util->numberFormat($employee->salaryScale)}}</td>
                                                    @endif
                                                    <td>{{$employee->organisation}}</td>
                                                    <td>{{date('h:i A', strtotime($employee->startTime))}} - {{date('h:i A', strtotime($employee->endTime))}}</td>
                                                    <td>{{$employee->phoneNo}}</td>
                                                    @if($authorityStatus == 1)
                                                        <td>{{$employee->added_by}}</td>
                                                        <td>{{$employee->updated_by}}</td>
                                                    @endif
                                                    <td>
                                                        <a href="/employees/{{$employee->id}}/edit" class="btn btn-warning action-btn" data-toggle="tooltip" title="Edit Employee">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        @if(in_array($userType, ['00', '51', '61', '401', '201', '501']))
                                                            <a href="/employees/downloadCif/{{$employee->id}}" target="_blank" class="btn btn-success action-btn" data-toggle="tooltip" title="Download CIF">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if(!isset($search))
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="/employees/exportempExcel/{{(isset($search))?$search:0}}/1/Non Teaching" class="btn btn-danger"><i class="fas fa-file-excel mr-2"></i>Download Excel</a>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle mr-2"></i> No records found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
