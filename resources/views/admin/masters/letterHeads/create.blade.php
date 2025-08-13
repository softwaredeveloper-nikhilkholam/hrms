@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Letterhead File</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/letterHeads" class="btn btn-primary mr-3">Active List</a>
                            <a href="/letterHeads/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="#" class="btn btn-success mr-3">Add</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Add Letterhead File</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\LetterHeadsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Letterhead Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="name" placeholder="Letterhead Name">
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Letterhead File<span class="text-red">*</span>:</label>
                                            <input type="file" class="form-control" name="fileName" placeholder="Letterhead File">
                                        </div>
                                    </div>                                    
                                </div>                               
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/home" class="btn btn-danger btn-lg">Cancel</a>
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
