<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = Auth::user()->language; 
    $transAllowed = Auth::user()->transAllowed; 

?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Apply Concession</h4>
            </div> 
            <div class="page-rightheader">
                <div class="row">
                    <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/concessionList">Active Concession List</a></h4>&nbsp;&nbsp;
                    <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/dConcessionList">Deactive Concession List</a></h4>&nbsp;&nbsp;
                </div> 

            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Name Of Employee':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Employee Code':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="code">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Designation':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="Designation">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Branch':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="Branch Name">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label" for="Gender">Gender</label>
                                                    <select class="form-control" id="Gender" name="Gender">
                                                     <option selected disabled value="">Select Gender</option>
                                                     <option value="Male" {{ old('Gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                     <option value="Female" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Student Name':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Full Adress':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Contact No':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Application Date':''}}<span class="text-red">*</span>:</label>
                                                  <input type="Date"  class="form-control" name="Date" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Year':''}}<span class="text-red">*</span>:</label>
                                                  <input type="Year"  class="form-control" name="Year" placeholder="Year">
                                                </div>
                                            </div>
                                            <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label">{{($language == 1)?'Student Branch':''}}<span class="text-red">*</span>:</label>
                                                  <input type="name"  class="form-control" name="name" placeholder="Name">
                                                </div>
                                            </div>
                                         </div>
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2">
                                               <div class="form-group mt-5">
                                                <button type="submit" class="btn btn-primary btn-block">Save</button>
                                            </div>

                                    </div>
                                </div>
                                        </div>
                </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
