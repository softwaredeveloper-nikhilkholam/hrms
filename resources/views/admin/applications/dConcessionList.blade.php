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
                <h4 class="page-title">Deactive Concession List</h4>
            </div> 
            <div class="page-rightheader">
                <div class="row">
                    <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/applyConcession">Apply Concession</a></h4>&nbsp;&nbsp;
                    <h4 class="page-title"><a class="btn btn-primary" href="/empApplications/concessionList">Active Concession List</a></h4>&nbsp;&nbsp;
                </div> 

            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                                <div class="table-responsive">
                                        <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example1">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th class="text-white w-5">#</th>
                                                    <th class="text-white">{{($language == 1)?'Sr No': 'दिनांक'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Name Of Emp': 'एप्लीकेशन टाईप'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Branch': 'स्टेटस'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Designation': 'अपडेटेड टाईम'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Name Of Student': 'अपडेटेड बाय'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Concession Amount': 'अपडेटेड बाय'}}</th>
                                                    <th class="text-white">{{($language == 1)?'Status': 'अपडेटेड बाय'}}</th>
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
