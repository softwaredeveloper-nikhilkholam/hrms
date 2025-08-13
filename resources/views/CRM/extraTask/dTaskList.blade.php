<?php
    $userRole = Session()->get('userRole');
?>
@extends('CRM.layouts.master')
@section('title', 'CRM')
@section('content') 
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4 text-left">
                <h5 class="card-title">Deactive Task List</h5>
            </div>
            <div class="col-md-8 text-left" style="text-align: right;">
                <a href="/CRM/extraTask/raiseTask" class="btn btn-primary text-right">Raised Task</a>
                <a href="/CRM/extraTask/dTaskList" class="btn btn-primary text-right">Deactive List</a>
                <a href="/CRM/extraTask/requestList" class="btn btn-success text-right">Active List</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive custom-table">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="datatable-length"></div>
                </div>
                <div class="col-md-6">
                    <div class="datatable-paginate"></div>
                </div>
		    </div>
            <table class="table  mt-4">
                <thead class="thead-light">
                    <tr> 
                        <th class="no-sort text-center">Sr No.</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Title/Subject</th>
                        <th class="text-center">Raised By</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Employee</th>
                        <th class="text-end text-center">Action<?php $i=1; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>26-07-2025 calls</td>
                        <td>Interview Calls</td>
                        <td>Shobhan</td>
                        <td>IVR</td>
                        <td>Pooja</td>
                        <td><a href="/CRM/masterchecklist/activeDeactiveStatus" class="badge badge-pill badge-status bg-danger">View</a>
                        <a href="/CRM/masterchecklist/activeDeactiveStatus" class="badge badge-pill badge-status bg-danger">Active</a></td>
                    </tr>
                           
                    
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection