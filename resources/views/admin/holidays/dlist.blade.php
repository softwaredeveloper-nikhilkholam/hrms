@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Holiday</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/holidays" class="btn btn-primary mr-3">Upcomming List</a>
                            <a href="#" class="btn btn-success mr-3">Archive List</a>
                            <a href="/holidays/create" class="btn btn-primary mr-3">Add Holiday</a>
                            
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
                            <h4 class="card-title">Archive Holiday</h4>
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
                                                    <th class="border-bottom-0">Holiday Name<?php $i=1; ?></th>
                                                    <th class="border-bottom-0 w-10">Updated By</th>
                                                    <th class="border-bottom-0 w-10">Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($holidays as $holiday)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{date('d-M-Y', strtotime($holiday->forDate))}}</td>
                                                        <td>{{$holiday->name}}</td>
                                                        <td>{{$holiday->updated_by}}</td>
                                                        <td>{{date('d-M-Y H:i', strtotime($holiday->created_at))}}</td>
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
