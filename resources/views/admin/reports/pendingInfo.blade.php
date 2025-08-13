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
                <h4 class="page-title">Employee Pending Information Report</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\ReportsController@pendingInfo', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Option:</label>
                                        {{Form::select('options', ['1'=>'DOB', '2'=>'Gender', '3'=>'Branch', '5'=>'Department', '6'=>'Designation', '7'=>'Reporting Authority', '8'=>'Job Joining Date', '9'=>'Contract Date'], (isset($option)?$option:''), ['placeholder'=>'Select Option','class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($pendingInfos))
                            @if(count($pendingInfos) >= 1)
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 w-5">#</th>
                                                <th class="border-bottom-0">Employee Name</th>
                                                <th class="border-bottom-0 w-10">Actions<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingInfos as $pending)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>@if($pending->firmType == 1)
                                                            {{$pending->empCode}}
                                                        @elseif($pending->firmType == 2)
                                                            AFF{{$pending->empCode}}
                                                        @else
                                                            AFS{{$pending->empCode}}
                                                        @endif -
                                                        {{$pending->name}}</td>
                                                    <td><a href="/employees/{{$pending->id}}/edit" class="btn btn-primary">Edit</a></td>
                                                </tr>
                                            @endforeach                                        
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$pendingInfos->links()}}</div>
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
