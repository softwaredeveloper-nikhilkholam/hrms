@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Employee Policy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="#" class="btn btn-success mr-3">Archive List</a>
                            <a href="/appointments/create" class="btn btn-primary mr-3">Get Appointment</a>
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
                            <h4 class="card-title">Employee Policy</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmpAttendancesController@updateEmpPolicy', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code</label> 
                                            <input type="text" pattern="^[0-9]*$" name="empCode" value="" placeholder="Employee Code" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Branch</label> 
                                            {{Form::select('branchId', $branches, null, ['placeholder'=>'Pick a Branch','class'=>'form-control','style'=>'color:red;'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Section</label> 
                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], null, ['placeholder'=>'Pick a Section','class'=>'form-control policySectionId', 'id'=>'policySectionId','style'=>'color:red;'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Department</label> 
                                            {{Form::select('departmentId', $departments, null, ['placeholder'=>'Pick a Department','class'=>'form-control policyDepartmentId', 'id'=>'policyDepartmentId', 'style'=>'color:red;'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Designation</label> 
                                            {{Form::select('designationId', $designations, null, ['placeholder'=>'Pick a Designation','class'=>'form-control policyDepartmentId', 'id'=>'policyDesignationId','style'=>'color:red;'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">From</label> 
                                            <input type="date" name="fromDate" value="" id="fromDate" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">To</label> 
                                            <input type="date" name="toDate" value="" id="toDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0" width="10%">No</th>
                                                <th class="border-bottom-0">Policy Name</th>
                                                <th class="border-bottom-0" width="10%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size:15px;">
                                            <tr>
                                                <td>1</td>
                                                <td>Weekly off( Sunday and Holiday ) Paid.</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol1" name="pol1" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>If an employee remains absent for 3 days of the same week. Only Half Day will be consideredas paid weekly off and half day payment will be deducted.</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol2" name="pol2" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>3rd Saturday as official off.</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol3" name="pol3" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>15th August attendance is compulsory. Failure to attend will lead to deduction of 3 days.</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol4" name="pol4" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>26th January attendance is compulsory. Failure to attend will lead to deduction of 3 days.</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol6" name="pol6" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Sandwich Leave Policy - If you take a leave on saturday and monday your Sunday will also consider your 3 leave?</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol6" name="pol6" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Late Mark Allowed</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol7" name="pol7" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Travelling Allowance Allowed</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol8" name="pol8" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Extra Working Day Salary</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol9" name="pol9" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Night Shift Allowed</td>
                                                <td>
                                                    <input type="checkbox" class="form-control" style="height:30px;width:30px;" id="pol10" name="pol10" value="1">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-2">
                                            <input type="submit" class="btn btn-danger btn-lg mb-3" value="Save">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="reset" class="btn btn-warning btn-lg mb-3" value="Cancel">
                                        </div>
                                        <div class="col-md-4"></div>
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
