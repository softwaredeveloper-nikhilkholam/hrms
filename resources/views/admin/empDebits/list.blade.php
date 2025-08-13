<?php
    use App\Helpers\Utility;
    $util = new Utility();   

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
                    <h4 class="page-title">Employee Deduction</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empDebits" class="btn btn-primary mr-3">List</a>
                            <a href="/empDebits/create" class="btn btn-success mr-3">Add Deduction</a>
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
                        </div>
                        <div class="card-body">
                            @if(isset($debits))
                                @if(count($debits) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0" width="5%">No</th>
                                                    <th class="border-bottom-0" width="10%">Date</th>
                                                    <th class="border-bottom-0" width="10%">Employee Code</th>
                                                    <th class="border-bottom-0" width="30%">Employee Name</th>
                                                    <th class="border-bottom-0" width="10%">Debit Rs</th>
                                                    <th class="border-bottom-0" width="10%">Mgmt. Status</th>
                                                    <th class="border-bottom-0" width="10%">Accounts Status</th>
                                                    <th class="border-bottom-0" width="10%">Debit Status</th>
                                                    <th class="border-bottom-0">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($debits as $debit)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{date('d-m-Y', strtotime($debit->created_at))}}</td>
                                                        <td>{{$debit->empCode}}</td>
                                                        <td>{{$debit->empName}}</td>
                                                        <td>{{$util->numberFormatRound($debit->amount)}}</td>
                                                        <td style="color:{{($debit->managementStatus == 0)?'black;':(($debit->managementStatus == 1)?'green;':'red;')}}"><b>{{($debit->managementStatus == 0)?'Pending':(($debit->managementStatus == 1)?'Approved':'Rejected')}}</b></td>
                                                        <td style="color:{{($debit->accountStatus == 0)?'black;':(($debit->accountStatus == 1)?'green;':'red;')}}"><b>{{($debit->accountStatus == 0)?'Pending':(($debit->accountStatus == 1)?'Approved':'Rejected')}}</b></td>
                                                        <td style="color:{{($debit->status == 0)?'black;':'green;'}}">{{($debit->status == 0)?'Pending':'Deducted'}}</td>
                                                        <td>
                                                            <a href="/empDebits/{{$debit->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                                                            <a href="/empDebits/{{$debit->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Found any Record.</h4>
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
