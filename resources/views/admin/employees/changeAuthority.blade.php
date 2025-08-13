@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Change Reporting Authority</h4>
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
                            <h4 class="card-title">Change Reporting Authority</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmployeesController@changAuthority', 'method' => 'GET', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Reporting Authority From<span class="text-red">* Only Username</span>:</label>
                                            <input type="text" name="from" class="form-control" placeholder="Enter Username" value="{{((isset($from))?$from:null)}}" id="from">
                                            <b style="color:blue;">{{(isset($fromUser))?$fromUser:''}}</b>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Reporting Authority To<span class="text-red">* Only Username</span>:</label>
                                            <input type="text" name="to" class="form-control" placeholder="Enter Username" value="{{((isset($to))?$to:null)}}" id="to">
                                            <b style="color:blue;">{{(isset($toUser))?$toUser:''}}</b>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Branches:</label>
                                            {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-5">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>  
                                <hr>                           
                            {!! Form::close() !!}
                            @if(isset($employees))
                                @if(count($employees) >= 1)
                                    <div class="table-responsive">
                                        {!! Form::open(['action' => 'admin\employees\EmployeesController@updateChangAuthority', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                            <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="">
                                                <thead class="text-white" style="background-color:seagreen;">
                                                    <tr>
                                                        <th class="text-white" width="5%">Emp Code</th>
                                                        <th class="text-white">Employee Name</th>
                                                        <th class="text-white">Branch</th>
                                                        <th class="text-white">Designation</th>
                                                        <th class="text-white" width="10%">Actions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" id="selectAll" style="width: 15px;height: 15px;"> 
                                                        <?php $i=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($employees as $employee)
                                                        <tr>
                                                            <td style="font-size:16px;">{{$employee->empCode}}</td>
                                                            <td>{{$employee->name}}</td>
                                                            <td>{{$employee->branchName}}</td>
                                                            <td>{{$employee->designationName}}</td>
                                                            <td class="text-center">
                                                                <input class="form-check-input" type="checkbox" id="authCheck" name="option1[]" value="{{$employee->id}}" style="margin-top:0px;width: 15px;height: 15px;">
                                                            </td>
                                                        </tr>
                                                    @endforeach                                        
                                                </tbody>
                                            </table>
                                            <div class="form-group mt-5">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-5"></div>
                                                    <div class="col-md-12 col-lg-3">
                                                        <input type="hidden" value="{{$from}}" name="updateFrom">
                                                        <input type="hidden" value="{{$to}}" name="updateTo">
                                                        <input type="hidden" value="{{$branchId}}" name="updateBranchId">
                                                        <button type="submit" class="btn btn-success btn-lg">Update</button>
                                                        <button type="reset" class="btn btn-danger btn-lg">Cancel</button>
                                                    </div>
                                                    <div class="col-md-12 col-lg-4"></div>
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                @else
                                    <h4 style="color:red;">Record Not found.</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
