@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Departments</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/departments" class="btn btn-primary mr-3">Active List</a>
                            <a href="/departments/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="#" class="btn btn-success mr-3">Add</a>
                            
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
                            <h4 class="card-title">Add Department</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => ['admin\masters\DepartmentsController@update', $department->id], 'method' => 'POST']) !!}
                                <div class="row">
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Section<span class="text-red">*</span>:</label>
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $department->section, ['placeholder'=>'Select Section','class'=>'form-control', 'id'=>'section', 'required'])}}
                                        </div>
                                    </div>    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Departement Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" value="{{$department->name}}" name="name" placeholder="Department Name"  required>
                                        </div>
                                    </div>                                   
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure?')">Update</button>
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
