@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Tickets</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/tickets/archiveTicketList" class="btn btn-primary mr-3">Archive</a>
                            <a href="/tickets/allTickets" class="btn btn-success mr-3">Tickets</a>
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
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\TicketsController@allTickets', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-xl-3 col-md-3 col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Year<span class="text-red">*</span>:</label>
                                            {{Form::select('forYear', ['2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022','2023'=>'2023','2024'=>'2024','2025'=>'2025'], $year, ['placeholder'=>'Select Year','class'=>'form-control', 'id'=>'forYear', 'required'])}}
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="section">Issue Type</label>
                                            <select class="form-control" id="issueType" name="issueType">
                                                <option selected disabled value="">Select Issue..</option>
                                                <option value="1" {{ ($issueType == '1')?'selected':''}}>Salary issue</option>
                                                <option value="2" {{ ($issueType == '2')?'selected':''}}>Changes to be done at HR END</option>
                                                <option value="3" {{ ($issueType == '3')?'selected':''}}>Requests</option>
                                            </select>
                                        </div>
                                    </div>      
                                    <div class="col-xl-4 col-md-4 col-lg-4 mt-5">
                                        <div class="form-group">
                                            <input type="submit" name="Search" class="btn btn-primary" value="Search">        
                                        </div>
                                    </div>                            
                                </div>      
                            {!! Form::close() !!}
                            @if(isset($tickets))
                                @if(count($tickets) >= 1)
                                    <div class="table-responsive">
                                        <table id="example" class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-5">Ticket No</th>
                                                    <th class="border-bottom-0 w-10">Emp Code</th>
                                                    <th class="border-bottom-0 w-20">Emp Name</th>
                                                    <th class="border-bottom-0 w-10">Designation</th>
                                                    <th class="border-bottom-0 w-10">Branch</th>
                                                    <th class="border-bottom-0 w-15">Issue Type</th>
                                                    <th class="border-bottom-0 w-10">Issue</th>
                                                    <th class="border-bottom-0 w-10">Status</th>
                                                    <th class="border-bottom-0 w-10">Updated At</th>
                                                    <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tickets as $ticket)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>#</td>
                                                        <td>{{$ticket->empCode}}</td>
                                                        <td>{{$ticket->name}}</td>
                                                        <td>{{$ticket->designationName}}</td>
                                                        <td>{{$ticket->branchName}}</td>
                                                        <td>
                                                            @if($ticket->issueType == 1)
                                                                Salary issue
                                                            @elseif($ticket->issueType == 2)
                                                                Changes to be done at HR END
                                                            @elseif($ticket->issueType == 3)
                                                                REQUESTES
                                                            @else
                                                                EXIT FORMALITIES INITATION
                                                            @endif
                                                        </td>
                                                        <td>{{$ticket->issue}} {{($ticket->issueType == '3')?$ticket->period:''}}</td>
                                                        <td>
                                                                 <!---  1-pending, 2-closed, 3-rejected, 4-inprogress, 5-Hold -->
                                                            @if($ticket->status == 1)
                                                                <b style="color:purple;">Pending</b>
                                                            @elseif($ticket->status == 2)
                                                                <b style="color:green;">Closed</b>
                                                            @elseif($ticket->status == 3)
                                                                <b style="color:red;">Rejected</b>
                                                            @elseif($ticket->status == 4)
                                                                <b style="color:orange;">In Progress</b>
                                                            @else
                                                                <b style="color:orange;">Hold</b>
                                                            @endif
                                                        </td>
                                                        <td>{{date('d-M-Y H:i A', strtotime($ticket->updated_at))}}</td>
                                                        <td>
                                                            <a href="/tickets/{{$ticket->id}}/changeStatus" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row" style="margin-top:15px;">
                                        <div class="col-md-4"><a href="/tickets/exportExcel/{{$year}}/1" class="btn btn-primary">Export</a></div>
                                        <div class='col-md-8'></div>
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
