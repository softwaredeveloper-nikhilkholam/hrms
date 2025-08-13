@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Leave Paid Policy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="{{route('leavePaymentPolicy.index')}}" class="btn btn-primary mr-3">Active List</a>
                            <a href="{{route('leavePaymentPolicy.dlist')}}" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="{{route('leavePaymentPolicy.create')}}" class="btn btn-success mr-3">Add Leave Paid Policy</a>
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
                            <h4 class="card-title">Add Leave Paid Policy</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\LeavePaymentPolicyController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Section<span class="text-red">*</span>:</label>
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'section', 'required'])}}
                                        </div>
                                    </div>      
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Start Month<span class="text-red">*</span>:</label>
                                            {{Form::select('startMonth', $months, null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'startMonth', 'required'])}}
                                        </div>
                                    </div>       
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">End Month<span class="text-red">*</span>:</label>
                                            {{Form::select('startMonth', $months, null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'startMonth', 'required'])}}
                                        </div>
                                    </div>     
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Payment Days<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="paymentDays" placeholder="Payment Days">
                                        </div>
                                    </div>                            
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Branch:</label>
                                            {{Form::select('branchId', $branches, null, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId'])}}
                                        </div>
                                    </div>      
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Year Type<span class="text-red">*</span>:</label>
                                            {{Form::select('yearStatus', ['0'=>'Previous Year','1'=>'Current Year','2'=>'Next Year'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'yearStatus'])}}
                                        </div>
                                    </div>       
                                                            
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/departments" class="btn btn-danger btn-lg">Cancel</a>
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
