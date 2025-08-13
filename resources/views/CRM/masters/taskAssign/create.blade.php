<?php
    $userRole = Session()->get('userRole');
?>
@extends('CRM.layouts.master')
@section('title', 'CRM')

{{-- This section pushes the required CSS to your master layout's <head> --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style for validation error messages */
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }
    </style>
@endpush
@section('content') 
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4 text-left">
                    <h5 class="card-title">Add New Check</h5>
                </div>
                <div class="col-md-8 text-left" style="text-align: right;">
                    <a href="/CRM/assignTaskSheet/assignTask" class="btn btn-success text-right">Add</a>
                    <a href="/CRM/assignTaskSheet/assignDeactiveTaskList" class="btn btn-primary text-right">Deactive List</a>
                    <a href="/CRM/assignTaskSheet/assignTaskList" class="btn btn-primary text-right">Active List</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'CRM\MasterChecklistController@updateAssignTask', 'method' => 'POST', 'class' => 'form-horizontal form-row row']) !!}
                
                {{-- NEW: General Error Message Box --}}
                @if ($errors->any())
                    <div class="col-12 mb-3">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                
                {{-- Task Dropdown --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="taskId">Task</label>
                    {{Form::select('taskId', $taskList, NULL, ['class'=>'form-control select2-searchable', 'id'=>'taskId', 'placeholder'=>'Select Task'])}}
                    
                    {{-- NEW: Validation message for taskId --}}
                    @error('taskId')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                {{-- Employee Dropdown --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="empId">Employee</label>
                    {{Form::select('empId', $employees, NULL, ['class'=>'form-control select2-searchable', 'id'=>'empId', 'placeholder'=>'Select Employee', 'style'=>'color:red;font-size:16px;'])}}
                    
                    {{-- NEW: Validation message for empId --}}
                    @error('empId')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                {{-- Submit Buttons --}}
                <div class="col-12 mt-3 text-center">
                    <button type="submit" class="btn btn-success btn-lg">Save</button>
                    <a href="/departments" class="btn btn-danger btn-lg">Cancel</a>
                </div>
                
            {!! Form::close() !!}
        </div>
    </div>
@endsection

{{-- This section pushes the required JavaScript to the end of your master layout's <body> --}}
@push('scripts')
    {{-- 1. jQuery (required by Select2) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    {{-- 2. Select2 JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- 3. Initialize Select2 on your dropdowns --}}
    <script>
        $(document).ready(function() {
            // Apply the search functionality to any dropdown with the 'select2-searchable' class
            $('.select2-searchable').select2({
                width: '100%' // Ensures the dropdown fits its container
            });
        });
    </script>
@endpush