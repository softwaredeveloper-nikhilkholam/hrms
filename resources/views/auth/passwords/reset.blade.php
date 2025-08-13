<?php
 $user = Auth::user();
 $username = $user->username;

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
                    <h4 class="page-title">Change Password</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
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
                            <h4 class="card-title">Change Password</h4>
                        </div>
                        <div class="card-body">
							{!! Form::open(['action' => 'HomeController@updatePass', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">New Password&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="password" class="form-control" name="newPassword1"  value="" id="password1" placeholder="New Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Confirm New Password&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="password" class="form-control" name="newPassword2"  value="" id="password2" placeholder="Confirm Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="showPassword"  onclick="myFunction2()">
                                                <span class="custom-control-label">Show Password</span>
                                            </label>	
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3"></div>
                                </div>

								<div class="row" style="margin-top:10px;">
									<div class="col-md-5"></div>
									<div class="col-md-3">
										<button type="Submit" class="btn btn-success btn-lg">Change Password</button>
									</div>
									<div class="col-md-4"></div>
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
