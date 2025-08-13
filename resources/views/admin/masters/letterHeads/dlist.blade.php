@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">LetterHead File</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/letterHeads" class="btn btn-primary mr-3">Active List</a>
                            <a href="#" class="btn btn-success mr-3">Deactive List</a>
                            <a href="/letterHeads/create" class="btn btn-primary mr-3">Add</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Active LetterHead</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($letterHeads))
                                @if(count($letterHeads) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-15">Name</th>
                                                    <th class="border-bottom-0">Letter Head</th>
                                                    <th class="border-bottom-0 w-5">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($letterHeads as $letter)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$letter->name}}</td>
                                                        <td><img src="/admin/letterHeads/{{$letter->fileName}}" height="200px" width="500px"></td>
                                                        <td>
                                                            <a href="/letterHeads/{{$letter->id}}/activate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Activate"></i>
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
