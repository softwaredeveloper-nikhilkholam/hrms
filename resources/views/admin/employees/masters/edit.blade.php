@extends('layouts.master')
@section('title', 'Add New Employee')
@section('content')
<style>
    .doc-upload-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    .doc-upload-item { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 1.25rem; transition: all 0.2s ease-in-out; display: flex; flex-direction: column; }
    .doc-upload-item:hover { border-color: #007bff; box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1); }
    .doc-header { display: flex; align-items: center; margin-bottom: 1rem; }
    .doc-header .doc-icon { font-size: 1.75rem; margin-right: 1rem; color: #007bff; width: 40px; text-align: center; }
    .doc-header .doc-title { font-size: 1.1rem; font-weight: 600; color: #343a40; }
    .doc-header .doc-title .text-danger { color: #dc3545; }
    .file-input-wrapper { position: relative; overflow: hidden; display: inline-block; width: 100%; cursor: pointer; }
    .file-input-button { border: 2px dashed #adb5bd; color: #495057; background-color: #fff; padding: 2rem 1rem; border-radius: 0.375rem; font-size: 0.95rem; font-weight: 500; width: 100%; text-align: center; transition: all 0.2s ease-in-out; cursor: pointer; }
    .file-input-wrapper:hover .file-input-button { border-color: #007bff; color: #007bff; background-color: #f0f6ff; }
    .file-input-wrapper input[type=file] { font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; height: 100%; width: 100%; }
    .file-name { margin-top: 0.75rem; font-size: 0.85rem; color: #6c757d; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .section-header { background: linear-gradient(to right, #007bff, #0056b3); color: white; padding: 0.75rem 1.25rem; margin-top: 2rem; margin-bottom: 1.5rem; border-radius: 0.5rem; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .section-header i { font-size: 1.5rem; margin-right: 0.75rem; }
    .section-header h4 { margin: 0; font-weight: 600; color: white !important; }
    .form-label i { margin-right: 8px; color: #5a6a85; }
    .profile-image-preview { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: block; margin: 0 auto 1rem; }
    .text-danger { font-size: 0.875em; }
    /* ... your existing styles ... */

    .doc-upload-item.is-invalid { 
        border-color: #dc3545; 
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25); 
    }
    
    /* Make the inner button red when the parent has an error */
    .doc-upload-item.is-invalid .file-input-button {
        border-color: #dc3545;
    }
    
    /* Make the icon red when the parent has an error */
    .doc-upload-item.is-invalid .doc-icon {
        color: #dc3545;
    }

    /* Style for the green 'selected' state */
    .doc-upload-item.is-selected .file-input-button {
        border-color: #28a745;
        color: #28a745;
        background-color: #d4edda;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container" style="max-width: 100% !important;">
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title"><i class="fas fa-users"></i>  Add New Employee</h4>
                </div>
                <div class="page-rightheader ml-auto">
                    <a href="/employees/create" class="btn btn-danger"><i class="fas fa-plus mr-2"></i> Add New Employee</a>
                    <a href="/employees/nonTeachingEmps" class="btn btn-primary">Non-Teaching</a>
                    <a href="/employees/teachingEmps" class="btn btn-primary">Teaching</a>
                </div>
            </div>
            <div class="row">
                @if($employee)
                    <div class="col-xl-12 col-md-12 col-lg-12">
                        {!! Form::open(['action' => ['admin\employees\EmployeesController@update', $employee->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                            <div class="card">
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger mb-4">
                                            <p><strong><i class="fas fa-exclamation-triangle"></i>  Whoops!</strong> There were some problems with your input. Please check the fields highlighted in red below.</p>
                                        </div>
                                    @endif

                                    <div class="section-header"><i class="fas fa-user-circle"></i> <h4>Basic & Personal Details</h4></div>
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-4">
                                            {{-- Determine if we are in create or edit mode --}}
                                            @php
                                                $isEdit = isset($employee);
                                            @endphp

                                            {{-- 
                                                Dynamically set the image source.
                                                - If editing and a photo exists, show the employee's photo.
                                                - Otherwise (creating or no photo), show a placeholder.
                                            --}}
                                            <img id="profilePreview" 
                                                src="{{ $isEdit && $employee->profilePhoto ? asset('/admin/profilePhotos/' . $employee->profilePhoto) : 'https://placehold.co/150x150/EFEFEF/AAAAAA&text=Photo' }}" 
                                                alt="Profile Preview" 
                                                class="profile-image-preview">

                                            <div class="form-group mt-3" style="display: inline-block;">
                                                <label for="profilePhotoInput" class="form-label">
                                                    {{ $isEdit ? 'Change Profile Photo' : 'Upload Profile Photo' }}
                                                    {{-- The photo is only required when creating a new employee --}}
                                                    @if(!$isEdit)<span class="text-red">*</span>@endif
                                                </label>
                                                
                                                <input id="profilePhotoInput" 
                                                    type="file" 
                                                    class="form-control @error('profilePhoto') is-invalid @enderror" 
                                                    style="width: 250px;" 
                                                    name="profilePhoto" 
                                                    onchange="previewProfilePhoto(this);" 
                                                    {{ !$isEdit ? 'required' : '' }}>
                                                    
                                                @error('profilePhoto')
                                                    <span class="text-danger d-block">{{$message}}</span>
                                                @enderror 
                                            </div>
                                        </div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-user-tie"></i> Role <span class="text-red">*</span></label>{{Form::select('userRoleId', $userRoles, (old('userRoleId'))?old('userRoleId'):$employee->userRoleId, ['class'=>'form-control' . ($errors->has('userRoleId') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select Role'])}} @error('userRoleId')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-user"></i> First Name <span class="text-red">*</span></label><input type="text" class="form-control @error('firstName') is-invalid @enderror" name="firstName" placeholder="First Name" required value="{{old('firstName')?old('firstName'):$employee->firstName}}"> @error('firstName')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="far fa-user"></i> Middle Name</label><input type="text" class="form-control @error('middleName') is-invalid @enderror" name="middleName" placeholder="Middle Name" value="{{old('middleName')?old('middleName'):$employee->middleName}}"> @error('middleName')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-user"></i> Last Name <span class="text-red">*</span></label><input type="text" class="form-control @error('lastName') is-invalid @enderror" name="lastName" placeholder="Last Name" required value="{{old('lastName')?old('lastName'):$employee->lastName}}"> @error('lastName')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-venus-mars"></i> Gender <span class="text-red">*</span></label>{{Form::select('gender', ['Male'=>'Male', 'Female'=>'Female'], old('gender')?old('gender'):$employee->gender, ['class'=>'form-control' . ($errors->has('gender') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select Gender'])}} @error('gender')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-praying-hands"></i> Religion</label><input type="text" class="form-control @error('region') is-invalid @enderror" name="region" placeholder="e.g., Hinduism" value="{{old('region')?old('region'):$employee->region}}"> @error('region')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-users"></i> Caste</label><input type="text" class="form-control @error('cast') is-invalid @enderror" name="cast" placeholder="e.g., Maratha" value="{{old('cast')?old('cast'):$employee->cast}}"> @error('cast')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-user-tag"></i> Sub Caste</label><input type="text" class="form-control @error('type') is-invalid @enderror" name="type" placeholder="Sub Caste" value="{{old('type')?old('type'):$employee->type}}"> @error('type')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-birthday-cake"></i> Date of Birth <span class="text-red">*</span></label><input type="date" name="DOB" class="form-control @error('DOB') is-invalid @enderror" required value="{{old('DOB')?old('DOB'):$employee->DOB}}"/> @error('DOB')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-ring"></i> Marital Status <span class="text-red">*</span></label>{{Form::select('maritalStatus', ['Single'=>'Single','Married'=>'Married','Widowed'=>'Widowed'], old('maritalStatus')?old('maritalStatus'):$employee->maritalStatus, ['class'=>'form-control' . ($errors->has('maritalStatus') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select Status'])}} @error('maritalStatus')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-phone"></i> Phone No <span class="text-red">*</span></label><input type="text" class="form-control @error('phoneNo') is-invalid @enderror" name="phoneNo" placeholder="10-digit number" required value="{{old('phoneNo')?old('phoneNo'):$employee->phoneNo}}"> @error('phoneNo')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fab fa-whatsapp"></i> WhatsApp No</label><input type="text" class="form-control @error('whatsappNo') is-invalid @enderror" name="whatsappNo" placeholder="10-digit number" value="{{old('whatsappNo')?old('whatsappNo'):$employee->whatsappNo}}"> @error('whatsappNo')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-envelope"></i> Email ID</label><input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="example@mail.com" value="{{old('email')?old('email'):$employee->email}}"> @error('email')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-user-graduate"></i> Qualification <span class="text-red">*</span></label><input type="text" class="form-control @error('qualification') is-invalid @enderror" name="qualification" placeholder="e.g., B.Sc. Computer Science" required value="{{old('qualification')?old('qualification'):$employee->qualification}}"> @error('qualification')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-briefcase"></i> Experience <span class="text-red">*</span></label>{{Form::select('workingStatus', ['1'=>'Fresher', '2'=>'Experience'],  old('workingStatus')?old('workingStatus'):$employee->workingStatus, ['id' => 'workingStatus', 'class'=>'form-control' . ($errors->has('workingStatus') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select...'])}} @error('workingStatus')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="fas fa-map-marker-alt"></i> Present Address <span class="text-red">*</span></label><textarea class="form-control @error('presentAddress') is-invalid @enderror" name="presentAddress" placeholder="Current residential address" required>{{old('presentAddress')?old('presentAddress'):$employee->presentAddress}}</textarea> @error('presentAddress')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-6"><div class="form-group"><label class="form-label"><i class="fas fa-home"></i> Permanent Address</label><textarea class="form-control @error('permanentAddress') is-invalid @enderror" name="permanentAddress" placeholder="Permanent address (if different)">{{old('permanentAddress')?old('permanentAddress'):$employee->permanentAddress}}</textarea> @error('permanentAddress')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                    </div>
                                    
                                    <div class="section-header"><i class="fas fa-briefcase"></i> <h4>Work Profile & Experience</h4></div>
                                    <div class="row">
                                        <div class="col-md-2"><div class="form-group"><label class="form-label"><i class="fas fa-sitemap"></i> Organisation <span class="text-red">*</span></label>{{Form::select('organisationId', $organisations, old('organisationId')?old('organisationId'):$employee->organisationId, ['id'=>'organisationId', 'class'=>'form-control' . ($errors->has('organisationId') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select Org.'])}} @error('organisationId')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-store"></i> Branch <span class="text-red">*</span></label>{{Form::select('branchId', $branches, old('branchId')?old('branchId'):$employee->branchId, ['id'=>'branchId','class'=>'form-control' . ($errors->has('branchId') ? ' is-invalid' : ''), 'required', 'placeholder'=>'Select Branch'])}} @error('branchId')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-chalkboard-teacher"></i> Section <span class="text-red">*</span></label>{{Form::select('sectionId', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], old('sectionId')?old('sectionId'):$employee->section, ['class'=>'form-control sectionId' . ($errors->has('sectionId') ? ' is-invalid' : ''), 'required', 'placeholder'=>'Select Section'])}} @error('sectionId')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-building"></i> Department <span class="text-red">*</span></label>{{Form::select('departmentId', $departments, old('departmentId')?old('departmentId'):$employee->departmentId, ['class'=>'form-control empDepartmentId' . ($errors->has('departmentId') ? ' is-invalid' : ''), 'required', 'placeholder'=>'Select Dept.'])}} @error('departmentId')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-user-tag"></i> Designation <span class="text-red">*</span></label>{{Form::select('designationId', $designations, old('designationId')?old('designationId'):$employee->designationId, ['class'=>'form-control empDesignationId' . ($errors->has('designationId') ? ' is-invalid' : ''), 'required', 'placeholder'=>'Select Desig.'])}} @error('designationId')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-book"></i> Teaching Subject</label><input type="text" class="form-control @error('teachingSubject') is-invalid @enderror" name="teachingSubject" placeholder="e.g., Mathematics" value="{{old('teachingSubject')?old('teachingSubject'):$employee->teachingSubject}}"> @error('teachingSubject')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-user-shield"></i> Reporting Authority <span class="text-red">*</span></label>
                                            <select class="form-control @error('reportingId') is-invalid @enderror" name="reportingId" required>
                                                <option value="">Select Option</option>
                                                @foreach($empReportings as $row)
                                                    <option value="{{$row->id}}" empCode="{{$row->empCode}}" {{ old('reportingId') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('reportingId')<span class="text-danger d-block">{{$message}}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-user-friends"></i> Buddy <span class="text-red">*</span></label>{{Form::select('buddyName', $buddyNames, old('buddyName')?old('buddyName'):$employee->buddyName, ['class'=>'form-control buddyName' . ($errors->has('buddyName') ? ' is-invalid' : ''), 'required', 'placeholder'=>'Select buddy Name'])}} @error('buddyName')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-calendar-alt"></i> Joining Date <span class="text-red">*</span></label><input type="date" name="empJobJoingDate" class="form-control @error('empJobJoingDate') is-invalid @enderror" required value="{{old('jobJoingDate')?old('jobJoingDate'):$employee->jobJoingDate}}"/> @error('empJobJoingDate')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="fas fa-clock"></i> Shift <span class="text-red">*</span></label>
                                                
                                                {{-- The 'null' value tells Form::model to automatically find the correct value --}}
                                                {{ Form::select(
                                                    'shift',
                                                    ['Day Shift' => 'Day Shift', 'Night Shift' => 'Night Shift'],
                                                    null,
                                                    ['class' => 'form-control' . ($errors->has('shift') ? ' is-invalid' : ''), 'required']
                                                ) }}

                                                @error('shift')
                                                    <span class="text-danger d-block">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-id-card"></i> ID Card <span class="text-red">*</span></label>{{Form::select('idCardStatus', ['Pending'=>'Pending','No Issued'=>'No Issued', 'Temporary ID Issued'=>'Temporary ID Issued', 'Permanent ID Issued'=>'Permanent ID Issued','issuedOrlostDamage'=>'Issued Or lost Damage'], old('idCardStatus')?old('idCardStatus'):$employee->idCardStatus, ['class'=>'form-control' . ($errors->has('idCardStatus') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select Status'])}} @error('idCardStatus')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-hourglass-start"></i> In Time <span class="text-red">*</span></label><input type="time" class="form-control @error('jobStartTime') is-invalid @enderror" value="{{old('jobStartTime')?old('jobStartTime'):$employee->startTime}}" name="jobStartTime" required> @error('jobStartTime')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-hourglass-end"></i> Out Time <span class="text-red">*</span></label><input type="time" class="form-control @error('jobEndTime') is-invalid @enderror" value="{{old('jobEndTime')?old('jobEndTime'):$employee->endTime}}" name="jobEndTime" required> @error('jobEndTime')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-file-signature"></i> Contract Start</label><input type="date" class="form-control @error('contractStartDate') is-invalid @enderror" name="contractStartDate" value="{{old('contractStartDate')?old('contractStartDate'):$employee->contractStartDate}}"> @error('contractStartDate')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-file-excel"></i> Contract End</label><input type="date" class="form-control @error('contractEndDate') is-invalid @enderror" name="contractEndDate" value="{{old('contractEndDate')?old('contractEndDate'):$employee->contractEndDate}}"> @error('contractEndDate')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-bus"></i> Transport Allowance <span class="text-red">*</span></label>{{Form::select('transAllowed', ['1'=>'Yes', '0'=>'No'], old('transAllowed')?old('transAllowed'):$user->transAllowed, ['class'=>'form-control' . ($errors->has('transAllowed') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select...'])}} @error('transAllowed')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                    </div>
                                    
                                    <div id="previousExperienceSection" style="display: none;">
                                        <hr>
                                        <h4 class="mb-4 font-weight-bold" style="color:Red;">Previous Experience Details</h4>
                                        @for($i=0; $i<2; $i++)
                                            <div class="row">
                                                <div class="col-md-2"><div class="form-group"><label>Organisation Name</label><input type="text" class="form-control @error('experName.'.$i) is-invalid @enderror" name="experName[]" placeholder="Company Name" value="{{ old('experName.'.$i) }}"> @error('experName.'.$i)<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                                <div class="col-md-2"><div class="form-group"><label>Designation</label><input type="text" class="form-control @error('experDesignation.'.$i) is-invalid @enderror" name="experDesignation[]" placeholder="e.g., Software Engineer" value="{{ old('experDesignation.'.$i) }}"> @error('experDesignation.'.$i)<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                                <div class="col-md-2"><div class="form-group"><label>Duration From</label><input type="date" class="form-control @error('experFromDuration.'.$i) is-invalid @enderror" name="experFromDuration[]" value="{{ old('experFromDuration.'.$i) }}"> @error('experFromDuration.'.$i)<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                                <div class="col-md-2"><div class="form-group"><label>Duration To</label><input type="date" class="form-control @error('experToDuration.'.$i) is-invalid @enderror" name="experToDuration[]" value="{{ old('experToDuration.'.$i) }}"> @error('experToDuration.'.$i)<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                                <div class="col-md-2"><div class="form-group"><label>Last Salary</label><input type="text" class="form-control @error('experLastSalary.'.$i) is-invalid @enderror" name="experLastSalary[]" placeholder="e.g., 45000" value="{{ old('experLastSalary.'.$i) }}"> @error('experLastSalary.'.$i)<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                                <div class="col-md-2"><div class="form-group"><label>Reason for Leaving</label><input type="text" class="form-control @error('experReasonLeaving.'.$i) is-invalid @enderror" name="experReasonLeaving[]" placeholder="Reason" value="{{ old('experReasonLeaving.'.$i) }}"> @error('experReasonLeaving.'.$i)<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                            </div>
                                            @if($i < 1)<hr class="my-2">@endif
                                        @endfor
                                    </div>

                                    <hr>
                                    <div class="section-header"><i class="fas fa-money-check-alt"></i> <h4>Bank, ID & Other Details</h4></div>
                                    <div class="row">
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-id-card"></i> Aadhar Card No. <span class="text-red">*</span></label><input type="text" name="aadhaarCardNo" class="form-control @error('aadhaarCardNo') is-invalid @enderror" placeholder="Aadhaar Card No" required value="{{old('aadhaarCardNo')?old('aadhaarCardNo'):$employee->AADHARNo}}" readonly> @error('aadhaarCardNo')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-id-card"></i> PAN No <span class="text-red">*</span></label><input type="text" name="PANNo" class="form-control @error('PANNo') is-invalid @enderror" placeholder="ABCDE1234F" required value="{{old('PANNo')?old('PANNo'):$employee->PANNo}}"> @error('PANNo')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-university"></i> Bank Name</label><input type="text" name="bankName" class="form-control @error('bankName') is-invalid @enderror" placeholder="Bank Name" value="{{old('bankName')?old('bankName'):$employee->bankName}}"> @error('bankName')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-code-branch"></i> Bank Branch</label><input type="text" name="bankBranch" class="form-control @error('bankBranch') is-invalid @enderror" placeholder="Branch Name" value="{{old('bankBranch')?old('bankBranch'):$employee->bankBranch}}"> @error('bankBranch')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-user-circle"></i> Bank A/C Name</label><input type="text" name="bankAccountName" class="form-control @error('bankAccountName') is-invalid @enderror" placeholder="Account Holder Name" value="{{old('bankAccountName')?old('bankAccountName'):$employee->bankAccountName}}"> @error('bankAccountName')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-hashtag"></i> Bank A/C No.</label><input type="text" name="bankAccountNo" class="form-control @error('bankAccountNo') is-invalid @enderror" placeholder="Account Number" value="{{old('bankAccountNo')?old('bankAccountNo'):$employee->bankAccountNo}}"> @error('bankAccountNo')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-code"></i> IFSC Code</label><input type="text" name="bankIFSCCode" class="form-control @error('bankIFSCCode') is-invalid @enderror" placeholder="IFSC Code" value="{{old('bankIFSCCode')?old('bankIFSCCode'):$employee->bankIFSCCode}}"> @error('bankIFSCCode')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-fingerprint"></i> PF Number</label><input type="text" name="pfNumber" class="form-control @error('pfNumber') is-invalid @enderror" placeholder="PF Number" value="{{old('pfNumber')?old('pfNumber'):$employee->pfNumber}}"> @error('pfNumber')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-fingerprint"></i> UAN Number</label><input type="text" name="uIdNumber" class="form-control @error('uIdNumber') is-invalid @enderror" placeholder="UAN Number" value="{{old('uIdNumber')?old('uIdNumber'):$employee->uIdNumber}}"> @error('uIdNumber')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-share-alt"></i> Reference</label>{{Form::select('reference', ['Facebook'=>'Facebook', 'Instagram'=>'Instagram', 'Other'=>'Other'], old('reference')?old('reference'):$employee->reference, ['class'=>'form-control' . ($errors->has('reference') ? ' is-invalid' : ''), 'placeholder' => 'Select...'])}} @error('reference')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-2"><div class="form-group"><label><i class="fas fa-calendar-check"></i> Attendance Type <span class="text-red">*</span></label>{{Form::select('attendanceType', ['Manual'=>'Manual', 'Automatic'=>'Automatic'], old('attendanceType')?old('attendanceType'):$employee->attendanceType, ['class'=>'form-control' . ($errors->has('attendanceType') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Select...'])}} @error('attendanceType')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                    </div>

                                    <hr>
                                    <div class="section-header"><i class="fas fa-first-aid"></i> <h4>Emergency Contact Details</h4></div>
                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-user"></i> Contact 1 Name <span class="text-red">*</span></label><input type="text" name="emergencyName1" class="form-control @error('emergencyName1') is-invalid @enderror" placeholder="Full Name" required value="{{old('emergencyName1')}}"> @error('emergencyName1')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-users"></i> Contact 1 Relation <span class="text-red">*</span></label><input type="text" name="emergencyRelation1" class="form-control @error('emergencyRelation1') is-invalid @enderror" placeholder="e.g., Father" required value="{{old('emergencyRelation1')}}"> @error('emergencyRelation1')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-map-pin"></i> Contact 1 Place</label><input type="text" name="emergencyPlace1" class="form-control @error('emergencyPlace1') is-invalid @enderror" placeholder="City" value="{{old('emergencyPlace1')}}"> @error('emergencyPlace1')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-phone-alt"></i> Contact 1 Phone <span class="text-red">*</span></label><input type="text" name="emergencyContactNo1" class="form-control @error('emergencyContactNo1') is-invalid @enderror" placeholder="10-digit number" required value="{{old('emergencyContactNo1')}}"> @error('emergencyContactNo1')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-user"></i> Contact 2 Name</label><input type="text" name="emergencyName2" class="form-control @error('emergencyName2') is-invalid @enderror" placeholder="Full Name" value="{{old('emergencyName2')}}"> @error('emergencyName2')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-users"></i> Contact 2 Relation</label><input type="text" name="emergencyRelation2" class="form-control @error('emergencyRelation2') is-invalid @enderror" placeholder="e.g., Mother" value="{{old('emergencyRelation2')}}"> @error('emergencyRelation2')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-map-pin"></i> Contact 2 Place</label><input type="text" name="emergencyPlace2" class="form-control @error('emergencyPlace2') is-invalid @enderror" placeholder="City" value="{{old('emergencyPlace2')}}"> @error('emergencyPlace2')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                        <div class="col-md-3"><div class="form-group"><label><i class="fas fa-phone-alt"></i> Contact 2 Phone</label><input type="text" name="emergencyContactNo2" class="form-control @error('emergencyContactNo2') is-invalid @enderror" placeholder="10-digit number" value="{{old('emergencyContactNo2')}}"> @error('emergencyContactNo2')<span class="text-danger d-block">{{$message}}</span>@enderror </div></div>
                                    </div>
                                    </div>

                                    <hr>
<div class="section-header"><i class="fas fa-folder-open"></i><h4>Upload Documents</h4></div>

@if ($errors->any())
    <div class="alert alert-warning">
        <i class="fas fa-cloud-upload-alt"></i> <strong>Note:</strong> Validation failed. For security, all files must be re-selected before submitting again.
    </div>
@endif

<div class="doc-upload-grid">
    @php
        // This is the master list of all possible documents for the form.
        // We make the 'required' status dynamic based on whether we are editing.
        $documents = [
            ['name' => 'uploadAadharCard', 'title' => 'Aadhaar Card', 'icon' => 'fa-id-card', 'required' => !$isEdit, 'type' => '1'],
            ['name' => 'uploadPanCard', 'title' => 'PAN Card', 'icon' => 'fa-id-badge', 'required' => !$isEdit, 'type' => '2'],
            ['name' => 'uploadBankDetails', 'title' => 'Bank Details', 'icon' => 'fa-university', 'required' => false, 'type' => '7'],
            ['name' => 'uploadDrivingLicense', 'title' => 'Driving License', 'icon' => 'fa-car', 'required' => false, 'type' => '4'],
            ['name' => 'uploadTestimonials10th', 'title' => '10th Marksheet', 'icon' => 'fa-award', 'required' => false, 'type' => '5'],
            ['name' => 'uploadTestimonials12th', 'title' => '12th Marksheet', 'icon' => 'fa-award', 'required' => false, 'type' => '3'],
            ['name' => 'uploadTestimonialsGrad', 'title' => 'Graduation Certificate', 'icon' => 'fa-certificate', 'required' => false, 'type' => '6'],
            ['name' => 'uploadTestimonialsPostGrad', 'title' => 'Post Grad Certificate', 'icon' => 'fa-user-graduate', 'required' => false, 'type' => '8'],
            ['name' => 'uploadEmployeeContract', 'title' => 'Policy Document', 'icon' => 'fa-file-contract', 'required' => false, 'type' => '9'],
            ['name' => 'uploadTestimonialsOther', 'title' => 'Other Documents', 'icon' => 'fa-folder-plus', 'required' => false, 'type' => '10'],
        ];
    @endphp

    {{-- We loop through the MASTER list of documents defined above --}}
    @foreach ($documents as $doc)
        @php
            // For each document type, we CHECK if an uploaded version exists in the collection
            // passed from the controller ($employee_documents).
            $existingDoc = isset($employee_documents) ? $employee_documents->where('type', $doc['type'])->first() : null;
        @endphp
        <div class="doc-upload-item @error($doc['name']) is-invalid @enderror">
            <div class="doc-header">
                <i class="fas {{ $doc['icon'] }} doc-icon"></i>
                <h6 class="doc-title">
                    {{ $doc['title'] }}
                    {{-- The field is required only if the config says so AND a file hasn't been uploaded yet --}}
                    @if($doc['required'] && !$existingDoc)<span class="text-danger">*</span>@endif
                </h6>
            </div>

            {{-- If a document is already uploaded, show a link to view it --}}
            @if($existingDoc)
                <div class="existing-doc" style="color:red;">
                    <i class="fas fa-check-circle"></i> 
                    <a href="{{ asset('/admin/images/empDocs/'.$employee->empCode.'/' . $existingDoc->fileName) }}" target="_blank"  style="color:green;">View Uploaded File</a>
                </div>
            @endif

            <div class="file-input-wrapper">
                <div class="file-input-button">
                    <i class="fas fa-cloud-upload-alt"></i> 
                    {{-- Change button text if a file already exists --}}
                    {{ $existingDoc ? 'Replace File' : 'Choose File' }}
                </div>
                {{-- The input is required only if the config says so AND a file isn't already uploaded --}}
                <input type="file" name="{{ $doc['name'] }}" onchange="updateFileName(this)" {{ ($doc['required'] && !$existingDoc) ? 'required' : '' }}>
            </div>
            <div class="file-name">No new file selected</div>
            @error($doc['name'])<span class="text-danger mt-1 d-block text-center">{{$message}}</span>@enderror
        </div>
    @endforeach
</div>

                                    <div class="row mt-5">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-success btn-lg">Save Employee Information</button>
                                            <a href="/employees" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                @else
                                            <h5>Employee Information not found..</h5>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        
        // --- Logic for Dynamic Designation Dropdown ---
        const oldDesignationId = '{{ old('designationId') }}';
        function fetchDesignations(departmentId, selectedDesignationId) {
            if (!departmentId) {
                $('.empDesignationId').html('<option value="">Select Desig.</option>');
                return;
            }
            const url = `/api/departments/${departmentId}/designations`; 
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    let designationDropdown = $('.empDesignationId');
                    designationDropdown.empty().append('<option value="">Select Desig.</option>');
                    $.each(data, function(key, value) {
                        designationDropdown.append($('<option></option>').attr('value', key).text(value));
                    });
                    if (selectedDesignationId) {
                        designationDropdown.val(selectedDesignationId);
                    }
                },
                error: function() { console.error('Failed to load designations.'); }
            });
        }
        $('.empDepartmentId').on('change', function() {
            fetchDesignations($(this).val(), null);
        });
        let initialDepartmentId = $('.empDepartmentId').val();
        if (initialDepartmentId) {
            fetchDesignations(initialDepartmentId, oldDesignationId);
        }

        // --- Logic for Experience Dropdown ---
        function toggleExperienceSection() {
            if ($('#workingStatus').val() == '2') { // Value '2' corresponds to 'Experience'
                $('#previousExperienceSection').slideDown('fast');
            } else {
                $('#previousExperienceSection').slideUp('fast');
            }
        }
        $('#workingStatus').on('change', toggleExperienceSection);
        toggleExperienceSection(); // Check on page load

    });

    // --- Utility Functions ---
    function updateFileName(input) {
        const fileNameDisplay = input.parentElement.nextElementSibling;
        const uploadItem = $(input).closest('.doc-upload-item');
        if (input.files.length > 0) {
            fileNameDisplay.textContent = input.files[0].name;
            input.previousElementSibling.style.borderColor = '#28a745';
            input.previousElementSibling.style.color = '#28a745';
            input.previousElementSibling.innerHTML = '<i class="fas fa-check-circle"></i>  File Selected';
            uploadItem.removeClass('is-invalid');
        } else {
            fileNameDisplay.textContent = 'No file selected';
            input.previousElementSibling.style.borderColor = '#adb5bd';
            input.previousElementSibling.style.color = '#495057';
            input.previousElementSibling.innerHTML = '<i class="fas fa-cloud-upload-alt"></i>  Choose File';
        }
    }

    function previewProfilePhoto(input) {
        const preview = document.getElementById('profilePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection