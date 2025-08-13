<?php
    $name = Session()->get('name');
	$user = Auth::user();
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
			<!--Page header-->
            <div class="page-header d-xl-flex d-block">
							<div class="page-leftheader">
								<h4 class="page-title">{{($language == 1)?'Payslips':'पेमेंट पावती'}}</h4>
							</div>
							<div class="page-rightheader ml-md-auto">
								<div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
									<div class="btn-list">
										<button  class="btn btn-light" data-toggle="tooltip" data-placement="top" title="E-mail"> <i class="feather feather-mail"></i> </button>
										<button  class="btn btn-light" data-placement="top" data-toggle="tooltip" title="Contact"> <i class="feather feather-phone-call"></i> </button>
										<button  class="btn btn-primary" data-placement="top" data-toggle="tooltip" title="Info"> <i class="fa fa-microphone" aria-hidden="true"></i> </button>
									</div>
								</div>
							</div>
						</div>
						<!--End Page header-->

						<!-- Row -->
						<div class="row">
							<div class="col-xl-12 col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header  border-0">
										<h4 class="card-title">{{($language == 1)?'Payslips':'माझ्या पेमेंट पावती तपशील'}}</h4>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table  table-vcenter text-nowrap table-bordered border-top" id="emp-attendance">
												<thead>
													<tr>
														<th class="border-bottom-0 text-center">#ID</th>
														<th class="border-bottom-0">Month</th>
														<th class="border-bottom-0">Year</th>
														<th class="border-bottom-0">Net Salary</th>
														<th class="border-bottom-0">Generated Date</th>
														<th class="border-bottom-0">Action</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-center">#10029</td>
														<td>July</td>
														<td>2021</td>
														<td class="font-weight-semibold">32,000</td>
														<td>01-08-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10321</td>
														<td>June</td>
														<td>2021</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-07-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10598</td>
														<td>May</td>
														<td>2021</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-06-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10438</td>
														<td>April</td>
														<td>2021</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-05-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10837</td>
														<td>March</td>
														<td>2021</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-04-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10391</td>
														<td>Feb</td>
														<td>2021</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-03-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#11073</td>
														<td>Jan</td>
														<td>2021</td>
														<td class="font-weight-semibold">28,000</td>
														<td>02-02-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10839</td>
														<td>Dec</td>
														<td>2020</td>
														<td class="font-weight-semibold">28,000</td>
														<td>02-01-2021</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10289</td>
														<td>Nov</td>
														<td>2020</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-12-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10422</td>
														<td>Oct</td>
														<td>2020</td>
														<td class="font-weight-semibold">28,000</td>
														<td>01-11-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10029</td>
														<td>Sept</td>
														<td>2020</td>
														<td class="font-weight-semibold">24,000</td>
														<td>01-10-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10398</td>
														<td>Aug</td>
														<td>2020</td>
														<td class="font-weight-semibold">24,000</td>
														<td>01-09-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10092</td>
														<td>July</td>
														<td>2020</td>
														<td class="font-weight-semibold">24,000</td>
														<td>01-08-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#11986</td>
														<td>June</td>
														<td>2020</td>
														<td class="font-weight-semibold">24,000</td>
														<td>01-07-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
													<tr>
														<td class="text-center">#10029</td>
														<td>May</td>
														<td>2020</td>
														<td class="font-weight-semibold">24,000</td>
														<td>01-06-2020</td>
														<td>
															<a class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-original-title="View"><i class="feather feather-eye"></i></a>
															<a class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" data-original-title="Download"><i class="feather feather-download"></i></a>
															<a class="btn btn-info btn-icon btn-sm" data-toggle="tooltip" data-original-title="Print" onclick="javascript:window.print();"><i class="feather feather-printer"></i></a>
															
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row-->
	</div>
</div>
@endsection
