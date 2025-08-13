@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Deactive List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/notices/create" class="btn btn-primary mr-3">Add</a>
                            <a href="/notices" class="btn btn-primary mr-3">Active List</a>
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
                            <h4 class="card-title">Deactive Notices</h4>
                        </div>
                        <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">Sr No</th>
                                                    <th class="border-bottom-0 w-5">Branch</th>
                                                    <th class="border-bottom-0">Department</th>
                                                    <th class="border-bottom-0">Designation Name</th>
                                                    <th class="border-bottom-0">section</th>
                                                    <th class="border-bottom-0">Title</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Ascon</td>
                                                    <td>IVR</td>
                                                    <td>IVR Executive</td>
                                                    <td>Non Teaching</td>
                                                    <td>Changes to the IVR System Menu Options</td>
                                                    <td>
                                                        <a href="#" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-check-circle-o" aria-hidden="true" style="font-size:20px;"  data-toggle="tooltip" data-original-title="Activate"></i>
                                                            </a>
                                                        <a href="/notices/1" class="btn btn-success btn-icon btn-sm">
                                                            <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                                                      
                                            </tbody>
                                                                                      
                                            </tbody>
                                        </table>
                                        <a href="/designations/1/excel" class="btn btn-primary mr-3">Export</a>
                                    </div>
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
