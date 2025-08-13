{{-- Logic like this should be handled in a controller or middleware, not the view.
     Use Blade's @can directive for checking user permissions.
--}}
@extends('CRM.layouts.master')
@section('title', 'CRM - Previous Checklists')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h5 class="card-title mb-0">Previous Checklists</h5>
            </div>
            {{-- Use Bootstrap's 'text-end' class for alignment --}}
            <div class="col-md-8 text-end">
                {{-- Always use the route() helper for URLs for better maintainability --}}
                <a href="{{ route('checklist.create') }}" class="btn btn-success">Today's Checklist</a>
                <a href="{{ route('checklist.index') }}" class="btn btn-primary">My Previous Checklists</a>
                {{-- Use @can directive to check user permissions/roles securely --}}
                    <a href="{{ route('checklist.employeesList') }}" class="btn btn-info">All Employees' Checklists</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Total Tasks</th>
                        <th class="text-center">Completed</th>
                        <th class="text-center">Pending</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($taskHistory as $daySummary)
                        <tr>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($daySummary->forDate)->format('d M, Y') }}
                            </td>
                            <td class="text-center align-middle">
                                {{-- Styled badge for total count --}}
                                <span class="badge fs-15 bg-primary">{{ $daySummary->total_count }}</span>
                            </td>
                            <td class="text-center align-middle">
                                {{-- Styled badge for completed count --}}
                                <span class="badge fs-15 bg-success">{{ $daySummary->completed_count }}</span>
                            </td>
                            <td class="text-center align-middle">
                                {{-- Styled badge for pending count --}}
                                <span class="badge fs-15 bg-warning text-dark">{{ $daySummary->pending_count }}</span>
                            </td>
                            <td class="text-center align-middle">
                                {{-- Use route() helper and pass parameters correctly --}}
                                 <a href="/CRM/dailyTaskList/showDetails/{{$daySummary->empId}}/{{$daySummary->forDate}}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-4">No history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination Links --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $taskHistory->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
