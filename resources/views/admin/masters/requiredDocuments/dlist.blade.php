@extends('layouts.master')
@section('title', 'AWs')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Required Documents</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/requiredDocuments" class="btn btn-primary mr-3">Active List</a>
                            <a href="#" class="btn btn-success mr-3">Deactive List</a>
                            <a href="/requiredDocuments/create" class="btn btn-primary mr-3">Add Required Document</a>
                            
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
                            <h4 class="card-title">Deactive Required Documents</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($requiredDocuments))
                                @if(count($requiredDocuments) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0">Document Name</th>
                                                    <th class="border-bottom-0">Remarks</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($requiredDocuments as $requiredDocument)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$requiredDocument->name}}</td>
                                                        <td>{{$requiredDocument->remarks}}</td>
                                                        <td>
                                                            <a href="/requiredDocuments/{{$requiredDocument->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/requiredDocuments/{{$requiredDocument->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-check-circle-o" aria-hidden="true" style="font-size:20px;"  data-toggle="tooltip" data-original-title="Activate"></i>
                                                            </a>
                                                            <a href="/requiredDocuments/{{$requiredDocument->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" data-toggle="tooltip" style="font-size:20px;"  data-original-title="show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Deactive Records.</h4>
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
