@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Add Notice</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/notices" class="btn btn-primary mr-3">Active List</a>
                            <a href="/notices/dlist" class="btn btn-primary mr-3">Deactive List</a>
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
                            <h4 class="card-title">Add</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\NoticesController@store', 'method' => 'POST', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Branches<span class="text-red">*</span>:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Department<span class="text-red">*</span>:</label>
                                            {{Form::select('departmentId', $departments, null, ['placeholder'=>'Select Department','class'=>'form-control', 'id'=>'departmentId', 'required'])}}
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
                                            <label class="form-label">Select Section<span class="text-red">*</span>:</label>
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], null, ['placeholder'=>'Select Section','class'=>'form-control', 'id'=>'section', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Title<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="name" placeholder="Title">
                                        </div>
                                    </div>    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Discription<span class="text-red">*</span>:<b style="color:red;">[Allow only 1000 Characters..]</b></label>
                                            <textarea class="form-control" name="profile" placeholder="Discription"></textarea>
                                        </div>
                                    </div>                                     
                                </div>                               
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/notices" class="btn btn-danger btn-lg">Cancel</a>
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
