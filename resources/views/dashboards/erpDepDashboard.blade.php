<?php
$userRole = Session()->get('userRole');
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
		<div class="page-header d-xl-flex d-block">
			<div class="page-leftheader">
				<h4 class="page-title">{{$userRole}}<span class="font-weight-normal text-muted ml-2">Dashboard</span></h4>
			</div>
		</div>
        <div class="row">
			<div class="col-xl-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-xl-2 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Employees</span>
												<h3 class="mb-0 mt-1 mb-2">{{$activeEmps}}</h3>
											</div>
										</div>
										<div class="col-2">
											<div class="icon1 bg-success my-auto  float-right"> <i class="feather feather-users"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-8">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Branches</span>
												<h3 class="mb-0 mt-1 mb-2">{{$activeBranches}}</h3>
											</div>
										</div>
										<div class="col-4">
											<div class="icon1 bg-primary my-auto  float-right"> <i class="fa fa-building-o"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="/exitProces/standardProcess">
									<div class="row">
										<div class="col-8">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">NDC</span>
												<h3 class="mb-0 mt-1 mb-2">{{$pendingNdc}}/{{$ndc}}</h3>
											</div>
										</div>
										<div class="col-4">
											<div class="icon1 bg-warning my-auto  float-right"> <i class="feather feather-box"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
