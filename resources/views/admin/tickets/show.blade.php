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
                            @if(Auth::user()->userType != '51')
                                <a href="/tickets/list" class="btn btn-primary mr-3">Ticket List</a>
                                <a href="/tickets" class="btn btn-success mr-3">Raise Ticket</a>
                            @else
                                <a href="/tickets/archiveTicketList" class="btn btn-primary mr-3">Archive</a>
                                <a href="/tickets/allTickets" class="btn btn-success mr-3">Tickets</a>
                            @endif
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
                            <h4 class="card-title">Raised Ticket Details
                                [
                            @if($ticket->status == 1)
                                <b style="color:purple;">Pending</b>
                            @elseif($ticket->status == 2)
                                <b style="color:green;">Closed</b>
                            @elseif($ticket->status == 4)
                                <b style="color:orange;">On Hold</b>
                            @else
                                <b style="color:red;">Rejected</b>
                            @endif
                            ]
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Issue Type<span class="text-red">*</span>:</label>
                                        @if($ticket->issueType == 1)
                                            <input type="text" class="form-control" name="forDate" placeholder="Date" value="Salary issue" disabled>
                                        @elseif($ticket->issueType == 2)
                                            <input type="text" class="form-control" name="forDate" placeholder="Date" value="Changes to be done at HR END" disabled>
                                        @elseif($ticket->issueType == 3)
                                            <input type="text" class="form-control" name="forDate" placeholder="Date" value="REQUESTES" disabled>
                                        @else
                                            <input type="text" class="form-control" name="forDate" placeholder="Date" value="EXIT FORMALITIES INITATION" disabled>
                                        @endif
                                    </div>
                                </div>   
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Issue<span class="text-red">*</span>:</label>
                                        <input type="text" class="form-control" name="forDate" placeholder="Date" value="{{$ticket->issue}}" disabled>
                                    </div>
                                </div>                                  
                            </div>   
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Note<span class="text-red">*</span>:</label>
                                        <textarea disabled class="form-control" name="note" placeholder="Note" required>{{$ticket->note}}</textarea>
                                    </div>
                                </div>                               
                            </div>   
                            <div class="row">
                                @if($ticket->status == 2)
                                    <div class="col-md-4 mt-2">
                                        <a href="/tickets/downloadSalaryCertificate/{{$ticket->id}}/{{$ticket->fromMonth}}/{{$ticket->toMonth}}" download style="color:red;"><i class="fa fa-download"></i>&nbsp;&nbsp;Download Certificate</a>
                                    </div>   
                                @endif
                                
                                @if($ticket->issueType == 1)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">For Month<span class="text-red">*</span>:</label>
                                            <input type="month" class="form-control" name="fromMonth" placeholder="Date" value="{{date('Y-m', strtotime($ticket->fromMonth))}}" disabled>
                                        </div>
                                    </div> 
                                    @if($ticket->status == 1)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Arriers Days<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" name="arriersDays" placeholder="Arriers Days" value="{{$ticket->arriersDays}}" disabled>
                                            </div>
                                        </div> 
                                    @endif
                                @endif

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Updated At<span class="text-red">*</span>:</label>
                                        <input type="text" class="form-control" name="forDate" placeholder="Date" value="{{date('d-M-Y H:i A', strtotime($ticket->updated_at))}}" disabled>
                                    </div>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Updated By<span class="text-red">*</span>:</label>
                                        <input type="text" class="form-control" name="forDate" placeholder="Date" value="{{$ticket->updated_by}}" disabled>
                                    </div>
                                </div>                                  
                            </div>  
                            @if($ticket->issue == 'FORM 16' || $ticket->issue == 'SALARY CERTIFICATE')       
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">Account Remark<span class="text-red">*</span>:</label>
                                            <textarea class="form-control" name="remark" placeholder="Remark" disabled>{{$ticket->remark}}</textarea>
                                        </div>
                                    </div>     
                                    <div class="col-md-4" style="margin-top:35px;">
                                        <b><a href="/admin/requestedDocs/{{$ticket->fileName}}" style="color:red;" download>Download {{$ticket->issue}}</a></b>
                                    </div>                            
                                </div>   
                            @else
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">HR Remark<span class="text-red">*</span>:</label>
                                            <textarea class="form-control" name="remark" placeholder="Remark" disabled>{{$ticket->remark}}</textarea>
                                        </div>
                                    </div>     
                                </div>   
                            @endif                          
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
