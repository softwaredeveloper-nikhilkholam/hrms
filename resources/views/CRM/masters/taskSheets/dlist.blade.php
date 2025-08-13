
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
                <a href="/CRM/masterchecklist/create" class="btn btn-primary text-right">Add</a>
                <a href="/CRM/masterchecklist/dlist" class="btn btn-success text-right">Deactive List</a>
                <a href="/CRM/masterchecklist" class="btn btn-primary text-right">Active List</a>
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
            @if(count($taskMasters) > 0)
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
                            <th class="text-center">Action<?php $i=1; ?></th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <td><a href="/CRM/masterchecklist/activeDeactiveStatus/{{$row->id}}" class="badge badge-pill badge-status bg-success">✓</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h5 style="color:red;">Data not Found...</h5>
            @endif
        </div>
    </div>
</div>
@endsection
