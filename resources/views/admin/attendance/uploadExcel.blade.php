@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employees Attendance</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Active Employees</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmpAttendancesController@uploadExcel', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Please Download Excel sheet Template<span class="text-red">*</span>:</label>
                                            <a href="/admin/attend_sheet_Template.xls" download><b style="color:red;">Click here....</b></a>
                                        </div>
                                    </div>  
                                </div>  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Month-Year<span class="text-red">*</span>:</label>
                                            <input type="month" name="month" value="{{date('Y-m', strtotime('-1 month'))}}" class="form-control" id="month">
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Select Excel Sheet<span class="text-red">*</span>:</label>
                                            <input type="file" class="form-control" name="excelFile" placeholder="Upload File" required>
                                            @if($errors->has('excelFile'))
                                                <div class="error">{{ $errors->first('excelFile') }}</div>
                                            @endif
                                        </div>
                                    </div>                                    
                                </div>    
                                <hr>                           
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Upload</button>
                                            <a href="/departments" class="btn btn-danger btn-lg">Cancel</a>
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
