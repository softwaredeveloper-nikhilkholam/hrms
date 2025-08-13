@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Change Office Time</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employees/changeTimeList" class="btn btn-primary mr-3">List</a>
                            <a href="/employees/changeTime" class="btn btn-success mr-3">Update Time</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Change Office Time</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\employees\EmployeesController@changeTime', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code :</label>
                                            <input type="text" class="form-control" name="employeeCode"  value="" id="employeeCode" placeholder="Employee Code">
                                        </div>
                                    </div>
                                   <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Branch :</label>
                                            {{Form::select('branchId', $branches, null, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <div class="form-group">
                                            <label class="form-label">Department :</label>
                                            {{Form::select('departmentId', $departments, null, ['placeholder'=>'Pick a Option','class'=>'form-control empDepartmentId','style'=>'color:red;', 'id'=>'empDepartmentId'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <div class="form-group">
                                            <label class="form-label">Designation :</label>
                                            {{Form::select('designationId', [], null, ['placeholder'=>'Pick a Option','class'=>'form-control empDesignationId','style'=>'color:red;', 'id'=>'empDesignationId'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            <style>
                                .row-selected {
                                    background-color: #fff3cd !important;
                                }
                            </style>
                            @if(isset($employees))
                                @if(count($employees) >= 1)
                                    {!! Form::open(['action' => 'admin\employees\EmployeesController@updateChangeTime', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">From Date&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="date" class="form-control" name="fromDate" id="fromDate" placeholder="Monthly Deduction" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">To Date&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="date" class="form-control" name="toDate" id="toDate" placeholder="Date From Deduction" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2"  id="">
                                                <div class="form-group">
                                                    <label class="form-label">In Time&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="time" class="form-control" name="fromTime" id="fromTime" placeholder="Monthly Deduction" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2"  id="">
                                                <div class="form-group">
                                                    <label class="form-label">Out Time:&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    <input type="time" class="form-control" name="toTime" id="toTime" placeholder="Date From Deduction" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Time Type&nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                    {{Form::select('masters', ['0'=>'None', '1'=>'Change in Master', '2'=>'Time Fetch from Masters'], null, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;', 'required'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Remarks&nbsp;<span class="text-red" style="font-size:22px;"></span> &nbsp;<span class="text-red" style="font-size:22px;"></span>:</label>
                                                    <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Remarks" required>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h3>Search Results: Employees Matching Your Selection</h3>
                                        <div class="table-responsive">
                                            <table class="table table-vcenter text-nowrap table-bordered border-bottom border-top border-left border-right" id="newHR-table" style="height:100px;">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0 w-5">#</th>
                                                        <th class="border-bottom-0" width="10%">Emp Code</th>
                                                        <th class="border-bottom-0">Name</th>
                                                        <th class="border-bottom-0" width="10%">Department</th>
                                                        <th class="border-bottom-0" width="10%">Designation</th>
                                                        <th class="border-bottom-0" width="10%">OFfice Time</th>
                                                        <th class="border-bottom-0" width="10%">Branch<?php $i=1; ?></th>
                                                        <th class="border-bottom-0" width="10%">Action <input type="checkbox" class="form-control" id="select-all"></th>
                                                    </tr>
                                                    <tr class="filter-row">
                                                        <td></td> {{-- Empty cell for the '#' column --}}
                                                        <td>
                                                            <input type="text" id="empCodeSearch" class="form-control form-control-sm" placeholder="Search Code...">
                                                        </td>
                                                        <td><input type="text" id="empNameSearch" class="form-control form-control-sm" placeholder="Search Employee Name..."></td> {{-- Empty cell for Name --}}
                                                        <td></td> {{-- Empty cell for Department --}}
                                                        <td></td> {{-- Empty cell for Designation --}}
                                                        <td></td> {{-- Empty cell for Office Time --}}
                                                        <td></td> {{-- Empty cell for Branch --}}
                                                        <td></td> {{-- Empty cell for Action --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($employees as $row)
                                                        {{-- The row will get the .row-selected class added/removed by the script --}}
                                                        <tr>
                                                            <td><b>{{$i++}}</b></td>
                                                            <td><b>{{$row->empCode}}</b></td>
                                                            <td><b>{{$row->name}}</b></td>
                                                            <td><b>{{$row->departmentName}}</b></td>
                                                            <td><b>{{$row->designationName}}</b></td>
                                                            <td><b>{{date('H:i', strtotime($row->startTime))}} To {{date('H:i', strtotime($row->endTime))}}</b></td>
                                                            <td><b>{{$row->branchName}}</b></td>
                                                            <td><input type="checkbox" class="form-control employee-checkbox" name="empCode[]" value="{{$row->empCode}}"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group mt-6">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-5"></div>
                                                <div class="col-md-12 col-lg-3">
                                                    <input type="hidden" name="searchEmployeeCode" value="{{$employeeCode}}">
                                                    <input type="hidden" name="searchBranchId" value="{{$branchId}}">
                                                    <input type="hidden" name="searchDepartmentId" value="{{$departmentId}}">
                                                    <input type="hidden" name="searchDesignationId" value="{{$designationId}}">
                                                    <button type="submit" class="btn btn-primary btn-lg">Change Time</button>
                                                </div>
                                                <div class="col-md-12 col-lg-4"></div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                @else
                                    <h4 style="color:red;">Not Found Active Records.</h4>
                                @endif
                            @endif

                            {{-- Step 2: Add the JavaScript --}}
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                            <script>
                            $(document).ready(function() {

                                $('#empCodeSearch').on('keyup', function() {
                                    // Get the search value from the 'Emp Code' input box
                                    var searchValue = $(this).val().toLowerCase();

                                    // Loop through each row in the table body
                                    $('#newHR-table tbody tr').each(function() {
                                        // Get the text from the SECOND table cell (td) in the current row.
                                        // :eq(1) is used because the index starts at 0.
                                        var cellText = $(this).find('td:eq(1)').text().toLowerCase();

                                        // Check if the cell text contains the search value
                                        if (cellText.indexOf(searchValue) > -1) {
                                            // If it matches, show the row
                                            $(this).show();
                                        } else {
                                            // If it doesn't match, hide the row
                                            $(this).hide();
                                        }
                                    });
                                });

                                $('#empNameSearch').on('keyup', function() {
                                    // Get the search value from the 'Emp Code' input box
                                    var searchValue = $(this).val().toLowerCase();

                                    // Loop through each row in the table body
                                    $('#newHR-table tbody tr').each(function() {
                                        // Get the text from the SECOND table cell (td) in the current row.
                                        // :eq(1) is used because the index starts at 0.
                                        var cellText = $(this).find('td:eq(2)').text().toLowerCase();

                                        // Check if the cell text contains the search value
                                        if (cellText.indexOf(searchValue) > -1) {
                                            // If it matches, show the row
                                            $(this).show();
                                        } else {
                                            // If it doesn't match, hide the row
                                            $(this).hide();
                                        }
                                    });
                                });

                                // When the header 'select-all' checkbox is clicked
                                $('#select-all').on('click', function() {
                                    var isChecked = $(this).prop('checked');
                                    
                                    $('.employee-checkbox').each(function() {
                                        $(this).prop('checked', isChecked);
                                        $(this).closest('tr').toggleClass('row-selected', isChecked);
                                    });
                                });

                                // When any individual employee checkbox is clicked
                                $('.employee-checkbox').on('click', function() {
                                    $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));

                                    if ($('.employee-checkbox:checked').length == $('.employee-checkbox').length) {
                                        $('#select-all').prop('checked', true);
                                    } else {
                                        $('#select-all').prop('checked', false);
                                    }
                                });

                            });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
