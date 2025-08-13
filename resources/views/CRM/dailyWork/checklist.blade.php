<?php
$userType = Auth::user()->userType;
?>
@extends('CRM.layouts.master')
@section('title', 'CRM')

@push('styles')
    {{-- Style for validation error messages --}}
    <style>
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4 text-left">
                <h5 class="card-title">Check List</h5>
            </div>
            <div class="col-md-8 text-left" style="text-align: right;">
                <a href="{{ route('checklist.create') }}" class="btn btn-success text-right">Today's Checklist</a>
                <a href="{{ route('checklist.index') }}" class="btn btn-primary text-right">Previous Checklist</a>
                    <a href="{{ route('checklist.employeesList') }}" class="btn btn-primary text-right">Employees Checklists</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i data-feather="calendar" class="feather-calendar me-2"></i>
                        <h3>Today</h3>
                        <h6 class="ms-2 badge bg-primary">{{count($taskLists)}}</h6>
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="todo-widget">
                            {!! Form::open(['route' => 'checklist.store', 'method' => 'POST']) !!}
                                @csrf
                                
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <p class="mb-0 fw-bold">Please fix the following errors:</p>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @foreach($taskLists as $index => $taskList)
                                    @php
                                        // Get the previously saved entry for this task, if it exists
                                        $savedEntry = $todaysEntries[$taskList->taskId] ?? null;
                                        // Determine the status: use old input first, then saved data, then default to 'Pending'
                                        $status = old('tasks.'.$index.'.status', $savedEntry->status ?? 'Pending');
                                        // Determine the remark: use old input first, then saved data
                                        $remark = old('tasks.'.$index.'.remark', $savedEntry->remark ?? '');
                                    @endphp

                                    <div class="todo-wrapper-list" id="task-row-{{ $index }}">
                                        <input type="hidden" value="{{$taskList->taskId}}" name="tasks[{{$index}}][taskId]">
                                        
                                        <div class="row w-100 align-items-center mb-2">
                                            <div class="col-md-7 d-flex align-items-center">
                                                <span class="h5 me-3">{{$loop->iteration}}.</span>
                                                <div class="todo-wrapper-list-content">
                                                    <h6>{{$taskList->task}}</h6>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <select class="form-select status-dropdown" name="tasks[{{$index}}][status]">
                                                    <option value="Pending" @if($status == 'Pending') selected @endif>Pending</option>
                                                    <option value="Completed" @if($status == 'Completed') selected @endif>Completed</option>
                                                    <option value="On Hold" @if($status == 'On Hold') selected @endif>On Hold</option>
                                                    <option value="Not Applicable" @if($status == 'Not Applicable') selected @endif>Not Applicable</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3 remark-field">
                                                <textarea name="tasks[{{$index}}][remark]" class="form-control @error('tasks.'.$index.'.remark') is-invalid @enderror" placeholder="Enter remark..." rows="2">{{ $remark }}</textarea>
                                                @error('tasks.'.$index.'.remark')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-4 text-center">
                                    <button type="submit" class="btn btn-success">Save Checklist</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    $(function() {
        feather.replace();

        $('.status-dropdown').on('change', function() {
            var taskRow = $(this).closest('.todo-wrapper-list');
            var remarkField = taskRow.find('.remark-field');

            if ($(this).val() === 'On Hold') {
                remarkField.slideDown();
            } else {
                remarkField.slideUp(function() {
                    $(this).find('textarea').val(''); 
                });
            }
        });
    });
</script>
@endpush
