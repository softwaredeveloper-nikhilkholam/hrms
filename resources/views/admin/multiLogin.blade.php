@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Multi Login By Username</h4>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Multilogin</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'HomeController@setMultiLogin', 'method' => 'GET', 'class' => 'form-horizontal']) !!}

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Username<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="username" placeholder="" required>
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="submit" value="Login" class="btn btn-primary mt-5">
                                        </div>
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
