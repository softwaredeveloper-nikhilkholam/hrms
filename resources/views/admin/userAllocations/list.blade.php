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
                            <a href="#" class="btn btn-success mr-3">List</a>
                            <a href="/userAllocations/create" class="btn btn-primary mr-3">Add</a>
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
                            @if(isset($users))
                                @if(count($users) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-10">Username</th>
                                                    <th class="border-bottom-0">Password</th>
                                                    <th class="border-bottom-0 w-10">Employee Name</th>
                                                    <th class="border-bottom-0 w-10">Department</th>
                                                    <th class="border-bottom-0 w-10">Updated</th>
                                                    <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $user)
                                                    <tr>
                                                        <th>{{$i++}}</th>
                                                        <td>{{$user->username}}</td>
                                                        <td>{{$user->viewPassword}}</td>
                                                        <td>{{$user->name}}</td>
                                                        <td>{{$user->departmentName}}</td>
                                                        <td>{{$user->updated_by}}<br>{{date('d-M-Y H:i', strtotime($user->created_at))}}</td>
                                                        <td>
                                                            <a href="/userAllocations/{{$user->id}}/edit" class="btn btn-warning btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            @if($user->active == 1)
                                                                <a href="/userAllocations/{{$user->id}}/deactivate" class="btn btn-danger btn-icon btn-sm">
                                                                    <i class="fa fa-delete" style="font-size:20px;" aria-hidden="true"></i>
                                                                </a>
                                                            @else
                                                                <a href="/userAllocations/{{$user->id}}/activate" class="btn btn-success btn-icon btn-sm">
                                                                    <i class="fa fa-tick" style="font-size:20px;" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Active Records.</h4>
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
