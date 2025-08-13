@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">WFH</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/hrPolicies/listWFH" class="btn btn-primary mr-3">Archive</a>
                            <a href="#" class="btn btn-success mr-3">Add WFH</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">WFH</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\HrPoliciesController@storeWFH', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" style="color:red;"><b>From date consider as Work From Home</b></label>
                                        </div>
                                    </div>                               
                                </div>     
                                <div class="row">  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">From<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="fromDate" placeholder="Date">
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">To<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" name="toDate" placeholder="Date">
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">WFH Type<span class="text-red">*</span>:</label>
                                            {{Form::select('wfhType', ['1'=>'Daily', '2'=>'Alternate Days', '3'=>'Gap 2 Days'], null, ['placeholder'=>'Select WFH Type','class'=>'form-control', 'id'=>'wfhType', 'required'])}}
                                        </div>
                                    </div>  
                                </div>  
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Branch<span class="text-red">*</span>:</label>
                                            {{Form::select('branchId', $branches, null, ['placeholder'=>'All Branch','class'=>'form-control', 'id'=>'branchId', 'required'])}}
                                        </div>
                                    </div>   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Staff Type<span class="text-red">*</span>:</label>
                                            {{Form::select('staffType', ['Non Teaching'=>'Non Teaching', 'Teaching'=>'Teaching'], null, ['placeholder'=>'All AWS','class'=>'form-control', 'id'=>'staffType', 'required'])}}
                                        </div>
                                    </div>    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Salary % (Per Day)<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="percent" placeholder="Salary % (Per Day)">
                                        </div>
                                    </div>                                  
                                </div>   
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Remark<span class="text-red">*</span>:</label>
                                            <textarea class="form-control" name="remark" placeholder="Remark" required></textarea>
                                        </div>
                                    </div>                               
                                </div>                               
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-4"></div>
                                        <div class="col-md-12 col-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/tickets" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
