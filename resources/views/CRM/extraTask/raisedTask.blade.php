
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
                <h5 class="card-title">Add New Task</h5>
            </div>
            <div class="col-md-8 text-left" style="text-align: right;">
                <a href="/CRM/extraTask/requestList" class="btn btn-primary text-right">Request List</a>
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
                        <th class="no-sort text-center">Assign Date</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Prority</th>
                        <th class="text-center">Deadline Date</th>
                        <th class="text-center">Task Discription</th>
                        <th class="text-center">Attachment</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-end text-center">Action<?php $i=1; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>31-07-2025</td>
                        <td>HR</td>
                        <td>Shobhan</td>
                        <td>Urgent</td>
                        <td>01-08-2025</td>
                        <td>Attendance Report</td>
                        <td>Blank</td>
                        <td>Blank</td>
                        <td><a href="/CRM/extraTask/assignedTask" class="badge badge-pill badge-status bg-danger">Assigned</a>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>31-07-2025</td>
                        <td>IVR</td>
                        <td>Pooja</td>
                        <td>Urgent</td>
                        <td>01-08-2025</td>
                        <td>IVR Monthly Report</td>
                        <td>Blank</td>
                        <td>Blank</td>
                        <td><a href="/CRM/extraTask/assignedTask" class="badge badge-pill badge-status bg-danger">Assigned</a>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>01-08-2025</td>
                        <td>ERP</td>
                        <td>Kartik</td>
                        <td>Urgent</td>
                        <td>02-08-2025</td>
                        <td>Transport Muster Printing</td>
                        <td>Blank</td>
                        <td>Blank</td>
                        <td><a href="/CRM/extraTask/assignedTask" class="badge badge-pill badge-status bg-danger">Assigned</a>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>01-08-2025</td>
                        <td>Design</td>
                        <td>Kartik</td>
                        <td>Urgent</td>
                        <td>02-08-2025</td>
                        <td>Quetion Paper Designing </td>
                        <td>Blank</td>
                        <td>Blank</td>
                        <td><a href="/CRM/extraTask/assignedTask" class="badge badge-pill badge-status bg-danger">Assigned</a>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>01-08-2025</td>
                        <td>Social Media</td>
                        <td>Kartik</td>
                        <td>Urgent</td>
                        <td>02-08-2025</td>
                        <td>Interview Drive Pramotion </td>
                        <td>Blank</td>
                        <td>Blank</td>
                        <td><a href="/CRM/extraTask/assignedTask" class="badge badge-pill badge-status bg-danger">Assigned</a>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
