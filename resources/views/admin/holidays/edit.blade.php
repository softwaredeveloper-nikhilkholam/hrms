@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Holidays</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/holidays" class="btn btn-primary mr-3">Upcomming List</a>
                            <a href="/holidays/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/holidays/create" class="btn btn-success mr-3">Add Holiday</a>
                            
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
                            <h4 class="card-title">Edit Holiday</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => ['admin\HolidaysController@update', $holiday->id], 'method' => 'POST']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Select Departments<span class="text-red">*</span>:</label>
                                            <select class="form-control" name="departmentId[]" multiple="multiple" size='8'>
                                                <option value="">Pick a Option</option>
                                                @foreach($departments as $depart)
                                                    <option value="{{$depart->id}}" {{($depart->status == 1)?'selected':''}}>{{$depart->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date<span class="text-red">*</span>:</label>
                                            <input type="date" class="form-control" value="{{$holiday->forDate}}" name="forDate" placeholder="" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Holiday Name<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" value="{{$holiday->name}}" name="name" placeholder="Holiday Name" required>
                                        </div>
                                    </div>                                    
                                </div>         

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure?')">Update</button>
                                            <a href="/holidays" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
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
