@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                @if($letterType == 1)
                    <div class="page-leftheader">
                        <h4 class="page-title">Offer Letter List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getOfferLetter" class="btn btn-success mr-3">Add Offer Letter</a>
                            </div>
                        </div>
                    </div>
                @elseif($letterType == 2)
                    <div class="page-leftheader">
                        <h4 class="page-title">Appointment Letter List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getAppointmentLetter" class="btn btn-success mr-3">Add Appointment Letter</a> 
                            </div>
                        </div>
                    </div>
                @elseif($letterType == 3)
                    <div class="page-leftheader">
                        <h4 class="page-title">Agreement Letter List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getAgreement" class="btn btn-success mr-3">Add Agreement Letter</a>
                            </div>
                        </div>
                    </div>
                @elseif($letterType == 4)
                    <div class="page-leftheader">
                        <h4 class="page-title">Experience Letter List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getExperienceLetter" class="btn btn-success mr-3">Add Experience Letter</a>
                            </div>
                        </div>
                    </div>
                @elseif($letterType == 7)
                    <div class="page-leftheader">
                        <h4 class="page-title">Transfer Letter (Internal Branch) List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getInternalBranchTransferLetter" class="btn btn-success mr-3">Add Letter</a>
                            </div>
                        </div>
                    </div>
                @elseif($letterType == 8)
                    <div class="page-leftheader">
                        <h4 class="page-title">Transfer Letter (Internal Department) List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getInternalDepartmentTransferLetter" class="btn btn-success mr-3">Add Letter</a>
                            </div>
                        </div>
                    </div>
                @elseif($letterType == 9)
                    <div class="page-leftheader">
                        <h4 class="page-title">Promotion Letter List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getPromotionLetter" class="btn btn-success mr-3">Add Letter</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="page-leftheader">
                        <h4 class="page-title">Warning Letter List</h4>
                    </div>
                    <div class="page-rightheader ml-md-auto">
                        <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                            <div class="btn-list">
                                <a href="/employeeLetters/getWarningLetter" class="btn btn-success mr-3">Add Warning Letter</a>
                            </div>
                        </div>
                    </div>
                @endif 
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Employee Letter List</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($letters))
                                @if(count($letters) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0"  width="10%">Employee Code</th>
                                                    <th class="border-bottom-0">Employee Name</th>
                                                    <th class="border-bottom-0"  width="20%">Letter</th>
                                                    <th class="border-bottom-0"  width="10%">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($letters as $letter)
                                                    <tr>
                                                        <td>{{date('d-m-Y', strtotime($letter->created_at))}}</td>
                                                        <td>@if($letter->firmType == 1)
                                                                {{$letter->empCode}}
                                                            @elseif($letter->firmType == 2)
                                                                AFF{{$letter->empCode}}
                                                            @else
                                                                AFS{{$letter->empCode}}
                                                            @endif
                                                        </td>
                                                        <td>{{$letter->empName}}</td>
                                                        @if($letter->letterType == 1)
                                                            <td>Offer Letter</td>
                                                        @elseif($letter->letterType == 2)
                                                            <td>Appointment Letter</td>  
                                                        @elseif($letter->letterType == 3)
                                                            <td>Agreement</td>  
                                                        @elseif($letter->letterType == 4)
                                                            <td>Experience Letter</td>  
                                                        @elseif($letter->letterType == 7)
                                                            <td>Transfer Letter (Branch)</td>  
                                                        @elseif($letter->letterType == 8)
                                                            <td>Transfer Letter (Department)</td>  
                                                        @else
                                                            <td>Warning Letter</td> 
                                                        @endif 
                                                        <td>
                                                            @if($letterType == 1)
                                                                <a href="/employeeLetters/viewOfferLetter/{{$letter->id}}" class="btn btn-danger btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="View Details"></i>
                                                                </a>
                                                            @elseif($letterType == 2)
                                                                <a href="/employeeLetters/viewAppointmentLetter/{{$letter->id}}/1" class="btn btn-success btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="View Details With Letter Head"></i>
                                                                </a>
                                                                <a href="/employeeLetters/viewAppointmentLetter/{{$letter->id}}/2" class="btn btn-danger btn-icon btn-sm">
                                                                    <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="View Details Without Letter Head"></i>
                                                                </a>
                                                            @elseif($letterType == 3)
                                                                <a href="/employeeLetters/viewAgreement/{{$letter->id}}" class="btn btn-success mr-3">View Aggreement</a>
                                                            @elseif($letterType == 4)
                                                                <a href="/employeeLetters/viewExperienceLetter/{{$letter->id}}" class="btn btn-success mr-3">View</a>
                                                            @elseif($letterType == 7)
                                                                <a href="/employeeLetters/viewInternalBranchTransferLetter/{{$letter->id}}" class="btn btn-success mr-3">View</a>
                                                            @elseif($letterType == 8)
                                                                <a href="/employeeLetters/viewInternalDepartmentTransferLetter/{{$letter->id}}" class="btn btn-success mr-3">View</a>
                                                            @else
                                                                <a href="/employeeLetters/viewWarningLetter/{{$letter->id}}" class="btn btn-success mr-3">View</a>
                                                            @endif 
                                                            
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found Letter Records.</h4>
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
