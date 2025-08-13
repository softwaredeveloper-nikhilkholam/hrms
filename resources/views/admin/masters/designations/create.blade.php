@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Designation</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/designations" class="btn btn-primary mr-3">Active List</a>
                            <a href="/designations/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="#" class="btn btn-success mr-3">Add Designation</a>
                            
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
                            <h4 class="card-title">Add Designation</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\masters\DesignationsController@store', 'method' => 'POST', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Department<span class="text-red">*</span>:</label>
                                            {{Form::select('departmentId', $departments, null, ['placeholder'=>'Select Department','class'=>'form-control', 'id'=>'departmentId', 'required'])}}
                                        </div>
                                    </div>      
                                    <div class="col-md-3 categoryShow">
                                        <div class="form-group">
                                            <label class="form-label">Select Category<span class="text-red">*</span>:</label>
                                            {{Form::select('category', ["Pre-Primary"=>"Pre-Primary","Primary - Secondary"=>"Primary - Secondary","Primary"=>"Primary","Authorities"=>"Authorities","Secondary"=>"Secondary","NA"=>"NA","Acedemic Director"=>"Acedemic Director"], null, ['placeholder'=>'Select Category','class'=>'form-control', 'id'=>'category', 'required'])}}
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="name" placeholder="Designation Name">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Job Opening<span class="text-red">*</span>:</label>
                                            {{Form::select('interviewStatus', ['0'=>'No', '1'=>'Yes'], 0, ['placeholder'=>'Select Job Opening','class'=>'form-control', 'required'])}}
                                        </div>
                                    </div> 
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Profile Details<span class="text-red">*</span>:<b style="color:red;">[Allow only 1000 Characters..]</b></label>
                                            <textarea class="form-control" name="profile" placeholder="Profile Details"></textarea>
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
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
