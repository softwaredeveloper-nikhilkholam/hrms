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
                            <h4 class="card-title">Event Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Title &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        <input type="text" class="form-control" name="title"  value="{{$event->title}}" id="empName" placeholder="Event Title" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span> :</label>
                                        <input type="text" class="form-control" name="description"   value="{{$event->description}}" id="description" maxlength="2000" placeholder="Event Description" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Event Date <span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        <input type="date" class="form-control" name="forDate" value="{{$event->forDate}}" min="{{date('Y-m-d')}}" placeholder="dd-mm-yyyy" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Branch &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        {{Form::select('branchId', $branches, $event->branchId, ['placeholder'=>'Select Branch','class'=>'branchId form-control', 'id'=>'branchId', 'disabled'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Select Section&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        {{Form::select('sectionId', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $event->designationId, ['placeholder'=>'Select Section','class'=>'sectionId form-control', 'disabled'])}}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Select Designation&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                        {{Form::select('designationId', $designations, $event->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId', 'disabled'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Updated At<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span> :</label>
                                        <input type="text" class="form-control" name="description"  value="{{date('d-m-Y h:i A', strtotime($event->updated_at))}}" id="description" maxlength="2000" placeholder="Event Description" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Updated By<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span> :</label>
                                        <input type="text" class="form-control" name="description"  value="{{$event->updated_by}}" id="description" maxlength="2000" placeholder="Event Description" disabled>
                                    </div>
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
