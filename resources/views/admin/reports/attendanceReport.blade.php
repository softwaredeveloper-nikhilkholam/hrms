<?php
    use App\Helpers\Utility;
    $util = new Utility();

    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Attendance Report') {{-- Changed title for clarity --}}
@section('content')
<div class="container">
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">Attendance Report</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- Form for filtering and generating reports --}}
                    {!! Form::open(['action' => 'admin\ReportsController@attendanceReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                    <div class="row mt-5">
                        @if($userType != '61') {{-- Assuming userType '61' doesn't need report type selection --}}
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label" for="reportType">Report Type<span style="color:red;">*</span></label>
                                    <select class="form-control" id="reportType" name="report_type" required>
                                        <option selected value="">Select Type</option>
                                        <option value="Extra working Report" {{ old('report_type', $report_type ?? '') == 'Extra working Report' ? 'selected' : '' }}>Extra working Report</option>
                                        <option value="Absent Report" {{ old('report_type', $report_type ?? '') == 'Absent Report' ? 'selected' : '' }}>Absent Report</option>
                                        <option value="Single Punch Report" {{ old('report_type', $report_type ?? '') == 'Single Punch Report' ? 'selected' : '' }}>Single Punch Report</option>
                                        <option value="AGF Report" {{ old('report_type', $report_type ?? '') == 'AGF Report' ? 'selected' : '' }}>AGF Report</option>
                                        <option value="WL Report" {{ old('report_type', $report_type ?? '') == 'WL Report' ? 'selected' : '' }}>WL Report</option>
                                        <option value="Leave Report" {{ old('report_type', $report_type ?? '') == 'Leave Report' ? 'selected' : '' }}>Leave Report</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-2 col-lg-2">
                            <div class="form-group">
                                <label class="form-label" for="startMonth">Month<span style="color:red;">*</span>:</label>
                                <input type="month" id="startMonth" class="form-control" value="{{ old('start_month', $start_month ?? date('Y-m')) }}" name="start_month" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label class="form-label">Branches:</label>
                                {{-- Using old() to retain selected value after form submission --}}
                                {{Form::select('branchId', $branches, old('branchId', $branchId ?? null), ['placeholder'=>'Select Branch','class'=>'form-control'])}} {{-- Added required --}}
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <div class="form-group">
                                <label class="form-label" for="section">Section</label>
                                <select class="form-control" id="section" name="section">
                                    <option selected disabled value="">Select Section..</option>
                                    <option value="Teaching" {{ old('section', $section ?? '') == 'Teaching' ? 'selected' : '' }}>Teaching</option>
                                    <option value="Non Teaching" {{ old('section', $section ?? '') == 'Non Teaching' ? 'selected' : '' }}>Non Teaching</option>
                                </select>
                            </div>
                        </div>

                        {{-- Wrapper for WL Report Fields (Policy dropdown) --}}
                        <div class="col-md-2 col-lg-2" id="wl-report-fields" style="display: {{ old('report_type', $report_type ?? '') == 'WL Report' ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label class="form-label" for="policy">Policy</label>
                                <select class="form-control" id="policy" name="policy">
                                    <option selected disabled value="">Select Policy..</option>
                                    <option value="Latemarks Policy" {{ old('policy', $policy ?? '') == 'Latemarks Policy' ? 'selected' : '' }}>Latemarks Policy</option>
                                    <option value="Sandwitch Policy" {{ old('policy', $policy ?? '') == 'Sandwitch Policy' ? 'selected' : '' }}>Sandwitch Policy</option>
                                </select>
                            </div>
                        </div>

                        {{-- Wrapper for AGF Report Fields (Issue Type dropdown) --}}
                        <div class="col-md-2 col-lg-2" id="agf-report-fields" style="display: {{ old('report_type', $report_type ?? '') == 'AGF Report' ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label class="form-label" for="issueType">Issue Type</label>
                                <select class="form-control" id="issueType" name="issue_type">
                                    <option selected disabled value="">Select Issue..</option>
                                    <option value="Was working Out of Office Premises" {{ old('issue_type', $issue_type ?? '') == 'Was working Out of Office Premises' ? 'selected' : '' }}>Was working Out of Office Premises</option>
                                    <option value="was working in another branch where punching is not registered" {{ old('issue_type', $issue_type ?? '') == 'was working in another branch where punching is not registered' ? 'selected' : '' }}>was working in another branch where punching is not registered</option>
                                    <option value="Out for School Event / Workshop" {{ old('issue_type', $issue_type ?? '') == 'Out for School Event / Workshop' ? 'selected' : '' }}>Out for School Event / Workshop</option>
                                    <option value="Out for Competitions" {{ old('issue_type', $issue_type ?? '') == 'Out for Competitions' ? 'selected' : '' }}>Out for Competitions</option>
                                    <option value="At Outstationed Branch" {{ old('issue_type', $issue_type ?? '') == 'At Outstationed Branch' ? 'selected' : '' }}>At Outstationed Branch</option>
                                    <option value="Power Failure" {{ old('issue_type', $issue_type ?? '') == 'Power Failure' ? 'selected' : '' }}>Power Failure</option>
                                    <option value="Extra working on Holiday" {{ old('issue_type', $issue_type ?? '') == 'Extra working on Holiday' ? 'selected' : '' }}>Extra working on Holiday</option>
                                    <option value="Others" {{ old('issue_type', $issue_type ?? '') == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-lg-2">
                            <div class="form-group mt-5">
                                <input type="hidden" value="2" name="flag"> {{-- This 'flag' parameter seems specific to your existing system --}}
                                <button type="submit" class="btn btn-primary btn-block">Search</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                {{-- Display Summary Statistics --}}
                @if(isset($summaryStats) && $attendances && $attendances->count() > 0)
                <div class="card-body pt-0">
                    <h5 class="mb-4">Summary Statistics</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-primary">Total Employees</h6>
                                    <h3 class="mb-0 text-primary">{{ $summaryStats['total_employees'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-success">Total Present Days</h6>
                                    <h3 class="mb-0 text-success">{{ number_format($summaryStats['total_present'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-danger">Total Absent Days</h6>
                                    <h3 class="mb-0 text-danger">{{ number_format($summaryStats['total_absent'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-info">Total Extra Work Days</h6>
                                    <h3 class="mb-0 text-info">{{ number_format($summaryStats['total_extra_work'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="card bg-warning-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-warning">Total Late Mark Deductions</h6>
                                    <h3 class="mb-0 text-warning">{{ number_format($summaryStats['total_late_mark_deductions'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-secondary">Total Sandwich Deductions</h6>
                                    <h3 class="mb-0 text-secondary">{{ number_format($summaryStats['total_sandwitch_deductions'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-dark-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-dark">Total Weekly Rule Deductions</h6>
                                    <h3 class="mb-0 text-dark">{{ number_format($summaryStats['total_weekly_rule_deductions'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-purple-transparent">
                                <div class="card-body">
                                    <h6 class="mb-1 text-purple">Overall Total Deductions</h6>
                                    <h3 class="mb-0 text-purple">{{ number_format($summaryStats['total_deductions'], 1) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Attendance Table --}}
                @if(isset($attendances) && $attendances->count() > 0)
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="attendance-table">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">Emp Code</th>
                                    <th class="wd-15p border-bottom-0">Name</th>
                                    <th class="wd-20p border-bottom-0">Designation</th>
                                    <th class="wd-15p border-bottom-0">Total Present</th>
                                    <th class="wd-15p border-bottom-0">Total Absent</th>
                                    <th class="wd-15p border-bottom-0">Total Extra Work</th>
                                    <th class="wd-15p border-bottom-0">Final Total</th>
                                    <th class="wd-15p border-bottom-0">Late Mark Ded.</th>
                                    <th class="wd-15p border-bottom-0">Sandwich Ded.</th>
                                    <th class="wd-15p border-bottom-0">Weekly Rule Ded.</th>
                                    <th class="wd-15p border-bottom-0">Total Ded.</th>
                                    <th class="wd-15p border-bottom-0">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $employee)
                                <tr>
                                    <td>{{ $employee['info']->empCode }}</td>
                                    <td>{{ $employee['info']->name }}</td>
                                    <td>{{ $employee['info']->designationName }}</td>
                                    <td>{{ number_format($employee['totals']['present'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['absent'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['extra_work'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['final_total'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['late_mark_deductions'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['sandwitch_deductions'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['weekly_rule_deductions'], 1) }}</td>
                                    <td>{{ number_format($employee['totals']['total_deductions'], 1) }}</td>
                                    <td>{{ $employee['totals']['remark'] ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination Links --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @else
                {{-- Message if no records found --}}
                <div class="card-body">
                    <p class="text-center text-muted">No attendance records found for the selected criteria.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportTypeSelect = document.getElementById('reportType');
        const wlReportFields = document.getElementById('wl-report-fields');
        const agfReportFields = document.getElementById('agf-report-fields');
        const policySelect = document.getElementById('policy');
        const issueTypeSelect = document.getElementById('issueType');

        function togglePolicyFields() {
            const selectedReportType = reportTypeSelect.value;

            // Hide all conditional fields first
            wlReportFields.style.display = 'none';
            agfReportFields.style.display = 'none';

            // Reset select values to default when hidden
            policySelect.value = '';
            issueTypeSelect.value = '';

            // Show fields based on selected report type
            if (selectedReportType === 'WL Report') {
                wlReportFields.style.display = 'block';
            } else if (selectedReportType === 'AGF Report') {
                agfReportFields.style.display = 'block';
            }
        }

        // Initial call to set correct visibility on page load
        togglePolicyFields();

        // Add event listener for changes on the Report Type dropdown
        reportTypeSelect.addEventListener('change', togglePolicyFields);
    });
</script>
@endsection

<style>
    /* Basic styling for attendance statuses */
    .attend-P { background-color: #e6ffe6; } /* Present */
    .attend-PL { background-color: #ffffe6; } /* Present Late */
    .attend-PH { background-color: #ffe6cc; } /* Present Half */
    .attend-PLH { background-color: #ffe6b3; } /* Present Late Half */
    .attend-A { background-color: #ffe6e6; } /* Absent */
    .attend-0 { background-color: #f0f0f0; } /* Not Employed / No Data */
    .attend-WO { background-color: #e6f7ff; } /* Weekly Off */
    .attend-LH { background-color: #e6f7ff; } /* Leave/Holiday */
    .attend-H { background-color: #e6f7ff; } /* Holiday */

    /* Table styling for better readability */
    #attendance-table th, #attendance-table td {
        white-space: nowrap; /* Prevent text wrapping in cells */
        padding: 8px 12px;
        text-align: center;
        vertical-align: middle;
    }
    #attendance-table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    #attendance-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .table-responsive {
        max-height: 600px; /* Adjust as needed for scrollable table */
        overflow-y: auto;
        border: 1px solid #e9ecef; /* Add border to the responsive container */
    }
</style>
