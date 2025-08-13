<?php
    use App\Helpers\Utility;
    $util=new Utility(); 
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Appraisal</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">List</a>
                            <a href="/apprisal/create" class="btn btn-primary mr-3">Get Appraisal</a>
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
                            <h4 class="card-title">Appraisal List</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\AppraisalsController@index', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Apprisal Year<span class="text-red" style="font-size:22px;">*</span> :</label>
                                            {{Form::select('year', ['2022'=>'2022','2023'=>'2023','2024'=>'2024','2025'=>'2025','2026'=>'2026'],((isset($year))?$year : null), ['placeholder'=>'Select Year','class'=>'form-control', 'id'=>'year', 'required'])}}
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button class="btn btn-danger mt-6" type="submit">Search</button>
                                        </div>
                                    </div> 
                                </div>
                            {!! Form::close() !!} 
                            @if(isset($lists))
                                @if(count($lists))
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="10%">No.</th>
                                                    <th class="border-bottom-0" width="10%">Code</th>
                                                    <th class="border-bottom-0">Name</th>
                                                    <th class="border-bottom-0" width="10%">Designation</th>
                                                    <th class="border-bottom-0" width="10%">Joining Date</th>
                                                    <th class="border-bottom-0" width="10%">Last Salary</th>
                                                    <th class="border-bottom-0" width="10%">Hike Salary</th>
                                                    <th class="border-bottom-0" width="10%">Hike %</th>
                                                    <th class="border-bottom-0" width="10%">Apprisal Salary</th>
                                                    <th class="border-bottom-0" width="10%">Effective From</th>
                                                    <th class="border-bottom-0" width="10%">Updated At</th>
                                                    <th class="border-bottom-0" width="10%">Updated By</th>
                                                    <th class="border-bottom-0" width="5%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lists as $appoint)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$appoint->empCode}}</td>
                                                        <td>{{$appoint->name}}</td>
                                                        <td>{{$appoint->designationName}}</td>
                                                        <td>{{$appoint->jobJoingDate}}</td>
                                                        <td>{{$appoint->oldSalary}}</td>
                                                        <td>{{$appoint->hikeRs}}</td>
                                                        <td>{{$appoint->percentage}}</td>
                                                        <td>{{$appoint->finalRs}}</td>
                                                        <td>{{date('m-Y', strtotime($appoint->month))}}</td>
                                                        <td>{{date('d-m-Y H:i A', strtotime($appoint->updated_at))}}</td>
                                                        <td>{{$appoint->updated_by}}</td>
                                                        <td>
                                                            <a href="/apprisal/{{$appoint->id}}" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="View more"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-right">
                                            <a href="{{ url('apprisal/exportExcel/' . ($year ?? '')) }}" 
                                            class="btn btn-danger btn-icon" 
                                            data-toggle="tooltip" 
                                            data-original-title="Export to Excel">
                                                Export <i class="fa fa-file-excel-o" style="font-size:20px;"></i>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <h4 style="color:red;">Records not found.</h4>
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
