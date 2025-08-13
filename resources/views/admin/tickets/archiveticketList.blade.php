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
                            @if(Auth::user()->userType == '51' || Auth::user()->userType == '61')
                                <a href="/tickets/archiveTicketList" class="btn btn-success mr-3">Archive</a>
                                <a href="/tickets/allTickets" class="btn btn-primary mr-3">Tickets</a>
                            @else
                                <a href="/tickets/archiveTicketList" class="btn btn-success mr-3">Archive</a>
                                <a href="/tickets" class="btn btn-primary mr-3">Tickets</a>
                            @endif
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
                            <h4 class="card-title">Tickets</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($tickets))
                                @if(count($tickets) >= 1)
                                    <div class="table-responsive">
                                        <table id="example" class="table  table-vcenter text-nowrap table-bordered border-top border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-5">Ticket No</th>
                                                    <th class="border-bottom-0 w-10">Emp Code</th>
                                                    <th class="border-bottom-0 w-20">Emp Name</th>
                                                    <th class="border-bottom-0 w-10">Designation</th>
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
                                                        <td>{{$ticket->issue}}</td>
                                                        <td>
                                                            @if($ticket->status == 1)
                                                                <b style="color:purple;">Pending</b>
                                                            @elseif($ticket->status == 2)
                                                                <b style="color:green;">Closed</b>
                                                            @else
                                                                <b style="color:red;">Rejected</b>
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
                                        <div class="col-md-4"><a href="/tickets/exportExcel/{{$year}}/2" class="btn btn-primary">Export</a></div>
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
