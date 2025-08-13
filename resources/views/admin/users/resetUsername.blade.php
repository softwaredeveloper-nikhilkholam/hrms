@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Reset Password</h4>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Reset Password</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'HomeController@resetUsername', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="username" placeholder="Search Employee Username or Name..." class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Search" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            <hr>   
                            @if(isset($users))
                                <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;">Users List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0" id="example">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-white border-bottom-0 w-5 text-center">No</th>
                                                <th class="text-white border-bottom-0">Name</th>
                                                <th class="text-white border-bottom-0">Username</th>
                                                <th class="text-white border-bottom-0">Status</th>
                                                <th class="text-white border-bottom-0">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                @if($user->userType != '00')
                                                    <tr>
                                                        <td class="text-center">{{$i++}}</td>
                                                        <td>{{$user->name}}</td>
                                                        <td>{{$user->username}}</td>
                                                        <td>{{($user->status == 1)?'Active':'Deactive'}}</td>
                                                        <td><a href="#" class="btn btn-danger">Reset Password</a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
