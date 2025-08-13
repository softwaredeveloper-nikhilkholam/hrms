@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Forms & Circular</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Current List</a>
                            <a href="/formsCirculars/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/formsCirculars/create" class="btn btn-primary mr-3">Add Forms & Circular</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Current Forms & Circular</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($formCirculars))
                                @if(count($formCirculars) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top  border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-20">Circular No</th>
                                                    <th class="border-bottom-0">Circular Name</th>
                                                    <th class="border-bottom-0">Circular For</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($formCirculars as $circular)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td><a href="/formsCirculars/{{$circular->id}}"><b style="color:red;">{{$circular->circularNo}}</b></a></td>
                                                        <td>{{$circular->name}}</td>
                                                        <td>{{$circular->status}}</td>
                                                        <td>
                                                            <a href="/formsCirculars/{{$circular->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/formsCirculars/{{$circular->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
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
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
