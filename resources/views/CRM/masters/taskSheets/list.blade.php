
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
                <h5 class="card-title">Active Task List</h5>
            </div>
            <div class="col-md-8 text-left" style="text-align: right;">
                <a href="/CRM/masterchecklist/create" class="btn btn-primary text-right">Add</a>
                <a href="/CRM/masterchecklist/dlist" class="btn btn-primary text-right">Deactive List</a>
                <a href="/CRM/masterchecklist" class="btn btn-success text-right">Active List</a>
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
                        <th class="no-sort text-center">No.</th>
                        <th class="text-center">Branch</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Designation</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Task</th>
                        <th class="text-center">Created</th>
                        <th class="text-end text-center">Action<?php $i=1; ?></th>
                    </tr>
                </thead>
                <tbody>
                    @if($taskMasters)
                        @foreach($taskMasters as $row)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$row->branchName}}</td>
                                <td>{{$row->departmentName}}</td>
                                <td>{{$row->designationName}}</td>
                                <td>#</td>
                                <td>{{$row->task}}</td>
                                <td>{{date('d-m-Y H:i', strtotime($row->created_at))}}<br>
                                    {{$row->updated_by}}
                                </td>
                                <td><a href="/CRM/masterchecklist/activeDeactiveStatus/{{$row->id}}" class="badge badge-pill badge-status bg-danger">X</a></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
