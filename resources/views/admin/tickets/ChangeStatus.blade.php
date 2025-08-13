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
                            <h4 class="card-title">Change Ticket
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
                        <style>
                            .info-group {
                                display: flex;
                                align-items: center;
                                margin-bottom: 1rem;
                                padding: 0.75rem;
                                background-color: #f9f9f9;
                                border-left: 4px solid #467fcf;
                                border-radius: 5px;
                            }
                            .info-icon {
                                font-size: 1.5rem;
                                color: #467fcf;
                                margin-right: 1rem;
                            }
                            .info-label {
                                font-weight: bold;
                                margin-bottom: 0.25rem;
                            }
                        </style>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-group">
                                        <i class="fa fa-id-card info-icon"></i>
                                        <div>
                                            <div class="info-label">Emp Code</div>
                                            <div>{{ $ticket->empCode }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-group">
                                        <i class="fa fa-user info-icon"></i>
                                        <div>
                                            <div class="info-label">Emp Name</div>
                                            <div>{{ $ticket->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-group">
                                        <i class="fa fa-briefcase info-icon"></i>
                                        <div>
                                            <div class="info-label">Designation</div>
                                            <div>{{ $ticket->designationName }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-group">
                                        <i class="fa fa-code-branch info-icon"></i>
                                        <div>
                                            <div class="info-label">Branch</div>
                                            <div>{{ $ticket->branchName }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-group">
                                        <i class="fa fa-question-circle info-icon"></i>
                                        <div>
                                            <div class="info-label">Issue Type</div>
                                            <div>
                                                @switch($ticket->issueType)
                                                    @case(1) Salary issue @break
                                                    @case(2) Changes to be done at HR END @break
                                                    @case(3) REQUESTS @break
                                                    @default EXIT FORMALITIES INITIATION
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-group">
                                        <i class="fa fa-exclamation-circle info-icon"></i>
                                        <div>
                                            <div class="info-label">Issue</div>
                                            <div>{{ $ticket->issue }} {{ $ticket->period }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if($ticket->issueType == 1)
                                    <div class="col-md-4">
                                        <div class="info-group">
                                            <i class="fa fa-calendar-alt info-icon"></i>
                                            <div>
                                                <div class="info-label">Month</div>
                                                <div>{{ date('M-Y', strtotime($ticket->fromMonth)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($ticket->issueType == 3)
                                    <div class="col-md-2">
                                        <div class="info-group">
                                            <i class="fa fa-calendar-alt info-icon"></i>
                                            <div>
                                                <div class="info-label">From Month</div>
                                                <div>{{ $fromMonth }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-group">
                                            <i class="fa fa-calendar-check info-icon"></i>
                                            <div>
                                                <div class="info-label">To Month</div>
                                                <div>{{ $toMonth }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-group">
                                        <i class="fa fa-sticky-note info-icon"></i>
                                        <div>
                                            <div class="info-label">Employee Remark / Note</div>
                                            <div>{{ $ticket->note }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-group">
                                        <i class="fa fa-clock info-icon"></i>
                                        <div>
                                            <div class="info-label">Updated At</div>
                                            <div>{{ date('d-M-Y H:i A', strtotime($ticket->updated_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-group">
                                        <i class="fa fa-user-edit info-icon"></i>
                                        <div>
                                            <div class="info-label">Updated By</div>
                                            <div>{{ $ticket->updated_by }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if($ticket->issueType == 3)
                                    <div class="col-md-4">
                                        <div class="download-section">
                                            <h5><i class="fa fa-download text-success me-2"></i>Download Salary Certificate</h5>
                                            @if($ticket->status == 2)
                                                <a href="/tickets/downloadSalaryCertificate/{{$ticket->id}}/{{$ticket->fromMonth}}/{{$ticket->toMonth}}" download class="btn btn-primary">
                                                    <i class="fa fa-download me-2"></i> Download
                                                </a>
                                            @else
                                                <div class="alert alert-danger" role="alert">
                                                    <strong>You can download after the ticket is closed.</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>                        
                            @if($ticket->status == 1 || $ticket->status == 4)
                                <hr>
                                {!! Form::open(['action' => 'admin\TicketsController@updateStatus', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                                
                                @if($ticket->issueType == 3)
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label"><i class="fa fa-file-alt text-primary"></i> Select Letterhead <span class="text-red">*</span></label>
                                            {{ Form::select('letterHeadId', $letterHeads, $ticket->letterHeadId ?? '', ['placeholder' => 'Select Letterhead', 'class' => 'form-control']) }}
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    @if($ticket->issueType == 1)
                                        <div class="col-md-4">
                                            <label class="form-label">Arrears Days <span class="text-red">*</span></label>
                                            <input type="text" name="arrearsDays" value="0" class="form-control" required>
                                        </div>
                                    @endif
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label">
                                            <i class="fa fa-comment-dots text-info"></i> 
                                            {{ Auth::user()->userType != '61' ? 'HR Remark' : 'Account Remark' }}
                                            <span class="text-red">*</span>
                                        </label>
                                        <textarea name="remark" class="form-control" placeholder="Enter your remark..." required>{{ $ticket->remark }}</textarea>
                                    </div>
                                    @if(Auth::user()->userType == '51' && $ticket->issue == 'Form 16')
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label"><i class="fa fa-upload text-success"></i> Upload {{ $ticket->issue }} <span class="text-red">*</span></label>
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                    @endif
                                </div>

                                {{-- Hidden Inputs --}}
                                <input type="hidden" name="fromMonth" value="{{ $fromMonth }}">
                                <input type="hidden" name="toMonth" value="{{ $toMonth }}">
                                <input type="hidden" name="id" value="{{ $ticket->id }}">

                                <div class="text-center mt-4">
                                    <button type="submit" name="approved" value="2" class="btn btn-success mx-1">
                                        <i class="fa fa-check-circle"></i> Close
                                    </button>
                                    <button type="submit" name="rejected" value="3" class="btn btn-danger mx-1">
                                        <i class="fa fa-times-circle"></i> Reject
                                    </button>
                                    <button type="submit" name="inprogress" value="4" class="btn btn-primary mx-1">
                                        <i class="fa fa-spinner"></i> In-Progress
                                    </button>
                                    <button type="submit" name="onHold" value="5" class="btn btn-warning mx-1">
                                        <i class="fa fa-pause-circle"></i> On Hold
                                    </button>
                                </div>

                                {!! Form::close() !!}
                            @else
                                <div class="row">
                                    @if($ticket->issueType == 1 && $ticket->status != 1)
                                        <div class="col-md-4">
                                            <div class="info-group">
                                                <i class="fa fa-calendar-check info-icon"></i>
                                                <div>
                                                    <div class="info-label">Arrears Days</div>
                                                    <div style="color:red;">{{ $ticket->arrearsDays}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <h5><i class="fa fa-info-circle text-secondary"></i> Final HR Remark</h5>
                                                <p class="mb-0">{{ $ticket->remark }}</p>
                                            </div>
                                        </div>
                                    </div>                                   
                            @endif

                            </div>
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
