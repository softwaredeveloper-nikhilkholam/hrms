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
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Forms & Circular</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                        @if($userType == '00' || $userType == '51')
                            <a href="/formsCirculars" class="btn btn-primary mr-3">Active List</a>
                            <a href="/formsCirculars/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/formsCirculars/create" class="btn btn-primary mr-3">Add Forms & Circular</a>
                        @else
                            <a href="{{ url()->previous() }}" class="btn btn-primary mr-3">Back To List</a>
                        @endif
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
                            <h4 class="card-title">Forms & Circular Detail</h4>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Circular No :</label>
                                            <input type="text" class="form-control" value="{{$formsCircular->circularNo}}" name="name" placeholder="Branch Name" readonly>
                                        </div>
                                    </div>                                   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Circular Name:</label>
                                            <input type="text" class="form-control" value="{{$formsCircular->name}}" name="name" placeholder="Branch Name" readonly>
                                        </div>
                                    </div>                                   
                                </div> 
                                
                                @if(isset($photos))    
                                    <div class="row">
                                        <?php $i=0; ?>
                                        <div class="table-responsive">
                                            <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0 w-5">#</th>
                                                        <th class="border-bottom-0 w-80">File Name</th>
                                                        <th class="border-bottom-0">Show</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($photos as $cirPhoto)
                                                        <tr>
                                                            <?php $i++; ?>
                                                            <td>{{$i}}</td>
                                                            <td>File {{$i}}</td>
                                                            <td>
                                                                <a class="btn btn-success" href="/admin/images/formscirculars/{{$cirPhoto->photo}}" target="_blank">
                                                                    Show <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach  
                                                </tbody>
                                            </table>
                                        </div>             
                                    </div>             
                                @endif                     
                                @if($userType == '00')
                                    <div class="form-group mt-4">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-5"></div>
                                            <div class="col-md-12 col-lg-3">
                                                <a href="/formsCirculars/{{$formsCircular->id}}/edit" class="btn btn-warning btn-lg">
                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>&nbsp;&nbsp;Edit</a>
                                            </div>
                                            <div class="col-md-12 col-lg-4"></div>
                                        </div>
                                    </div>
                                @endif
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
