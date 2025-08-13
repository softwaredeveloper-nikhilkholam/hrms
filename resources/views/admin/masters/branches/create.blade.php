@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Branches</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/branches" class="btn btn-primary mr-3">Active List</a>
                            <a href="/branches/dlist" class="btn btn-primary mr-3">Deactive List</a>
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
                            <h4 class="card-title">Add Branch</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\masters\BranchesController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Branch Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="name" placeholder="Branch Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email<span class="text-red">*</span>:</label>
                                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Branch Address<span class="text-red">*</span>:</label>
                                            <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="address" placeholder="Branch Address" required></textarea>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Country<span class="text-red">*</span> :</label>
                                            {{Form::select('countryId', $countries, 101, ['class'=>'form-control', 'id'=>'countryId', 'readonly'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select State<span class="text-red">*</span>:</label>
                                            {{Form::select('regionId', $regions, null, ['class'=>'form-control', 'placeholder'=>'Select State', 'id'=>'regionId', 'required'])}}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select City<span class="text-red">*</span> :</label>
                                            {{Form::select('cityId', [], NULL, ['class'=>'form-control', 'id'=>'cityId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">PIN Code<span class="text-red">*</span> :</label>
                                            <input type="text" class="form-control" name="PINCode" placeholder="PIN Code" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Contact No 1<span class="text-red">*</span> :</label>
                                            <input type="text" class="form-control" name="contactNo1" placeholder="Contact No 1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Contact No 2:</label>
                                            <input type="text" class="form-control" name="contactNo2" placeholder="Contact No 2">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/branches" class="btn btn-danger btn-lg">Cancel</a>

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
