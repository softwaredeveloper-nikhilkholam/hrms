
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
                <h5 class="card-title">Add New Task</h5>
            </div>
            <div class="col-md-8 text-left" style="text-align: right;">
                <a href="/CRM/masterchecklist/create" class="btn btn-success text-right">Add</a>
                <a href="/CRM/masterchecklist/dlist" class="btn btn-primary text-right">Deactive List</a>
                <a href="/CRM/masterchecklist" class="btn btn-primary text-right">Active List</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['action' => 'CRM\MasterChecklistController@store', 'method' => 'POST', 'class' => 'form-horizontal form-row row']) !!}
            <div class="col-md-3 mb-3">
                <label class="form-label" for="validationTooltip01">Branch</label>
                {{Form::select('branchId', $branches, NULL, ['class'=>'form-control', 'id'=>'branchId', 'placeholder'=>'Select Branch','required'])}}
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="validationTooltip01">Department</label>
                {{Form::select('departmentId', $departments, NULL, ['class'=>'form-control empDepartmentId', 'id'=>'empDepartmentId', 'placeholder'=>'Select Department', 'required'])}}
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="validationTooltip01">Designation</label>
                {{Form::select('designationId', [], NULL, ['class'=>'form-control empDesignationId', 'id'=>'empDesignationId', 'placeholder'=>'Select Designation', 'required'])}}
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="Type">Type</label>
                <select class="form-select" id="Type" name="Type">
                 <option selected disabled value="">Choose..</option>
                 <option value="Daily" {{ old('havingSpecsLenses') == 'Yes' ? 'selected' : '' }}>Daily</option>
                 <option value="Weekly" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Weekly</option>
                 <option value="Quarterly" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Quarterly</option>
                 <option value="Monthly" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Monthly</option>
                 <option value="Half Yearly" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Half Yearly</option>
                 <option value="Yearly" {{ old('havingSpecsLenses') == 'No' ? 'selected' : '' }}>Yearly</option>

                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label" for="validationTooltip02">Task</label>
                <textarea class="form-control" name="task" rows="6" cols="3" placeholder="Enter Task"></textarea>
            </div>
            <div class="form-group">
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
