@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Event</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/events" class="btn btn-success mr-3">Active List</a>
                            <a href="/events/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/events/create" class="btn btn-primary mr-3">Add Event</a>
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
                            <h4 class="card-title">Add Event</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'EventsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Title &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="title"  value="" id="empName" placeholder="Event Title" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Description<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span> <b style="color:red;">[ Allow only 2000 characters.]</b>:</label>
                                            <input type="text" class="form-control" name="description"  value="" id="description" maxlength="2000" placeholder="Event Description" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Event Date <span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="date" class="form-control" name="forDate" min="{{date('Y-m-d')}}" placeholder="dd-mm-yyyy" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Branch &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('branchId', $branches, null, ['placeholder'=>'Select Branch','class'=>'branchId form-control', 'id'=>'branchId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Select Section&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], '', ['placeholder'=>'Select Section','class'=>'sectionId form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Select Designation&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('designationId', $designations, (isset($application)?$application->designationId:''), ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/events" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
