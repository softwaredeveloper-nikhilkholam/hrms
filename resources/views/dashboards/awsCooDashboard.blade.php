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
		<style>
			* {
			box-sizing: border-box;
			}

			.heading {
			font-size: 25px;
			margin-right: 25px;
			}

			.checked {
			color: orange;
			}

			/* Three column layout */
			.side {
			float: left;
			width: 15%;
			margin-top:10px;
			}

			.middle {
			margin-top:10px;
			float: left;
			width: 70%;
			}

			/* Place text to the right */
			.right {
			text-align: right;
			}

			/* Clear floats after the columns */
			.row:after {
			content: "";
			display: table;
			clear: both;
			}

			/* The bar container */
			.bar-container {
			width: 100%;
			background-color: #f1f1f1;
			text-align: center;
			color: white;
			}

			/* Individual bars */
			.bar-5 {width: 60%; height: 18px; background-color: #04AA6D;}
			.bar-4 {width: 30%; height: 18px; background-color: #2196F3;}
			.bar-3 {width: 10%; height: 18px; background-color: #00bcd4;}
			.bar-2 {width: 4%; height: 18px; background-color: #ff9800;}
			.bar-1 {width: 15%; height: 18px; background-color: #f44336;}

			/* Responsive layout - make the columns stack on top of each other instead of next to each other */
			@media (max-width: 400px) {
			.side, .middle {
				width: 100%;
			}
			.right {
				display: none;
			}
			}

			.chart {
				width: 100%; 
				min-height: 450px;
				position: absolute;
				top: 0;
				left: 0;
				height:100%;
				padding-top: 5.25%;
			}
		</style>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = google.visualization.arrayToDataTable(<?php echo $temps; ?>);
				var options = {
					title: "Today's Attendance"
					};
				var chart = new google.visualization.PieChart(document.getElementById('piechart'));
				chart.draw(data, options);
			}
 
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart5);
			function drawChart5() {
				var data5 = google.visualization.arrayToDataTable(<?php echo $tempEmpTimes; ?>);
				var options5 = {
					title: "Today's Employees Timing Status"
					};
				var chart5 = new google.visualization.PieChart(document.getElementById('piechartTimeStatus'));
				chart5.draw(data5, options5);
			}

			google.charts.load("current", {packages:["corechart"]});
			google.charts.setOnLoadCallback(drawChart1);
			function drawChart1() 
			{
				var data1 = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['Not Found',   <?php echo $notFound; ?>],
					['AGF',     <?php echo $agfCt; ?>],
					['Exit Pass',      <?php echo $exitPassCt; ?>],
					['Leave Application',  <?php echo $leaveCt; ?>],
					['Travelling Allowance', <?php echo $travelCt; ?>]
				]);
				var options1 = <?php echo $msg; ?>
				var chart1 = new google.visualization.PieChart(document.getElementById('piechart_3d'));
				chart1.draw(data1, options1);
			}

			google.charts.load("current", {packages:["corechart"]});
			google.charts.setOnLoadCallback(drawChart2);
			function drawChart2() {
				var data2 = google.visualization.arrayToDataTable([
				['Task', 'Hours per Day'],
				['Male', <?php echo $male; ?>],
				['Female', <?php echo $female; ?>],
				]);
				var options2 = {
				title: 'AWS Gender Graph',
				pieHole: 0.4,
				};
				var chart2 = new google.visualization.PieChart(document.getElementById('donutchart'));
				chart2.draw(data2, options2);
			}

			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawChart3);

			function drawChart3() {
				var data3 = google.visualization.arrayToDataTable(<?php echo $tempGraph; ?>);

				var options3 = {
				chart: {
					title: 'Attendance of AWS',
					subtitle: 'Last 7 Days',
				}
				};
				var chart3 = new google.charts.Bar(document.getElementById('columnchart_material'));
				chart3.draw(data3, google.charts.Bar.convertOptions(options3));
			}

		</script>
        <div class="row">
			<div class="col-xl-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-xl-3 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">AWS Employees</span>
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
								<a href="#">
									<div class="row">
										<div class="col-8">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Departments</span>
												<h3 class="mb-0 mt-1 mb-2">{{$activeDepartments}}</h3>
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
					<div class="col-xl-2 col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">NDC</span>
											<h3 class="mb-0 mt-1  mb-2">{{$pendingNdc}}/{{$ndc}}</h3> </div>
										</div>
										<div class="col-2">
											<div class="icon1 bg-danger brround my-auto  float-right"> <i class="fa fa-ticket" aria-hidden="true"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">New Joined {{date('M-y', strtotime('-1 month'))}}</span>
											<h3 class="mb-0 mt-1  mb-2">{{$newJoined}}</h3> </div>
										</div>
										<div class="col-2">
											<div class="icon1 bg-secondary brround my-auto  float-right"> <i class="feather feather-users"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-xl-3 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Advance Taken</span>
												<h3 class="mb-0 mt-1 mb-2">Rs. 0/-</h3>
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
					<div class="col-xl-3 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-8">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Travelling Allowance Exp.</span>
												<h3 class="mb-0 mt-1 mb-2">Rs. 0/-</h3>
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
					<div class="col-xl-3 col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">{{date('M-y', strtotime('-1 month'))}} Salary Expenses</span>
											<h3 class="mb-0 mt-1  mb-2">Rs. {{$lastSalary}}/-</h3> </div>
										</div>
										<div class="col-2">
											<div class="icon1 bg-danger brround my-auto  float-right"> <i class="fa fa-ticket" aria-hidden="true"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<a href="#">
									<div class="row">
										<div class="col-10">
											<div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">{{date('M-y')}} Exp. Salary till Date</span>
											<h3 class="mb-0 mt-1  mb-2">Rs. {{$expectedSalary}}/-</h3> </div>
										</div>
										<div class="col-2">
											<div class="icon1 bg-secondary brround my-auto float-right"> <i class="feather feather-users"></i> </div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top:0 !important;">
			<div class="col-xl-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-xl-4 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="piechart"  class="chart"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="piechart_3d"  class="chart"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="donutchart"  class="chart"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top:0 !important;">
			<div class="col-xl-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-xl-8 col-lg-8 col-md-12" style="overflow-y: hidden;" >
						<div class="card">
							<div class="card-body" style="position: relative;height:500px;overflow-x: scroll;">
								<div id="columnchart_material"  style="margin-left:20px;height:200px;width:750px;" class="chart"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="piechartTimeStatus"  class="chart"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
