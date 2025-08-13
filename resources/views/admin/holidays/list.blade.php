@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Holiday</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Upcomming List</a>
                            <a href="/holidays/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/holidays/create" class="btn btn-primary mr-3">Add Holiday</a>
                            <a href="/holidays/uploadList" class="btn btn-danger mr-3">Upload Holiday List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Upcomming Holiday</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($holidays))
                                @if(count($holidays) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-10">Date</th>
                                                    <th class="border-bottom-0">Holiday Name</th>
                                                    <th class="border-bottom-0 w-10">Branch Count</th>
                                                    <th class="border-bottom-0 w-10">Department Count</th>
                                                    <th class="border-bottom-0 w-10">Designation Count</th>
                                                    <th class="border-bottom-0 w-10">Holiday Type</th>
                                                    <th class="border-bottom-0 w-10">Updated</th>
                                                    <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($holidays as $holiday)
                                                    <tr>
                                                        <th>{{$i++}}</th>
                                                        <td>{{date('d-M-Y', strtotime($holiday->forDate))}}</td>
                                                        <td>{{$holiday->name}}</td>
                                                        <td>{{$holiday->branchCount}}</td>
                                                        <td>{{$holiday->departmentCount}}</td>
                                                        <td>{{$holiday->designationCount}}</td>
                                                        <td>{{($holiday->holidayType == '1')?'100% Paid':(($holiday->holidayType == '2')?'50% Paid':'Without Paid')}}</td>
                                                        <td>{{$holiday->updated_by}}<br>{{date('d-M-Y H:i', strtotime($holiday->created_at))}}</td>
                                                        <td>
                                                            @if($holiday->status != 3)
                                                                <a href="/holidays/{{$holiday->id}}/edit" class="btn btn-warning btn-icon btn-sm">
                                                                    <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                </a>
                                                            @endif
                                                            <a href="/holidays/{{$holiday->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"></i>
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
