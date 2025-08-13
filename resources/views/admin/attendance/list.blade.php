<?php
$userType = Auth::user()->userType;
?>
@extends('layouts.master')
@section('title', 'Final Attendance Management')
@section('content')
    <div class="container-fluid">
        {{-- Page Header and Filter Form --}}
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title" style="font-weight: 600;">Attendance Sheet</h4>
            </div>
        </div>
        <div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 10px; margin-bottom: 1.5rem;">
            <div class="card-header" style="background-color: transparent; border-bottom: 1px solid #e9ecef; font-weight: 600;">
                <h3 class="card-title"><i class="fa fa-filter mr-2 text-primary"></i>Select Criteria</h3>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => route('admin.searchAttendance'), 'method' => 'GET']) !!}
                    <div class="row align-items-end">
                        <div class="col-md-2 form-group">
                            <label>Month <span class="text-danger">*</span></label>
                            <input type="month" class="form-control" name="month" value="{{ $finalMonth ?? '' }}" required>
                        </div>
                        @if($userType == '11' || $userType == '21' || $userType == '31')
                            <div class="col-md-1 form-group">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search mr-1"></i> Search</button>
                            </div>
                        @else
                            <div class="col-md-2 form-group">
                                <label>Branch <span class="text-danger">*</span></label>
                                {{ Form::select('branchId', $branches, $branchId ?? null, ['placeholder'=>'Select Branch','class'=>'form-control']) }}
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Section <span class="text-danger">*</span></label>
                                {{ Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $section ?? null, ['placeholder'=>'All Sections','class'=>'form-control'])}}
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Employee Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="empCode" value="{{ $empCode ?? '' }}" placeholder="Search by Employee Name or Code...">
                            </div>
                              <div class="col-md-1 form-group">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search mr-1"></i> Search</button>
                            </div>
                        @endif
                    </div>
                {!! Form::close() !!}                 
            </div>
        </div>
        @if(isset($attendances) && $attendances->count() > 0)           
            <div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 10px; margin-bottom: 1.5rem;">
                <div class="card-body">
                    <h3><center>Summary of {{date('M-Y', strtotime(($finalMonth ?? '')))}} Days</center></h3>
                    <div class="row">
                        <div class="col-xl-2 col-md-6"><div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 8px; margin-bottom: 1.5rem; border-top: 4px solid #28a745;"><div class="card-body" style="position: relative;"><h6 class="text-muted mb-1">Total Employees</h6><span style="font-size: 2rem; font-weight: 700;">{{ $summaryStats['total_employees'] ?? 0 }}</span><i class="fa fa-user-check" style="font-size: 2.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i></div></div></div>
                        <div class="col-xl-2 col-md-6"><div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 8px; margin-bottom: 1.5rem; border-top: 4px solid #28a745;"><div class="card-body" style="position: relative;"><h6 class="text-muted mb-1">Total Present</h6><span style="font-size: 2rem; font-weight: 700;">{{ $summaryStats['total_present'] ?? 0 }}</span><i class="fa fa-user-check" style="font-size: 2.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i></div></div></div>
                        <div class="col-xl-2 col-md-6"><div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 8px; margin-bottom: 1.5rem; border-top: 4px solid #dc3545;"><div class="card-body" style="position: relative;"><h6 class="text-muted mb-1">Total Absent</h6><span style="font-size: 2rem; font-weight: 700;">{{ $summaryStats['total_absent'] ?? 0 }}</span><i class="fa fa-user-times" style="font-size: 2.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i></div></div></div>
                        <div class="col-xl-2 col-md-6"><div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 8px; margin-bottom: 1.5rem; border-top: 4px solid #ffc107;"><div class="card-body" style="position: relative;"><h6 class="text-muted mb-1">Total Deducted</h6><span style="font-size: 2rem; font-weight: 700;">{{ $summaryStats['total_deductions'] ?? 0 }}</span><i class="fa fa-cut" style="font-size: 2.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i></div></div></div>
                        <div class="col-xl-2 col-md-6"><div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 8px; margin-bottom: 1.5rem; border-top: 4px solid #28a745;"><div class="card-body" style="position: relative;"><h6 class="text-muted mb-1">Total Extra</h6><span style="font-size: 2rem; font-weight: 700;">{{ $summaryStats['total_extra_work'] ?? 0 }}</span><i class="fa fa-user-check" style="font-size: 2.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i></div></div></div>
                        <div class="col-xl-2 col-md-6"><div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 8px; margin-bottom: 1.5rem; border-top: 4px solid #28a745;"><div class="card-body" style="position: relative;"><h6 class="text-muted mb-1">Total Payable</h6><span style="font-size: 2rem; font-weight: 700;">{{ ($summaryStats['total_extra_work'] ?? 0) + ($summaryStats['total_present'] ?? 0) }}</span><i class="fa fa-user-check" style="font-size: 2.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i></div></div></div>
                    </div>                    
                        <div style="width: 100%;max-height: 75vh;overflow: auto;border: 1px solid #dee2e6;border-radius: 5px;">                                
                            <table class="table table-vcenter table-bordered border-top" style="font-size: 12px !important; border-spacing: 0;">
                                <thead style="position: sticky;top: 0;">
                                    <tr style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;">
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; left: 0; width: 10px;">#</th>
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: left !important; vertical-align: middle; padding: 12px 8px; padding-left: 6.50em !important;padding-right: 6.50em !important; left: 30px; width: 250px;">Employee</th>
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2; border-right: 1px solid #eee !important;position: sticky; top: 0; z-index: 2; background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 16px 10px; min-width: 140px;">{{ $day }}<br><small class="text-muted">{{ $carbonDate->copy()->day($day)->isoFormat('ddd') }}</small></th>
                                        @endfor
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; min-width: 70px; font-size: 11px;"><i class="fa fa-check text-success"></i><br>Present</th>
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; min-width: 70px; font-size: 11px;"><i class="fa fa-times text-danger"></i><br>Absent</th>
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; min-width: 70px; font-size: 11px;"><i class="fa fa-cut text-warning"></i><br>WL</th>
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; min-width: 70px; font-size: 11px;"><i class="fa fa-cut text-warning"></i><br>Extra</th>
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; min-width: 70px; font-size: 11px;"><i class="fa fa-star text-info"></i><br>Payable</th>
                                        <th style="position: -webkit-sticky;position: sticky;top: 0;z-index: 2;background: #f8f9fa; font-weight: 600; color: #495057; text-align: center; vertical-align: middle; padding: 12px 8px; min-width: 70px; font-size: 11px;"><i class="fa fa-cog"></i><br>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $index => $employee)
                                    @if($index % 2 == 0)
                                        <tr style="background-color:#f9f9f9;" class="{{ $employee['info']->finalSalaryStatus == 1 ? 'table-danger' : '' }}">
                                    @else
                                        <tr class="{{ $employee['info']->finalSalaryStatus == 1 ? 'table-danger' : '' }}">
                                    @endif
                                            <td class="font-weight-bold" style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word; position: sticky !important; z-index: 1; background: rgb(255, 255, 255) !important; left: 0px; width: 50px;">{{ $attendances->firstItem() + $index }}</td>
                                            <td style="text-align: left !important; vertical-align: middle; padding: 8px; word-wrap: break-word; position: sticky !important; z-index: 1; background: rgb(255, 255, 255) !important; left: 30px; width: 290px; padding-left: 15px !important;">
                                                <div><b style="color:green;font-size:16px;">{{ $employee['info']->empCode }}</b> - <b style="color:red;font-size:16px;">{{ $employee['info']->name }}</b></div>
                                                <b style="color:black;">{{$employee['info']->designationName}} [{{$employee['info']->branchName}}]</b><br>
                                                <b style="color:black;">DOJ: {{date('d-m-Y', strtotime($employee['info']->jobJoingDate))}}</b><b style='color:Red;'>{{($employee['info']->lastDate)?' To '.date('d-m-Y', strtotime($employee['info']->lastDate)):''}}</b><br>
                                                <b style="color:black;">Timing : {{date('H:i', strtotime($employee['info']->startTime))}} To {{date('H:i', strtotime($employee['info']->endTime))}}</b>
                                                @if($userType == '51' || $userType == '61' || $userType == '501')
                                                    <a class="btn btn-primary btn-sm" target="_blank" href="/empApplications/{{$employee['info']->empId}}/{{$finalMonth.'-01'}}/1/AGFShow">AGF List</a>
                                                @endif 
                                            </td>
                                             @php
                                                $baseBadgeStyle = "display: inline-flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 11px; height: 30px; width: 30px; border-radius: 50%; padding: 0;";
                                            @endphp
                                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                                @php $dayData = $employee['days'][$day] ?? null; @endphp
                                                <td style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;">
                                                    @if($dayData)
                                                        @if(in_array($dayData->status, ['P', 'PL', 'PH', 'PLH']))
                                                            <div style="font-size: 13px; line-height: 1.3; font-weight: 600;">
                                                                <div style="color:red;">{{ $dayData->officeInTime ? \Carbon\Carbon::parse($dayData->officeInTime)->format('H:i') : '--:--' }} - {{ $dayData->officeOutTime ? \Carbon\Carbon::parse($dayData->officeOutTime)->format('H:i') : '--:--' }}</div>
                                                                <div style="color: #28a745;">{{ $dayData->inTime ? \Carbon\Carbon::parse($dayData->inTime)->format('H:i') : '--:--' }} - {{ $dayData->outTime ? \Carbon\Carbon::parse($dayData->outTime)->format('H:i') : '--:--' }}</div>
                                                                <div style="color: #007bff;">{{ $dayData->deviceInTime ??'-' }} - {{ $dayData->deviceOutTime ??'-' }}</div>
                                                                <div style="font-weight: 700; color: #000; display: block; border-top: 1px solid #eee; border-bottom: 1px solid #eee; margin: 2px 0; padding: 1px 0;">
                                                                    @if($dayData->status == 'P')
                                                                            <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color: #28a745;">
                                                                    @elseif($dayData->status == 'PL')
                                                                            <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color: #ffc107;">
                                                                    @elseif($dayData->status == 'WO')
                                                                            <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color:purple;">
                                                                    @elseif($dayData->status == 'PH')
                                                                            <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color: #007bff;">
                                                                    @else
                                                                        <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            background-color: #dc3545;">
                                                                    @endif
                                                                        <b>{{ $dayData->status }}</b>
                                                                    </span>
                                                                </div>
                                                                <b style="color: #000; display: block;">{{ $dayData->workingHr ?? 'N/A' }} hrs.</b>
                                                            </div>
                                                        @else       
                                                                <div style="color:red;"><b>{{ $dayData->officeInTime ? \Carbon\Carbon::parse($dayData->officeInTime)->format('H:i') : '--:--' }} - {{ $dayData->officeOutTime ? \Carbon\Carbon::parse($dayData->officeOutTime)->format('H:i') : '--:--' }}</b></div>
                                                                <div style="color: #28a745;"><b>{{ $dayData->inTime ? \Carbon\Carbon::parse($dayData->inTime)->format('H:i') : '--:--' }} - {{ $dayData->outTime ? \Carbon\Carbon::parse($dayData->outTime)->format('H:i') : '--:--' }}</b></div>
                                                                <div style="color:rgb(57, 40, 167);"><b>{{ $dayData->deviceInTime ??'-' }} - {{ $dayData->deviceOutTime ??'-' }}</b></div>                                                    
                                                                    @if($dayData->status == 'P')
                                                                        <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;
                                                                            background-color: #28a745;">
                                                                    @elseif($dayData->status == 'PL')
                                                                        <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color: #ffc107;">
                                                                    @elseif($dayData->status == 'WO')
                                                                        <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color:purple;">
                                                                    @elseif($dayData->status == 'PH')
                                                                    <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            padding: 0;background-color: #007bff;">
                                                                    @else
                                                                        <span style="{{ $baseBadgeStyle }}
                                                                            display: inline-flex; 
                                                                            align-items: center; 
                                                                            justify-content: center; 
                                                                            font-weight: 700; 
                                                                            color: white; 
                                                                            font-size: 11px; 
                                                                            height: 30px; 
                                                                            width: 30px; 
                                                                            border-radius: 50%; 
                                                                            background-color: #dc3545;">
                                                                    @endif
                                                                <b>{{ $dayData->status == '0' ? 'A' : $dayData->status }}</b>
                                                            </span>
                                                        @endif
                                                        
                                                        @if($dayData->repAuthStatus != 0 || $dayData->HRStatus != 0 || $dayData->AGFStatus != 0)
                                                            <div style="font-size: 10px; white-space: nowrap; line-height: 1;" class="mt-1">
                                                                <i class="fa {{ $dayData->repAuthStatus != 0 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}" style="margin: 0 1px; cursor: help;" data-toggle="tooltip" title="Rep. Manager: {{ $dayData->repAuthStatus != 0 ? 'Approved' : 'Pending' }}[{{$dayData->AGFDayStatus}}]"></i>
                                                                <i class="fa {{ $dayData->HRStatus != 0 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}" style="margin: 0 1px; cursor: help;" data-toggle="tooltip" title="HR: {{ $dayData->HRStatus != 0 ? 'Approved' : 'Pending' }}[{{$dayData->AGFDayStatus}}]"></i>
                                                                <i class="fa {{ $dayData->AGFStatus != 0 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}" style="margin: 0 1px; cursor: help;" data-toggle="tooltip" title="Final AGF: {{ $dayData->AGFStatus != 0 ? 'Approved' : 'Pending' }}[{{$dayData->AGFDayStatus}}]"></i>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span style="display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; height: 30px; width: 30px; border-radius: 50%; padding: 0; background-color: #e9ecef; color: #6c757d;">-</span>
                                                    @endif
                                                </td>
                                            @endfor
                                            <td style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;"> <h5>{{ $employee['totals']['present'] }}<br> {{ ($employee['totals']['is_edited'])? $employee['totals']['new_present']:'' }} </h5></td>
                                            <td style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;"> <h5>{{ $employee['totals']['absent'] }}<br> {{ ($employee['totals']['is_edited'])? $employee['totals']['new_absent']:'' }}</h5></td>
                                            <td class="font-weight-bold text-danger" style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;" 
                                                data-toggle="tooltip" 
                                                data-html="true"
                                                title="Late Mark Ded: {{ $employee['totals']['late_mark_deductions'] }} <br> Sandwich Ded: {{ $employee['totals']['sandwitch_deductions'] }} <br> Weekly Rule Ded: {{ $employee['totals']['weekly_rule_deductions'] }}">
                                                <h5>{{ $employee['totals']['total_deductions'] }}<br> {{ ($employee['totals']['is_edited'])? $employee['totals']['new_wl']:'' }}</h5>
                                            </td>
                                            <td class="font-weight-bold text-primary" style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;"> <h5>{{ $employee['totals']['extra_work'] }} <br> {{ ($employee['totals']['is_edited'])? $employee['totals']['new_extra_work']:'' }}</h5></td>
                                            <td class="font-weight-bold text-primary" style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;"> <h5>{{ $employee['totals']['final_total'] }} <br> {{ ($employee['totals']['is_edited'])? $employee['totals']['new_final_total']:'' }}</h5></td>
                                            <td style="text-align: center; vertical-align: middle; padding: 8px; word-wrap: break-word;">
                                                <span class="badge badge-pill badge-{{ $employee['info']->finalSalaryStatus == 1 ? 'danger' : 'success' }} px-3 py-2">{{ $employee['info']->finalSalaryStatus == 1 ? 'Hold' : 'Release' }}</span>
                                                @if($userType == '501')
                                                    <button type="button" class="btn btn-warning btn-sm mt-1" 
                                                            data-toggle="modal" 
                                                            data-target="#editAttendanceModal{{ $employee['info']->attendEmpId }}"
                                                            data-empid="{{ $employee['info']->attendEmpId }}"
                                                            data-name="{{ $employee['info']->name }}"
                                                            data-present="{{ $employee['totals']['present'] }}"
                                                            data-absent="{{ $employee['totals']['absent'] }}"
                                                            data-wl="{{ $employee['totals']['total_deductions'] }}"
                                                            data-extra="{{ $employee['totals']['extra_work'] }}">
                                                        Edit
                                                    </button>
                                                    <div class="modal fade" id="editAttendanceModal{{ $employee['info']->attendEmpId }}" tabindex="-1" role="dialog" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                {{-- Make sure you have a route named 'admin.attendance.update-single' or similar --}}
                                                                {!! Form::open(['url' => route('admin.attendance.updateFinalAttendance'), 'method' => 'POST']) !!}
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance for: <strong id="modal-employee-name" class="text-primary">{{ $employee['info']->empCode }} - {{ $employee['info']->name }}</strong></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="empId" value="{{ $employee['info']->attendEmpId }}" id="modal-attend-emp-id">
                                                                    <input type="hidden" name="finalMonth" value="{{ $finalMonth ?? '' }}">
                                                                    <div class="row">
                                                                        <div class="col-md-6 form-group">
                                                                            <label for="modal-present-days">Present Days<span color="color:red;">*</span></label>
                                                                            <input type="number" step="0.5" value="{{ $employee['totals']['present'] }}"  class="form-control modal-calc-input" id="modal-present-days" name="presentDays" required>
                                                                        </div>
                                                                        <div class="col-md-6 form-group">
                                                                            <label for="modal-absent-days">Absent Days<span color="color:red;">*</span></label>
                                                                            <input type="number" step="0.5" value="{{ $employee['totals']['absent'] }}" class="form-control" id="modal-absent-days" name="absentDays" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 form-group">
                                                                            <label for="modal-wl-days">WL (Deductions)<span color="color:red;">*</span></label>
                                                                            <input type="number" step="0.5" value="{{ $employee['totals']['total_deductions'] }}"  class="form-control" id="modal-wl-days" name="WLDays" required>
                                                                        </div>
                                                                        <div class="col-md-6 form-group">
                                                                            <label for="modal-extra-days">Extra Days<span color="color:red;">*</span></label>
                                                                            <input type="number" step="0.5" value="{{ $employee['totals']['extra_work'] }}"  class="form-control modal-calc-input" id="modal-extra-days" name="extraWorkDays" required>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-md-6 form-group">
                                                                            <label for="modal-wl-days">Payble Days<span color="color:red;">*</span></label>
                                                                            <input type="number" step="0.5" value="{{ $employee['totals']['final_total'] }}"  class="form-control" id="modal-wl-days" name="payableDays" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 form-group">
                                                                            <label for="modal-wl-days">Remark<span color="color:red;">*</span></label>
                                                                            <input type="text" value="" placeholder="Remark"  class="form-control" id="modal-wl-days" name="remark" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" value="{{ $employee['totals']['present'] }}" name="oldPresentDays">
                                                                    <input type="hidden" value="{{ $employee['totals']['absent'] }}" name="oldAbsentDays">
                                                                    <input type="hidden" value="{{ $employee['totals']['total_deductions'] }}" name="oldWLDays">
                                                                    <input type="hidden" value="{{ $employee['totals']['extra_work'] }}" name="oldExtraWorkDays">
                                                                    <input type="hidden" value="{{ $employee['totals']['final_total'] }}" name="oldPayableDays">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                                </div>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="{{ $daysInMonth + 8 }}" class="text-center py-5"><i class="fa fa-inbox fa-3x text-muted mb-3"></i><h4>No records found.</h4><p>Please try adjusting your search filters.</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        @elseif(request()->filled('branchId'))
            <div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 10px; margin-bottom: 1.5rem;"><div class="card-body text-center py-5"><i class="fa fa-inbox fa-3x text-muted mb-3"></i><h4>No records found.</h4><p class="text-muted">Please try adjusting your filters.</p></div></div>
        @endif
    </div>
   
@endsection

@push('scripts')
<script>
$(function () {
    // Initialize existing tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // --- MODAL TRIGGER SCRIPT ---
    // This script runs when any edit button is clicked
    $('#editAttendanceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // The button that triggered the modal
        var modal = $(this);

        // Extract info from the button's data-* attributes
        var empId = button.data('empid');
        var name = button.data('name');
        var present = button.data('present');
        var absent = button.data('absent');
        var wl = button.data('wl');
        var extra = button.data('extra');

        // Update the modal's content with the data from the specific row
        modal.find('#modal-employee-name').text(name);
        modal.find('#modal-attend-emp-id').val(empId);
        modal.find('#modal-present-days').val(present);
        modal.find('#modal-absent-days').val(absent);
        modal.find('#modal-wl-days').val(wl);
        modal.find('#modal-extra-days').val(extra);

        // Manually trigger the calculation function when the modal opens
        calculatePayableInModal();
    });

    // --- AUTO-CALCULATION SCRIPT FOR MODAL ---
    // This script runs whenever an input in the modal is changed
    $('.modal-calc-input').on('keyup change', function() {
        calculatePayableInModal();
    });

    function calculatePayableInModal() {
        // Get the current values from the modal's input fields
        var present = parseFloat($('#modal-present-days').val()) || 0;
        var extra = parseFloat($('#modal-extra-days').val()) || 0;
        
        // Calculation: Payable days = Present Days + Extra Days
        var payableTotal = present + extra;

        // Display the calculated total in the modal
        $('#modal-payable-total').text(payableTotal.toFixed(2));
    }
});
</script>
@endpush