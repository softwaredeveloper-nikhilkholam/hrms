<?php
$userType = Auth::user()->userType;
?>
@extends('CRM.layouts.master')
@section('title', 'CRM - Previous Checklists')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4 text-left">
                <h5 class="card-title">Employee's Checklists</h5>
            </div>
            <div class="col-md-8 text-right" style="text-align: right;">
                <a href="{{ route('checklist.create') }}" class="btn btn-success text-right">Today's Checklist</a>
                <a href="{{ route('checklist.index') }}" class="btn btn-primary text-right">Previous Checklists</a>
                    <a href="{{ route('checklist.employeesList') }}" class="btn btn-primary text-right">Employees Checklists</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee Name</th>
                            <th>Total Tasks</th>
                            <th>Completed</th>
                            <th>Pending</th>
                            <th>Actions</th> {{-- ✨ New Column --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($taskHistory as $daySummary)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($daySummary->forDate)->format('d M, Y') }}
                                </td>
                                <td>{{ $daySummary->employee_name }}</td>
                                <td>{{ $daySummary->total_count }}</td>
                                <td class="text-success">{{ $daySummary->completed_count }}</td>
                                <td class="text-warning">{{ $daySummary->pending_count }}</td>
                                <td>
                                    {{-- ✨ Update the link to pass the employee ID --}}
                                    <a href="/CRM/dailyTaskList/showDetails/{{$daySummary->empId}}/{{$daySummary->forDate}}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            

            {{-- Pagination Links --}}
            <div class="mt-3">
                {{ $taskHistory->links() }}
            </div>
        </div>
    </div>
</div>
@endsection