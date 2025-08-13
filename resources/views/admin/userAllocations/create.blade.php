<?php
    $user = Auth::user();
    $userType = $user->userType;
?>

@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Users</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/userAllocations" class="btn btn-success mr-3">List</a>
                            <a href="#" class="btn btn-primary mr-3">Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">User Allocation</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\UserAllocationsController@create', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="empCode" placeholder="Search Employee Code..." class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>
                            @if($empCode != '')
                                @if(!empty($user))
                                    {!! Form::open(['action' => 'admin\UserAllocationsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                        <div class="row">
                                            <div class="col-md-3">
                                                <b>Name : {{$empDet->empCode}}-{{$empDet->name}}</b>
                                            </div>
                                            <div class="col-md-3">
                                                <b>Designation : {{$empDet->designationName}}</b>
                                            </div>
                                            <div class="col-md-3">
                                                <b>Department : {{$empDet->departmentName}}</b>
                                            </div>
                                            <div class="col-md-3">
                                                <b>Branch : {{$empDet->branchName}}</b>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Department &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    {{Form::select('userRoleId', $userRoles, null, ['class'=>'form-control', 'placeholder'=>'Select a Option'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Username &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Password &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="password" id="password" placeholder="Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-4"></div>
                                                <div class="col-md-12 col-lg-4">
                                                    <input type="hidden" value="{{$empDet->id}}" name="empId">
                                                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                                    <a href="/home" class="btn btn-danger btn-lg">Cancel</a>
                                                </div>
                                                <div class="col-md-12 col-lg-4"></div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
