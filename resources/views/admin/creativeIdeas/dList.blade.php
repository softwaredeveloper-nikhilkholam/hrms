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
                <h4 class="page-title">Deactive List</h4>
            </div> 
            <div class="page-rightheader">
                <div class="row">
                    <h4 class="page-title"><a class="btn btn-primary" href="/creativeIdeas/create">Add</a></h4>&nbsp;&nbsp;
                    <h4 class="page-title"><a class="btn btn-primary" href="/creativeIdeas/list">Active List</a></h4>&nbsp;&nbsp;
                </div> 

            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row mt-5">
                                <div class="col-md-3" col-md-3>
                                                <div class="form-group">
                                                  <label class="form-label" for="Gender">General Titles</label>
                                                    <select class="form-control" id="Gender" name="Gender">
                                                     <option selected disabled value="">Select Title</option>
                                                     <option value="Write Up" {{ old('Gender') == 'Male' ? 'selected' : '' }}>Write Up</option>
                                                     <option value="Ideas" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Ideas</option>
                                                     <option value="Artical" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Artical</option>
                                                     <option value="Speech" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Speech</option>
                                                    </select>
                                                </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-body">
                                <div class="table-responsive">
                                        <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example1">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th class="text-white w-5">#</th>
                                                    <th class="text-white">{{($language == 1)?'Sr No': 'दिनांक'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Date': 'एप्लीकेशन टाईप'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Title': 'स्टेटस'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Updated At': 'स्टेटस'}}</th>
                                                    <th class="text-white w-15">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                                </tr>
                                            </thead>
                                            
                                        </table>
                                    </div>
                                <h4 style="color:red;">Not Found Active Records.</h4>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
