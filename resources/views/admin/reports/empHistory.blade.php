<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Employee Document History</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\employees\EmployeesController@empHistory', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Employee Code:</label>
                                        <input type='text' value="{{$empCode}}" class="form-control" name="empCode" id="empCode"/>
                                    </div>
                                </div>
                                <div class="class="col-md-1 col-lg-2">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div> 
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($histories))
                            @if(count($histories) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 w-5">#</th>
                                                <th class="border-bottom-0 w-5">Emp Code</th>
                                                <th class="border-bottom-0 w-10">Name</th>
                                                <th class="border-bottom-0 w-10">Document Name</th>
                                                <th class="border-bottom-0">Details</th>
                                                <th class="border-bottom-0 w-5">Status</th>
                                                <th class="border-bottom-0 w-10">Updated At</th>
                                                <th class="border-bottom-0 w-10">Updated By<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($histories as $history)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$history->empCode}}</td>
                                                    <td>{{$history->empName}}</td>
                                                    
                                                    <td>Doc. Name: {{$history->name}}<br>
                                                        Doc. Name: {{$history->name}}<br>
                                                    
                                                    </td>
                                                    <td>{{date('d-m-Y h:i A', strtotime($history->updated_at))}}</td>
                                                    <td>{{$history->updated_by}}</td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$histories->links()}}</div>
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Records.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
