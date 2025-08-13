@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Board Notice</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/notices" class="btn btn-primary mr-3">Upcomming List</a>
                            <a href="/notices/dlist" class="btn btn-primary mr-3">Archive List</a>
                            <a href="/notices/deletedList" class="btn btn-danger mr-3">Deleted List</a>
                            <a href="/notices/create" class="btn btn-success mr-3">Add Board Notice</a>
                            
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
                            <h4 class="card-title">Edit Notice</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => ['admin\NoticesController@update', $notice->id], 'method' => 'POST']) !!}
                                <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Branch:</label>
                                        {{Form::select('branchId', $branches, $notice->branchId, ['placeholder'=>'Select Branch','class'=>'branchId form-control', 'id'=>'branchId'])}}
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Select Departments:</label>.
                                        <select class="form-control" name="" multiple="multiple">
                                            <option value="">Select Department</option>
                                            <?php $selectedDepartmentId = explode(',', $notice->departmentId);?>
                                            @foreach($departments as $department)
                                                <option value="{{$department->id}}" <?php echo (in_array($department->id, $selectedDepartmentId))?'selected':''; ?> >{{$department->name}}</option> 
                                            @endforeach                                        
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">From Date<span class="text-red">*</span>:</label>
                                        <input type="date" class="form-control" name="fromDate" value="{{$notice->fromDate}}" placeholder="" required>
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">To Date<span class="text-red">*</span>:</label>
                                        <input type="date" class="form-control" name="toDate" value="{{$notice->toDate}}" placeholder="" required>
                                    </div>
                                </div> 
                            </div> 
                            <div class="row"> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Title<span class="text-red">*</span>:</label>
                                        <input type="text" class="form-control" name="title" value="{{$notice->title}}" placeholder="Title" required>
                                    </div>
                                </div>     
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Notice<span class="text-red">*</span>:</label>
                                        <textarea class="form-control" name="description" placeholder="Notice Board" cols="10" required>{{$notice->description}}</textarea>
                                    </div>
                                </div>                                  
                            </div> 
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            {{Form::hidden('_method', 'PUT')}}
                                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure?')">Update</button>
                                            <a href="/notices" class="btn btn-danger btn-lg">Cancel</a>
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
