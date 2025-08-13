<?php
    $userRole = Session()->get('userRole');
?>
@extends('CRM.layouts.master')
@section('title', 'CRM')
@section('content') 
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4 text-left">
                <h5 class="card-title">Assigned Task Details</h5>
            </div>
            <div class="col-md-8 text-left" style="text-align: right;">
                <a href="/CRM/extraTask/requestList" class="btn btn-primary text-right">Request List</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['action' => 'CRM\ExtraWorksController@assignedTask', 'method' => 'POST', 'class' => 'form-horizontal form-row row']) !!}
            <div class="col-md-4 mb-3">
                <label class="form-label" for="validationTooltip01">Task Title/Sub</label>
                <input class="form-control" name="title" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="birthDate">Deadline Date</label>
                <input class="form-control @error('AssignedDate') is-invalid @enderror" id="AssignedDate" name="AssignedDate" type="date" value="{{ old('AssignedDate') }}" readonly>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label" for="validationTooltip02">Task Discription / Details</label>
                <textarea class="form-control" name="task" rows="6" cols="3" placeholder="Task" readonly></textarea>
            </div>
    <div class="card-header">
        <div class="row">
            <div class="col-md-4 text-left">
                <h5 class="card-title">Assigned Task To</h5>
            </div>
        </div>
    </div>
            <div class="row mt-3">
            <div class="col-md-4">
                <label class="form-label" for="validationTooltip01">Assigned To</label>
                {{Form::select('departmentId', [], NULL, ['class'=>'form-control empDepartmentId', 'id'=>'empDepartmentId', 'placeholder'=>'Emloyee Name', 'required'])}}
            </div>
            <div class="col-md-2 col-lg-2"> <br>
                <button type="submit" class="btn btn-success btn-lg">View</button>
            </div>
            </div>
        <div class="row mt-3">

            <div class="col-md-4">
                <label class="form-label" for="birthDate">Deadline Date</label>
                <input class="form-control @error('AssignedDate') is-invalid @enderror" id="AssignedDate" name="AssignedDate" type="date" value="{{ old('AssignedDate') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="timeOfBirth">Deadline Time </label>
                <input class="form-control" id="timeOfBirth" name="timeOfBirth" type="time" value="{{ old('timeOfBirth') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="Priority">Status</label>
                <select class="form-select" id="Priority" name="Priority">
                 <option selected disabled value="">Choose..</option>
                 <option value="Completed" {{ old('havingSpecsLenses') == 'Yes' ? 'selected' : '' }}>Completed</option>
                 <option value="Pending" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Pending</option>
                 <option value="Hold" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Hold</option>
                 <option value="Accept" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Accept</option>
                 <option value="Reject" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Reject</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="birthDate">Attachment (If Any)</label>
                <input class="form-control @error('file') is-invalid @enderror" id="file" name="File" type="File" value="{{ old('file') }}">
            </div>
            </div>

            
            <div class="form-group">
                <br>
                <div class="row">
                    <div class="col-md-12 col-lg-5"></div>
                    <div class="col-md-12 col-lg-3">
                        <button type="submit" class="btn btn-success btn-lg">Save</button>
                        <a href="/departments" class="btn btn-danger btn-lg">Cancel</a>
                    </div>
                    <div class="col-md-12 col-lg-4"></div>
                </div>
            </div>
         {!! Form::close() !!}
    </div>
</div>
@endsection