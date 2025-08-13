@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Concern Letter List</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employeeLetters/concernCreate" class="btn btn-success mr-3">Add Concern Letter</a>
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
                            <h4 class="card-title">Employee Concern Letter List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($letters))
                                @if(count($letters) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0"  width="10%">Employee Code</th>
                                                    <th class="border-bottom-0">Employee Name</th>
                                                    <th class="border-bottom-0"  width="20%">Letter</th>
                                                    <th class="border-bottom-0"  width="10%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($letters as $letter)
                                                    <tr>
                                                        <td>{{date('d-m-Y', strtotime($letter->created_at))}}</td>
                                                        <td>{{$letter->empCode}}</td>
                                                        <td>{{$letter->empName}}</td>
                                                        <td>Concern Letter</td>
                                                        <td>
                                                            <a href="/employeeLetters/concernView/{{$letter->id}}" class="btn btn-danger btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="View Details"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Letter Records.</h4>
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
