<?php
$userRole = Session()->get('userRole');
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
	<div class="container" style="max-width: 100% !important;">                        
		<!--Page header-->
		<div class="page-header d-xl-flex d-block">
			<div class="page-leftheader">
				<h4 class="page-title">{{$userRole}}<span class="font-weight-normal text-muted ml-2">Dashboard</span></h4>
			</div>
		</div>
        <style>            
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

            google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart10);
			function drawChart10() {
				var data7 = google.visualization.arrayToDataTable(<?php echo $tempTeachingActiveDeactive; ?>);
				var options8 = {
					title: "Teaching Employee Active / Deactive"
					};
				var chart9 = new google.visualization.PieChart(document.getElementById('piechartTeachingActiveDeactive'));
				chart9.draw(data7, options8);
			}

            google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart101);
			function drawChart101() {
				var data7 = google.visualization.arrayToDataTable(<?php echo $tempNonTeachingActiveDeactive; ?>);
				var options8 = {
					title: "Non Teaching Employee Active / Deactive"
					};
				var chart9 = new google.visualization.PieChart(document.getElementById('piechartNonTeachingActiveDeactive'));
				chart9.draw(data7, options8);
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

        </script>
        <div class="row">
			<div class="col-xl-12 col-md-12 col-lg-12">
				<div class="row">
                    <div class="col-xl-8 col-md-8 col-lg-8">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">Employees</span>
                                                        <h3 class="mb-0 mt-1 mb-2">{{$employees}}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="icon1 bg-success my-auto  float-right"> <i class="feather feather-users"></i> </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#"  data-toggle="modal" data-target="#myModal">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">Branches</span>
                                                        <h3 class="mb-0 mt-1 mb-2">{{$branches}}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="icon1 bg-primary my-auto  float-right"> <i class="fa fa-building-o"></i> </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div id="myModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">No.</th>    
                                                                                <th width="40%">Branch Name</th>    
                                                                                <th width="10%" class="text-center">Teaching Count</th>    
                                                                                <th width="10%" class="text-center">Non Teaching Count</th>    
                                                                                <th width="35%" class="text-center">Total<?php $i=1;$totalEmps=$totalTeachingEmps=$totalNonTeachingEmps=$totTeachNonTeach=0; ?></th>    
                                                                            <tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if(isset($branchWiseEmployees))
                                                                                @foreach($branchWiseEmployees as $row)
                                                                                    <tr>
                                                                                        <td>{{$i++}}</td>
                                                                                        <td>{{$row->branchName}}</td>
                                                                                        <td class="text-center">{{$row->teachingCount}} <b style="color:red;">( {{round($row->teachingPercentage, 0)}}% )</b>
                                                                                        <td class="text-center">{{$row->nonTeachingCount}} <b style="color:red;">( {{round($row->nonTeachingPercentage, 0)}}% ) </b>
                                                                                        <td class="text-center">{{$totTeachNonTeach = $row->teachingCount + $row->nonTeachingCount}}
                                                                                            <?php $totalEmps=$totalEmps+($row->teachingCount + $row->nonTeachingCount);
                                                                                                $totalTeachingEmps=$totalTeachingEmps+$row->teachingCount;
                                                                                                $totalNonTeachingEmps=$totalNonTeachingEmps+$row->nonTeachingCount; ?>  
                                                                                                <b style="color:red;">( {{round(($totTeachNonTeach/$totalEmployees)*100, 2)}}%)</b>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                <tr>
                                                                                    <td class="text-center" colspan="2">Total</td>
                                                                                    <td class="text-center">{{$totalTeachingEmps}} <b style="color:red;">( {{round(($totalTeachingEmps/$totalEmps)*100)}}%)</b></td>
                                                                                    <td class="text-center">{{$totalNonTeachingEmps}} <b style="color:red;">( {{round(($totalNonTeachingEmps/$totalEmps)*100)}}%)</b></td>
                                                                                    <td class="text-center">{{$totalEmps}}</td>
                                                                                </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="modal" data-target="#myModalDepartment">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">Departments</span>
                                                        <h3 class="mb-0 mt-1 mb-2">{{$departments}}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="icon1 bg-warning my-auto  float-right"> <i class="feather feather-box"></i> </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div id="myModalDepartment" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">No.</th>    
                                                                                <th width="60%">Department Name</th>    
                                                                                <th width="35%">Total Employee<?php $i=1;$totalEmps=0; ?></th>    
                                                                            <tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if(isset($departmentWiseEmployees))
                                                                                @foreach($departmentWiseEmployees as $row)
                                                                                    <tr>
                                                                                        <td>{{$i++}}</td>
                                                                                        <td>{{$row->departmentName}}</td>
                                                                                        <td>{{$row->departmentCount}}
                                                                                            <?php $totalEmps=$totalEmps+$row->departmentCount; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                <tr>
                                                                                    <td colspan="2">Total</td>
                                                                                    <td>{{$totalEmps}}</td>
                                                                                </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="modal" data-target="#myModalTickets">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">Tickets of ({{date('M-Y')}})</span>
                                                    <h3 class="mb-0 mt-1  mb-2">{{$tickets}}</h3> </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="icon1 bg-danger brround my-auto  float-right"> <i class="fa fa-ticket" aria-hidden="true"></i> </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div id="myModalTickets" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">No.</th>    
                                                                                <th width="60%">Month</th>    
                                                                                <th width="35%">Total Ticket<?php $i=1;$totalTicket=0; ?></th>    
                                                                            <tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if(isset($ticketMonthWise))
                                                                                @foreach($ticketMonthWise as $row)
                                                                                    <tr>
                                                                                        <td>{{$i++}}</td>
                                                                                        <td>{{date('M-Y', strtotime($row->ticketMonth))}}</td>
                                                                                        <td>{{$row->totalTicket}}
                                                                                            <?php $totalTicket=$totalTicket+$row->totalTicket; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                <tr>
                                                                                    <td colspan="2">Total</td>
                                                                                    <td>{{$totalTicket}}</td>
                                                                                </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#"  data-toggle="modal" data-target="#myModalJoineeLeft">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">Joined/Left Emp.{{date('M-Y')}}</span>
                                                    <h3 class="mb-0 mt-1  mb-2">{{$newJoinee}} / {{$leftEmployees}}</h3> </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="icon1 bg-secondary brround my-auto  float-right"> <i class="feather feather-users"></i> </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div id="myModalJoineeLeft" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3>New Joinnee / NDC</h3>
                                                                <div class="table-responsive">
                                                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">No.</th>    
                                                                                <th width="45%">Month</th>    
                                                                                <th width="25%">Total Joined Emp.</th>    
                                                                                <th width="25%">Total Left Emp.<?php $i=1;$totalNewJoinnee=0;$totalNdc=0; ?></th>    
                                                                            <tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($ndcNewJoineeList as $temp)
                                                                                <tr>
                                                                                    <td>{{$i++}}</td>
                                                                                    <td>{{date('M-Y', strtotime($temp['forDate']))}}</td>
                                                                                    <td>{{$temp['newJoinneeCount']}} <?php $totalNewJoinnee = $totalNewJoinnee + $temp['newJoinneeCount']; ?></td>
                                                                                    <td>{{$temp['ndcCount']}} <?php $totalNdc = $totalNdc + $temp['ndcCount']; ?></td>
                                                                                </tr>     
                                                                            @endforeach
                                                                            <tr>
                                                                                <td colspan="2">Total</td>
                                                                                <td>{{$totalNewJoinnee}}</td>
                                                                                <td>{{$totalNdc}}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#"   data-toggle="modal" data-target="#myModalTeachingJoineeLeft">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">Teaching Joined/Left {{date('M-Y')}}</span>
                                                    <h3 class="mb-0 mt-1  mb-2">{{$newTeachingJoinee}} / {{$leftTeachingEmployees}}</h3> </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="icon1 bg-danger brround my-auto  float-right"> <i class="feather feather-delete"></i> </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div id="myModalTeachingJoineeLeft" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3>Teaching Joinnee / NDC</h3>
                                                                <div class="table-responsive">
                                                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">No.</th>    
                                                                                <th width="45%">Month</th>    
                                                                                <th width="25%">Total Joined Emp.</th>    
                                                                                <th width="25%">Total Left Emp.<?php $i=1;$totalNewJoinnee=0;$totalNdc=0; ?></th>    
                                                                            <tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($ndcNewJoineeList as $temp)
                                                                                <tr>
                                                                                    <td>{{$i++}}</td>
                                                                                    <td>{{date('M-Y', strtotime($temp['forDate']))}}</td>
                                                                                    <td>{{$temp['newTeachingJoinneeCount']}} <?php $totalNewJoinnee = $totalNewJoinnee + $temp['newTeachingJoinneeCount']; ?></td>
                                                                                    <td>{{$temp['ndcTeachingCount']}} <?php $totalNdc = $totalNdc + $temp['ndcTeachingCount']; ?></td>
                                                                                </tr>     
                                                                            @endforeach
                                                                            <tr>
                                                                                <td colspan="2">Total</td>
                                                                                <td>{{$totalNewJoinnee}}</td>
                                                                                <td>{{$totalNdc}}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#"    data-toggle="modal" data-target="#myModalNonTeachingJoineeLeft">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="mt-0 text-left"> <span class="fs-11 font-weight-semibold">NonTeaching Joined/Left {{date('M-Y')}}</span>
                                                    <h3 class="mb-0 mt-1  mb-2">{{$newNonTeachingJoinee}} / {{$leftNonTeachingEmployees}}</h3> </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="icon1 bg-danger brround my-auto  float-right"> <i class="feather feather-delete"></i> </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div id="myModalNonTeachingJoineeLeft" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3>Non Teaching Joinnee / NDC</h3>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">No.</th>    
                                                                                <th width="45%">Month</th>    
                                                                                <th width="25%">Total Joined Emp.</th>    
                                                                                <th width="25%">Total Left Emp.<?php $i=1;$totalNewJoinnee=0;$totalNdc=0; ?></th>    
                                                                            <tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($ndcNewJoineeList as $temp)
                                                                                <tr>
                                                                                    <td>{{$i++}}</td>
                                                                                    <td>{{date('M-Y', strtotime($temp['forDate']))}}</td>
                                                                                    <td>{{$temp['newNonTeachingJoinneeCount']}} <?php $totalNewJoinnee = $totalNewJoinnee + $temp['newNonTeachingJoinneeCount']; ?></td>
                                                                                    <td>{{$temp['ndcNonTeachingCount']}} <?php $totalNdc = $totalNdc + $temp['ndcNonTeachingCount']; ?></td>
                                                                                </tr>     
                                                                            @endforeach
                                                                            <tr>
                                                                                <td colspan="2">Total</td>
                                                                                <td>{{$totalNewJoinnee}}</td>
                                                                                <td>{{$totalNdc}}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
				    </div>
                    <div class="col-xl-4 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h4 class="card-title">Notice Board</h4>
                                    </div>
                                    <div class="pt-4">
                                        <div class="table-responsive">
                                            <table class="table transaction-table mb-0 text-nowrap">
                                                <tbody style="overflow-y:scroll; height:10px;">
                                                    @foreach($notices as $notice)
                                                        <tr class="border-bottom">
                                                            <td class="d-flex pl-6">
                                                                <span class="bg-pink pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                                <div class="my-auto">
                                                                    <span class="mb-1 font-weight-semibold fs-17">{{$notice->title}}</span>
                                                                    <div class="clearfix"></div>
                                                                    <small class="text-muted fs-14">{{$notice->description}}</small>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                    </div> 
				</div>

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
								<div id="piechartTimeStatus"  class="chart"></div>
							</div>
						</div>
					</div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="donutchart"  class="chart"></div>
							</div>
						</div>
					</div>
                   
                    <div class="col-xl-3 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="piechartTeachingActiveDeactive"  class="chart"></div>
							</div>
						</div>
					</div>
                    <div class="col-xl-3 col-lg-6 col-md-12">
						<div class="card">
							<div class="card-body" style="position: relative;padding-bottom: 100%;overflow:hidden;">
								<div id="piechartNonTeachingActiveDeactive"  class="chart"></div>
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
                            <div class="col-xl-4 col-md-12 col-lg-12">
                                <div class="card mg-b-20 card--events">
                                    <div class="card-header border-bottom-0">
                                        <div class="card-title">Up Coming Birthday</div>
                                    </div>
                                    <div class="card-body p-0">
                                        @if(isset($birthdays))
                                            <div class="table-responsive">
                                                <table class="table transaction-table mb-0 text-nowrap">
                                                    <tbody>
                                                        @foreach($birthdays as $birthday)
                                                            <tr class="border-bottom">
                                                                <td class="d-flex  coming_holidays calendar-icon icons pl-6">
                                                                    @if(date('m-d', strtotime($birthday->DOB)) == date('m-d'))
                                                                        <span class="date_time bg-success-transparent bradius mr-3">
                                                                    @else
                                                                        <span class="date_time bg-primary-transparent bradius mr-3">
                                                                    @endif
                                                                        <span class="date fs-15">{{date('d', strtotime($birthday->DOB))}}</span>
                                                                        <span class="month fs-15">{{date('M', strtotime($birthday->DOB))}}</span>
                                                                    </span>
                                                                    <div class="mr-3 mt-0 mt-sm-1 d-block">
                                                                        <h6 class="mb-1 font-weight-semibold">{{$birthday->name}}</h6>
                                                                        <span class="clearfix"></span>
                                                                        <h6 class="mb-1 font-weight-semibold">{{$birthday->designationName}}</h6>
                                                                        <span class="clearfix"></span>
                                                                        <h6 class="mb-1 font-weight-semibold">{{$birthday->branchName}}</h6>
                                                                    </div>
                                                                    @if(date('m-d', strtotime($birthday->DOB)) == date('m-d'))
                                                                        &nbsp;
                                                                        <div class="text-right mr-10">
                                                                            <a class="btn btn-outline-orange mt-0" href="/employees/bdayWish/{{$birthday->id}}" id="wish" to="{{$birthday->name}}" bdayId="{{$birthday->id}}"><i class="fa fa-birthday-cake mr-2"></i>Send your Wishes</a>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
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
