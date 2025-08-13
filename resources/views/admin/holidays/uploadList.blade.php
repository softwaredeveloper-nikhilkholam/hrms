@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Holidays</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/holidays" class="btn btn-primary mr-3">Upcomming List</a>
                            <a href="/holidays/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="#" class="btn btn-success mr-3">Add Holiday</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Upload Holiday List</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\HolidaysController@uploadHolidayList', 'method' => 'POST', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Upload File<span class="text-red">*</span>:<b style="color:red;">[ Upload only PDF ]</b></label>
                                            <input type="file" class="form-control" accept="application/pdf" name="fileName" placeholder="" required>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Year<span class="text-red">*</span>:</label>
                                            <input type="text" class="form-control" name="name" placeholder="Year" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                           <label class="form-label" for="Report Type">Branch<span class="text-red">*</span></label>
                                           <select class="form-control" id="Report Type" name="Report Type" required>
                                             <option selected disabled value="">Select Branch</option>
                                             <option value="Teaching" {{ old('Report Type') == 'Yes' ? 'selected' : '' }}>Pune Branch</option>
                                             <option value="Non Teaching" {{ old('Report Type') == 'No' ? 'selected' : '' }}>Belgaon Branch</option>
                                           </select>
                                        </div>
                                </div>
                                </div>   
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
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
