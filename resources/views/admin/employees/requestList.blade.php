@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Profile Update Request</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <div class="page-title">
                                <h4 class="page-title">Update Request</h4>
                            </div>
                            <div class="page-rightheader ml-md-auto">
                                <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                                    <div class="btn-list">
                                        <a href="#" class="btn btn-success mr-3">Update Request</a>
                                        <a href="/employees/profileAddRequestList" class="btn btn-primary mr-3">Add Request</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(isset($employees) && count($employees) > 0)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 w-5">#</th>
                                                <th class="border-bottom-0 w-10">Emp Code</th>
                                                <th class="border-bottom-0">Name</th>
                                                <th class="border-bottom-0 w-10">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employees as $index => $emp)
                                                <tr>
                                                    <th>{{ $index + 1 }}</th>
                                                    <td>{{ $emp->empCode }}</td>
                                                    <td>{{ $emp->name }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-success btn-icon btn-sm">
                                                            <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h4 class="text-danger">No records found.</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
