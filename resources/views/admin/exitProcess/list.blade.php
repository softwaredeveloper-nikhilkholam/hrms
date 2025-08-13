<?php
    use App\Helpers\Utility;
    $util = new Utility();
    $userType = Auth::user()->userType;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content')  
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Resignations</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">List</a>
                            <a href="/exitProces/apply" class="btn btn-primary mr-3">Raise Resignation</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Employee Resignation List</h4>
                        </div>
                        <div class="card-body">
                        @if(isset($resins))
                                @if(count($resins) >= 1)
                                    <div class="table-responsive">
                                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0"  width="5%">No</th>
                                                    <th class="border-bottom-0"  width="12%">Name</th>
                                                    <th class="border-bottom-0"  width="5%">Reporting Authority</th>
                                                    <th class="border-bottom-0"  width="5%">Store Dept</th>
                                                    <th class="border-bottom-0"  width="5%">IT Dept</th>
                                                    <th class="border-bottom-0"  width="5%">ERP Dept</th>
                                                    <th class="border-bottom-0"  width="5%">HR Dept</th>
                                                    <th class="border-bottom-0"  width="5%">MD/CEO/COO</th>
                                                    <th class="border-bottom-0"  width="5%">Accounts Dept</th>
                                                    <th class="border-bottom-0" width="5%">Status</th>
                                                    <th class="border-bottom-0" width="5%">Updated At</th>
                                                    <th class="border-bottom-0" width="10%">Updated By</th>
                                                    <th class="border-bottom-0">Actions<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($resins as $temp)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td><a href="/exitProces/view/{{$temp->id}}"><h5>{{$temp->empCode}}-{{$temp->name}}</h5>Applied Date: {{date('d-m-Y h:i A', strtotime($temp->created_at))}}</a></td>
                                                        <td><b style="color:{{($temp->reportingAuth == 0)?'red':(($temp->reportingAuth == 1)?'purple':'green')}};">{{($temp->reportingAuth == 0)?'Pending':(($temp->reportingAuth == 1)?'In Progress':'Completed')}}<br>{{($temp->reportingAuthDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->storeDept == 0)?'red':(($temp->storeDept == 1)?'purple':'green')}};">{{($temp->storeDept == 0)?'Pending':(($temp->storeDept == 1)?'In Progress':'Completed')}}<br>{{($temp->storeDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->itDept == 0)?'red':(($temp->itDept == 1)?'purple':'green')}};">{{($temp->itDept == 0)?'Pending':(($temp->itDept == 1)?'In Progress':'Completed')}}<br>{{($temp->itDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->erpDept == 0)?'red':(($temp->erpDept == 1)?'purple':'green')}};">{{($temp->erpDept == 0)?'Pending':(($temp->erpDept == 1)?'In Progress':'Completed')}}<br>{{($temp->erpDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->hrDept == 0)?'red':(($temp->hrDept == 1)?'purple':'green')}};">{{($temp->hrDept == 0)?'Pending':(($temp->hrDept == 1)?'In Progress':'Completed')}}<br>{{($temp->hrDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->finalPermission == 0)?'red':(($temp->finalPermission == 1)?'purple':'green')}};">{{($temp->finalPermission == 0)?'Pending':(($temp->finalPermission == 1)?'In Progress':'Completed')}}<br>{{($temp->finalPermissionDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->accountDept == 0)?'red':(($temp->accountDept == 1)?'purple':'green')}};">{{($temp->accountDept == 0)?'Pending':(($temp->accountDept == 1)?'In Progress':'Completed')}}<br>{{($temp->accountDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->status == 0)?'red':(($temp->status == 1)?'purple':'green')}};">{{($temp->status == 0)?'Pending':(($temp->status == 1)?'In Progress':'Completed')}}</td>
                                                        <td>{{date('d-m-Y h:i A', strtotime($temp->updated_at))}}</td>
                                                        <td>{{$temp->updated_by}}</td>
                                                        <td>
                                                            <a href="/exitProces/deleteResignation/{{$temp->id}}" class="btn btn-danger btn-icon btn-sm">
                                                                <i class="fa fa-trash-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                            </a>   
                                                            <a href="/exitProces/view/{{$temp->id}}" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-eye" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="View Details"></i>
                                                            </a>                                                            
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                                <a href="/exitProces/exportExcel" class="btn btn-success btn-icon btn-sm">
                                                                <i class="fa fa-excel" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="View Details"></i>Export
                                                            </a>   
                                        </div>
                                    </div>
                                @else
                                    <h4 style="color:red;">Not Record found.</h4>
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
