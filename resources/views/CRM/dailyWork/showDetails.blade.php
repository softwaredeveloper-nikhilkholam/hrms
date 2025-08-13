@extends('CRM.layouts.master')
@section('title', 'CRM - Checklist Details')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            {{-- Dynamic title showing the date being viewed --}}
            <div class="col-md-6">
                <h5 class="card-title mb-0">
                    Tasks for {{ \Carbon\Carbon::parse($taskDate)->format('F j, Y') }}
                </h5>
            </div>
            {{-- Back button to return to the main summary list --}}
            <div class="col-md-6 text-end">
                <a href="{{ route('checklist.index', request()->only('empId')) }}" class="btn btn-secondary">
                    &larr; Back to Summary
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Description</th>
                        <th>Status</th>
                        <th>Time Created</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through the tasks for the selected date --}}
                    @forelse ($tasks as $task)
                        <tr>
                            {{-- Use a placeholder if the description is empty --}}
                            <td>{{ $task->task ?? 'No description provided' }}</td>
                            <td>
                                {{-- Display a styled badge based on the task status --}}
                                @if($task->status == 'Completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            {{-- Format the timestamp to show only the time --}}
                            <td>{{ $task->updated_at->format('h:i A') }}</td>
                        </tr>
                    @empty
                        {{-- This row shows if no tasks are found for the criteria --}}
                        <tr>
                            <td colspan="4" class="text-center">No tasks found for this date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{-- Render the pagination links for the tasks --}}
        {{ $tasks->links() }}
    </div>
</div>
@endsection