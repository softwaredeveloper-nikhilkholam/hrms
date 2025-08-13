@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container"> 
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Contract Report</h4>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Employee List&nbsp;&nbsp;<b style="color:Red;">{{count($employees)}}</b></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-body">
                                    @if(isset($employees))
                                        @if(count($employees))
                                            <div class="table-responsive">
                                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Emp Code</th>
                                                            <th>Name</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th class="w-7">Contract Start</th>
                                                            <th class="w-7">Contract End</th>
                                                            <th class="w-10">Action<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($employees as $row)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$row->empCode}}</td>    
                                                                <td>{{$row->name}}</td>    
                                                                <td>{{$row->departmentName}}</td>    
                                                                <td>{{$row->designationName}}</td>   
                                                                <td>{{($row->contractStartDate == '' || $row->contractStartDate == '0')?'-':date('d-m-Y', strtotime($row->contractStartDate))}}</td>  
                                                                <td>{{($row->contractEndDate == '' || $row->contractEndDate == '0')?'-':date('d-m-Y', strtotime($row->contractEndDate))}}</td>  
                                                                <td><a href="/employees/{{$row->id}}/edit" class="btn btn-warning">Edit</a></td>       
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row" style="margin-top:15px;">
                                                <div class='col-md-8'></div>
                                                <div class='col-md-4'>
                                                    <a href="/reports/getContractReportExcel" class="btn btn-primary"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel <i class="fa fa-download" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                        </div>   
                    </div>   
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
