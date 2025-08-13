<?php
 $user = Auth::user();
 $language = $user->language;
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
                    <h4 class="page-title">{{($language == 1)?'Holiday': 'सुट्टीचे दिवस'}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">{{($language == 1)?'Holiday List': 'सुट्टीची यादी'}}</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($holidays))
                                @if(count($holidays) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0 w-10">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Holiday Name': 'सुट्टीच दिवस'}}<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($holidays as $holiday)
                                                    @if($holiday->status == 1)
                                                        <tr style="color:orange;font-weight: bold;">
                                                    @endif
                                                    @if($holiday->status == 2)
                                                        <tr style="color:green;font-weight: bold;">
                                                    @endif
                                                        <th>{{$i++}}</th>
                                                        <td>{{date('d-m-Y', strtotime($holiday->forDate))}}</td>
                                                        <td>{{$holiday->name}}</td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Holiday Records.</h4>
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
