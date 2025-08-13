@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Forms & Circular</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/formsCirculars" class="btn btn-primary mr-3">Current List</a>
                            <a href="/formsCirculars/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="#" class="btn btn-success mr-3">Add Forms & Circular</a>
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
                            <h4 class="card-title">Add Forms & Circular</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\FormAndCircularsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Circular No.<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="circularNo" placeholder="Circular No." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Name<span class="text-red">*</span>:</label>
                                            <input type="name" class="form-control" name="name" placeholder="Circular Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Policy For<span class="text-red">*</span>:</label>
                                            {{Form::select('status', ['All Employees'=>'All Employees', 'HR Department'=>'HR Department'], null , ['placeholder'=>'Select Option ','class'=>'form-control', 'id'=>'status', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Upload File<span class="text-red">*</span>:</label>
                                            <input type="file" class="form-control" name="photos[]" multiple="multiple" placeholder="Upload File" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/assets" class="btn btn-danger btn-lg">Cancel</a>
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
