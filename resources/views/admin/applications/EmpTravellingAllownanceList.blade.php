<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = Auth::user()->language; 
    $transAllowed = Auth::user()->transAllowed; 

?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Travelling Allowance List</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\employees\EmpApplicationsController@applyTAllow', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Month<span class="text-red">*</span>:</label>
                                        <input type="month" class="form-control" value="{{date('Y-m')}}" max="{{date('Y-m')}}" name="month" placeholder="Date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mt-5">Apply For</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="card-body">
                        @if(isset($applications))
                            @if(count($applications) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-striped card-table table-vcenter text-nowrap mb-0" id="example">
                                        <thead class="bg-primary text-white">
                                            <tr> 
                                                <th class="text-white w-5">#</th>
                                                <th class="text-white w-20">Month</th>
                                                <th class="text-white w-10">Total</th>
                                                <th class="text-white w-10">Pending From Authority</th>
                                                <th class="text-white w-10">Pending From Account </th>
                                                <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $app)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('M-Y', strtotime($app->forDate))}}</td>
                                                    <td>{{$app->totalCt}}</td>
                                                    <td>{{$app->pendingCt}}</td>
                                                    <td>{{$app->acPendingCt}}</td>
                                                    <td>
                                                        <a href="/empApplications/{{$empId}}/{{$app->forDate}}/4/travellingTranspShow" class="btn btn-success btn-icon btn-sm">
                                                            <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table> 
                                </div>
                            @else
                                <hr>
                                <h4 style="color:red;">Record not Found.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
