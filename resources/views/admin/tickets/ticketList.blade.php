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
                            <a href="/tickets/list" class="btn btn-success mr-3">Ticket List</a>
                            <a href="/tickets" class="btn btn-primary mr-3">Raise Ticket</a>
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
                            <h4 class="card-title">Raise Ticket</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($tickets))
                                @if(count($tickets) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-10">#</th>
                                                    <th class="border-bottom-0 w-25">Issue Type</th>
                                                    <th class="border-bottom-0 w-20">Issue</th>
                                                    <th class="border-bottom-0 w-10">Status</th>
                                                    <th class="border-bottom-0 w-10">Updated At</th>
                                                    <th class="border-bottom-0 w-10">Updated By</th>
                                                    <th class="border-bottom-0 w-15">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tickets as $ticket)
                                                    <tr>
                                                        <td>{{$i++}}</td>
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
                                                            @elseif($ticket->status == 4)
                                                                <b style="color:orange;">On Hold</b>
                                                            @else
                                                                <b style="color:red;">Rejected</b>
                                                            @endif
                                                        </td>
                                                        <td>{{date('d-M-Y H:i A', strtotime($ticket->updated_at))}}</td>
                                                        <td>{{$ticket->updated_by}}</td>
                                                        <td>
                                                            <a href="/tickets/{{$ticket->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                            </a>
                                                             <a href="/tickets/deactivate/{{$ticket->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-trash" style="font-size:20px;" data-toggle="tooltip" data-original-title="delete"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>{{$tickets->links()}}</div>
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
