@extends('layouts.master')
@section('title', 'AWs')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Designations</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/designations" class="btn btn-primary mr-3">Active List</a>
                            <a href="#" class="btn btn-success mr-3">Deactive List</a>
                            <a href="/designations/create" class="btn btn-primary mr-3">Add</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-1 col-md-1 col-lg-1"></div>
                <div class="col-xl-10 col-md-10 col-lg-10">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Deactive Designations</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($designations))
                                @if(count($designations) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0">Department</th>
                                                    <th class="border-bottom-0">Designation Name</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($designations as $designation)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$designation->departmentName}}</td>
                                                        <td>{{$designation->name}}</td>
                                                        <td>
                                                            <a href="/designations/{{$designation->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/designations/{{$designation->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-check-circle-o" aria-hidden="true" style="font-size:20px;"  data-toggle="tooltip" data-original-title="Activate"></i>
                                                            </a>
                                                            <a href="/designations/{{$designation->id}}"<i class="fa fa-eye" data-designation="tooltip" style="font-size:20px;"  data-original-title="show"></i>
                                                                <i class="fa fa-eye" data-designation="tooltip" style="font-size:20px;"  data-original-title="show"></i>
                                                            </a>
                                                        </td>   
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                        <a href="/designations/0/excel" class="btn btn-primary mr-3">Export</a>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Deactive Records.</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-1 col-md-1 col-lg-1"></div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
