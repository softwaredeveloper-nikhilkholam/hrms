@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Job Vacancy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/empJobs" class="btn btn-primary mr-3">Current Opening List</a>
                            <a href="/empJobs/dlist" class="btn btn-primary mr-3">Archive Opening List</a>
                            <a href="#" class="btn btn-success mr-3">Add Job Vacancy</a>
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
                                <h4 class="card-title">Add Job Vacancy</h4>
                            </div>
                            <div class="card-body">
                                {!! Form::open(['action' => ['admin\recruitments\EmpJobsController@update', $empJob->id], 'method' => 'POST']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Job Position &nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" value="{{$empJob->jobPosition}}" name="jobPosition" id="jobPosition" placeholder="Job Position" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Branch &nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('branchId', $branches, $empJob->branchId, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Department&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('departmentId', $departments, $empJob->departmentId, ['placeholder'=>'Select Department','class'=>'empDepartmentId form-control', 'id'=>'departmentId', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Designation&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('designationId', $designations, $empJob->designationId, ['placeholder'=>'Select Designation','class'=>'empDesignationId form-control', 'id'=>'designationId', 'required'])}}
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Job Type&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('jobType', ['Full-Time'=>'Full-Time', 'Part-Time'=>'Part-Time', 'Freelancer'=>'Freelancer'], $empJob->jobType, ['placeholder'=>'Select Job Type','class'=>'form-control', 'id'=>'jobType', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Select Gender&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            {{Form::select('gender', ['0'=>'Any','1'=>'Male', '2'=>'Female'], $empJob->gender, ['placeholder'=>'Select Gender','class'=>'form-control', 'id'=>'gender', 'required'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">no Of Vacancy&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control"  value="{{$empJob->noOfVacancy}}" name="noOfVacancy" id="noOfVacancy" placeholder="no Of Vacancy" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Experience&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control"  value="{{$empJob->experience}}" name="experience" id="experience" placeholder="Experience" required>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Posted Date&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="feather feather-calendar"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control fc-datepicker" name="postedDate"  value="{{$empJob->postedDate}}" placeholder="DD-MM-YYY" type="date">
                                            </div>
                                        </div>                        
                                    </div>    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Last Date To Apply&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="feather feather-calendar"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control fc-datepicker" name="lastDateToApply"  value="{{$empJob->lastDateToApply}}" placeholder="DD-MM-YYY" type="date">
                                            </div>
                                        </div>                        
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Education&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" name="education"  value="{{$empJob->education}}" id="education" placeholder="Experience" required>
                                        </div>
                                    </div>                    
                                </div> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Salary From&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control"  value="{{$empJob->salaryFrom}}" name="salaryFrom" id="salaryFrom" placeholder="Salary From" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Salary To&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control"  value="{{$empJob->salaryTo}}" name="salaryTo" id="salaryTo" placeholder="Salary To" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Skill&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control"  value="{{$empJob->skill}}" name="skill" id="skill" placeholder="Salary From" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Languages&nbsp;<span class="text-red" style="font-size:22px;">*</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control"  value="{{$empJob->language}}" name="language" id="language" placeholder="languages" required>
                                        </div>
                                    </div>   
                                </div> 
                                <hr>  
                                <h5>Add Description Point Wise (One By One)</h5>
                                <div class="row">    
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="form-label">Description&nbsp;<span class="text-red">( Add Point in Below Table )</span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                            <input type="text" class="form-control" id="empJobDescription" placeholder="Description">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group" style="margin-top:35px;">
                                            <button type="button" id="addJobDescRow" class="addJobDescRow btn btn-primary">Add Point</button>
                                        </div>
                                    </div>
                                </div>     
                                <div class="row">    
                                    <div class="col-md-12">
                                        <div class="row empJobRow">
                                            <div class="table-responsive">
                                                <table id="empJobTable" class="empJobTable table table-bordered card-table table-vcenter text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th width="90%">Description</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>	
                                                        @foreach($jobDescs as $temp)
                                                            <tr>
                                                                <td><input type="text" value="{{$temp->description}}" name="jobDescription[]" readonly class="form-control"></td>
                                                                <td><button type="button" class="ibtnDel btn btn-md btn-danger" value="Delete">Delete</button></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>               
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <input type="hidden" value="{{count($jobDescs)}}" id="rowCt">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
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
    