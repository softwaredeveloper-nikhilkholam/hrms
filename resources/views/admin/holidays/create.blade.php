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
                            <h4 class="card-title">Add Holiday</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\HolidaysController@create', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="select_all_branch" name="option" value="1">
                                            <label class="form-check-label" for="select_all_branch" style="font-size:18px;">Select All Branch</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="branchDiv">
                                    @foreach($branches as $branch)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input branchCheckClass" id="branchCheck{{$branch->id}}" name="branchOption[]" value="{{$branch->id}}">
                                                <label class="form-check-label" for="branchCheck{{$branch->id}}"><b style="color:blue;">{{$branch->branchName}}</b></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="select_all_department" name="option" value="1">
                                            <label class="form-check-label" for="select_all_department" style="font-size:18px;">Select All Department</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"  id="">
                                    @foreach($departments as $department)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input departmentCheckClass" id="departmentCheck{{$department->id}}" name="departmentOption[]" value="{{$department->id}}">
                                                <label class="form-check-label" for="departmentCheck{{$department->id}}"><b style="color:red;">{{$department->name}}</b></label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Next</button>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}

                            @if(!empty($branchIds) && !empty($departmentIds))
                                {!! Form::open(['action' => 'admin\HolidaysController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="select_all_designation" name="option" value="1">
                                                <label class="form-check-label" for="select_all_designation" style="font-size:18px;">Select All Designation</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"  id="designationDiv">
                                        @foreach($designations as $designation)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input designationCheckClass" id="designationCheck{{$designation->id}}" name="designationOption[]" value="{{$designation->id}}" checked>
                                                    <label class="form-check-label" for="designationCheck{{$designation->id}}"><b style="color:green;">{{$designation->departmentName}}-{{$designation->name}}</b></label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Date<span class="text-red">*</span>:</label>
                                                <input type="date" class="form-control" name="forDate" placeholder="" required>
                                            </div>
                                        </div>  
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Holiday Name<span class="text-red">*</span>:</label>
                                                <input type="text" class="form-control" name="name" placeholder="Holiday Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Select Holiday Type<span class="text-red">*</span>:</label>
                                                {{Form::select('holidayType', ['1'=>'Paid Holiday','2'=>'UnPaid Holiday','3'=>'50% Paid Holiday'], NULL, ['placeholder'=>'Select Holiday Type','class'=>'form-control', 'id'=>'departmentId'])}}
                                            </div>
                                        </div>                                     
                                    </div>   

                                    <hr>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-5"></div>
                                            <div class="col-md-12 col-lg-3">
                                                @for($i=0; $i<count($branchIds); $i++)
                                                    <input type="hidden" value="{{$branchIds[$i]}}" name="branchIds[]">
                                                @endfor
                                                @for($k=0; $k<count($departmentIds); $k++)
                                                    <input type="hidden" value="{{$departmentIds[$k]}}" name="departmentIds[]">
                                                @endfor
                                                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            </div>
                                            <div class="col-md-12 col-lg-4"></div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
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
