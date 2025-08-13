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
                <h4 class="page-title">Apply Compoff</h4>
            </div> 
            <div class="page-rightheader">
                <div class="row">
                    <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/compOffApplication">Active List</a></h4>&nbsp;&nbsp;
                    <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/compdList">Deactive List</a></h4>&nbsp;&nbsp;
                </div> 

            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Select Compoff Type':'सिलेक्ट कारण'}}<span class="text-red">*</span>:</label>
                                                    {{Form::select('leaveType', ['1'=>'Full Day ', '2'=>'Half Day ( 1st Half )', '3'=>'Half Day ( 2nd Half )'], null, ['class'=>'leaveType form-control', 'placeholder'=>'Select a Option'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Working Date':'पासून'}}<span class="text-red">*</span>:</label>
                                                    <input type="date" class="form-control" name="leaveStartDate" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-6 toDate">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Compoff Date':'च्यापर्यत'}}<span class="text-red">*</span>:</label>
                                                    <input type="date"  class="form-control" name="leaveEndDate" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{($language == 1)?'Description':'डिस्क्रिप्शन'}}<span class="text-red">*</span>:</label>
                                                    <textarea class="form-control mb-4" placeholder="Description" rows="3" maxlength="3000" name="leaveDescription" placeholder="Description"></textarea>
                                                </div>
                                            </div>
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
