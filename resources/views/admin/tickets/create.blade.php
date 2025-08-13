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
                            <a href="/tickets/list" class="btn btn-primary mr-3">Ticket List</a>
                            <a href="/tickets" class="btn btn-success mr-3">Raise Ticket</a>
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
                        <div class="card-header border-0">
                            <h4 class="card-title">Raise Ticket</h4>
                        </div>
                        <div class="card-body">
                            {{-- Displaying success or error messages --}}
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            {{-- Form to submit the ticket --}}
                           {!! Form::open(['action' => 'admin\TicketsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                {{-- Displaying validation errors --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">To<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="forDate" placeholder="Date" value="HR Department" disabled>
                                        </div>
                                    </div>  
                                </div>  
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Issue<span class="text-red">*</span>:</label>
                                            @if($flag == 1)
                                                {{Form::select('issue', ['AGF'=>'AGF', 'Arrears'=>'Arrears','Arrears Calculations'=>'Arrears Calculations', 'Extra Working '=>'Extra Working', 'Other'=>'Other'], old('issue'), ['placeholder'=>'Select issue','class'=>'form-control' . ($errors->has('issue') ? ' is-invalid' : ''), 'id'=>'issue', 'required'])}}
                                            @elseif($flag == 2)
                                                {{Form::select('issue', ['Change in Name'=>'Change in Name', 'Change in Phone Number'=>'Change in Phone Number', 'Change in Emergency Number'=>'Change in Emergency Number', 'Change in Email Id'=>'Change in Email Id', 'Change in Address'=>'Change in Address', 'Change in Marital Status'=>'Change in Marital Status', 'Change in Bank details'=>'Change in Bank details', 'Change in Branch'=>'Change in Branch', 'Change in Department'=>'Change in Department', 'Change in Designation'=>'Change in Designation', 'Change in Timings'=>'Change in Timings','Qualification Updation'=>'Qualification Updation', 'Other'=>'Other'], old('issue'), ['placeholder'=>'Select issue','class'=>'form-control' . ($errors->has('issue') ? ' is-invalid' : ''), 'id'=>'issue', 'required'])}}
                                            @elseif($flag == 3)
                                                {{Form::select('issue', ['Salary Certificate'=>'Salary Certificate', 'Form 16'=>'Form 16', 'Request for Original Document'=>'Request for Original Document', 'Salary on Hold'=>'Salary on Hold', 'Rejoining of Employee'=>'Rejoining of Employee', 'Other'=>'Other'], old('issue'), ['placeholder'=>'Select issue','class'=>'form-control' . ($errors->has('issue') ? ' is-invalid' : ''), 'id'=>'issue', 'required'])}}
                                            @else
                                                {{Form::select('issue', ['RESIGNATION OF EMPLOYEE'=>'RESIGNATION OF EMPLOYEE', 'APPROVAL OF THE SAME'=>'APPROVAL OF THE SAME', 'POST THREE MONTHS /6  MONTHS NEXT INITATION'=>'POST THREE MONTHS /6  MONTHS NEXT INITATION', 'Other'=>'Other'], old('issue'), ['placeholder'=>'Select issue','class'=>'form-control' . ($errors->has('issue') ? ' is-invalid' : ''), 'id'=>'issue', 'required'])}}
                                            @endif
                                            @error('issue')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>  
                                    @if($flag == 1)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">For Month<span class="text-red">*</span>:</label>
                                                <input type="text" value="{{date('M-Y', strtotime('-1 month'))}}" name="fromMonth" class="form-control" readonly>
                                                <!-- <input type="month" name="fromMonth" value="{{ old('fromMonth', date('Y-m', strtotime('-1 month'))) }}" class="form-control @error('fromMonth') is-invalid @enderror" max="{{date('Y-m', strtotime('-2 month'))}}" min="{{date('Y-m', strtotime('+1 month'))}}" required> -->
                                                @error('fromMonth')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>      
                                    @endif
                                    <div class="col-md-4 salaryCertificate">
                                        <div class="form-group">
                                            <label class="form-label">Period<span class="text-red">*</span>:</label>
                                            {{Form::select('period', ['Last Month'=>'Last Month', 'Last 3 Months'=>'Last 3 Months', 'Last 6 Months'=>'Last 6 Months', 'Last 9 Months'=>'Last 9 Months', 'Last 12 Months'=>'Last 12 Months'], old('period'), ['placeholder'=>'Select Month','class'=>'form-control' . ($errors->has('period') ? ' is-invalid' : '')])}}
                                            @error('period')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>      
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="forDate" placeholder="Date" value="{{date('d/m/Y')}}" disabled>
                                        </div>
                                    </div>          
                                </div>  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Description<span class="text-red">*</span>:</label>
                                            <textarea class="form-control @error('note') is-invalid @enderror" name="note" placeholder="Description" required>{{ old('note') }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>          
                                </div>      
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-4"></div>
                                        <div class="col-md-12 col-lg-4">
                                            <input type="hidden" class="form-control" name="flag" value="{{$flag}}">
                                            <button type="submit" class="btn btn-primary btn-lg">Raise Ticket</button>
                                            <a href="/tickets" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
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
