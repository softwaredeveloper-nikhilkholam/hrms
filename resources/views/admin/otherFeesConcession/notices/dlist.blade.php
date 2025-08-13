@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Board Notice</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Upcomming List</a>
                            <a href="/notices/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/notices/deletedList" class="btn btn-danger mr-3">Deleted List</a>
                            <a href="/notices/create" class="btn btn-primary mr-3">Add Board Notice</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Upcomming Board Notice</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($notices))
                                @if(count($notices) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="5%">#</th>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0">Title</th>
                                                    <th class="border-bottom-0" width="10%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($notices as $row)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{date('d-M-Y', strtotime($row->fromDate))}} To {{date('d-M-Y', strtotime($row->toDate))}}</td>
                                                        <td>{{$row->title}}</td>
                                                        <td>
                                                            <a href="/notices/{{$row->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="/notices/{{$row->id}}/edit" class="btn btn-warning btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/notices/{{$row->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Active Records.</h4>
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
