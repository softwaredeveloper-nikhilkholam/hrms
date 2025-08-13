@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Ticket For HR</h4>
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
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="/tickets/raiseTicket/1">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="mt-0 text-left"><span class="fs-14 font-weight-semibold"><b style="color:red;">Salary issue</b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="/tickets/raiseTicket/2">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold"><b style="color:blue;">Changes to be done at HR END</b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="/tickets/raiseTicket/3">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold"><b style="color:purple;">Requests</b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="/tickets/raiseTicket/4">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold"><b style="color:green;">Exit Formatlites Initiation</b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
                        
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
