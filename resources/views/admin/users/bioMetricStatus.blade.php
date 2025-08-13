@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Bio Metric Machine Status</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">Bio Metric Machine Status [Online / Offline]</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'HomeController@getBioMetricStatus', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="date" name="forDate" value="{{$forDate}}" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::select('machineId', $machines, $machineId, ['class'=>'form-control', 'placeholder'=>'Select a Machine'])}}
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>
                            @if(isset($bioMetricStatus))
                                <div class="table-responsive">
                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0" id="example">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-white border-bottom-0 w-5 text-center">NO</th>
                                                <th class="text-white border-bottom-0">Machine Name</th>
                                                <th class="text-white border-bottom-0">Data Fetching Time<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bioMetricStatus as $machine)
                                                <tr>
                                                    <td class="text-center">{{$i++}}</td>
                                                    <td>{{$machine->machineName}} [{{$machine->serialNo}}]</td>
                                                    <td>{{date('d-M-Y H:i A', strtotime($machine->created_at))}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
