<?php
$authorityStatus = Auth::user()->username;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Deactive List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/developer/create" class="btn btn-primary mr-3">Add</a>
                            <a href="/developer/list" class="btn btn-primary mr-3">Active List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <div class='col-md-7'><h4 class="card-title">Developer Deactive List</h4></div>
                        </div>
                        <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="example">
											<thead class="text-white" style="background-color:seagreen;">
                                                <tr>
                                                    <th class="text-white"  width="5%">Sr. No.</th>
                                                    <th class="text-white">Name Of Employee </th>
                                                    <th class="text-white">Skills</th>
                                                    <th class="text-white">Rating</th>
                                                    <th class="text-white">Other Details</th>
                                                    <th class="text-white" width="10%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Pooja</td>
                                                        <td>Test</td>
                                                        <td>Test</td>
                                                        <td>Test</td>
                                                        <td>
                                                            <a href="/developer/create" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="#" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>
                                                            <a href="/developer/show" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 style="color:red;">Record not found...</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
  

