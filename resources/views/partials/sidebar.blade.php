<?php
use App\Helpers\Utility;
$util = new Utility();
	$type = session()->get('applicationType');
	$circularNotification = session()->get('circularNotification');
	$user = Auth::user();
	$userType = $user->userType;
	$username = $user->username;
	$empId = $user->empId;
	$forInterviewer = $user->forInterviewer;
	$language = $user->language;
	$newUser = $user->newUser;
	$menus = explode(", ",$user->menus);
	$loginFlag = $user->loginFlag;
	$deptUserType = $user->deptUserType;
	$appointStatus = $user->appointStatus;
	$appointStatus = $user->appointStatus;
	$transAllowed = $user->transAllowed;
	
?>
<style>
      .blink {
        animation: blinker 0.95s linear infinite;
        font-weight: bold;
        font-family: sans-serif;
      }
      @keyframes blinker {
        50% {
          opacity: 0;
        }
      }
</style>
@if($newUser == 1)
	<div class="sticky">
		<div class="horizontal-main hor-menu clearfix">
			<div class="horizontal-mainwrapper container clearfix">
				<nav class="horizontalMenu clearfix">
					<ul class="horizontalMenu-list">
							@if($userType == '00' || $userType == '007')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="feather feather-home"></i>Dashboard</a>							
								</li>

								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Users<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/multiLogin/login">Multi Login</a></li>
										<li aria-haspopup="true"><a href="/userAllocations">Users</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Recruitment <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/jobApplications/interviewDList">Interview Drive</a></li>
									</ul>
								</li>

								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/employees">Employee</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="fa fa-user hor-icon"></i> Applications <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/employees">AGF</a></li>
												<li aria-haspopup="true"><a href="/employees">Exit Pass</a></li>
												<li aria-haspopup="true"><a href="/employees">Leave Application</a></li>
												<li aria-haspopup="true"><a href="/employees">Travelling Allow. Application</a></li>
											</ul>
										</li>
										<li aria-haspopup="true"><a href="/holidays">Holiday</a></li>
										<li aria-haspopup="true"><a href="/employees/UpdateInTime">Add Employee Time</a></li>
										<li aria-haspopup="true"><a href="/employees/appointmentPerson">Add Appointment Person</a></li>
									</ul>
								</li>

								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Payroll <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
								</li>
								
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Performance Mgmt. <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/employeeLetters/viewAppointmentLetter/{{$util->getLastAppointmentLetter($empId)}}">Appraisal</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i>Masters<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/contactusLandPage">Branch</a></li>
										<li aria-haspopup="true" ><a href="/departments">Department</a></li>
										<li aria-haspopup="true" ><a href="/designations">Designation</a></li>
										<li aria-haspopup="true" ><a href="/assets">Assets</a></li>
										<li aria-haspopup="true" ><a href="/leavePaymentPolicy">Leave Payment</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="/formsCirculars" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i>
										@if($circularNotification)
											<b class="blink">
										@endif
											Forms & Circulars
										@if($circularNotification)
											<b style="color:red;">&nbsp;{{$circularNotification}}</b></b> 
										@endif
									</a>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Reports <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">										
										<li aria-haspopup="true" ><a href="/reports/pendingInfo">Pending Info Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/newJoinee">New Joinee Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/exitPassReport">Exit Pass Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/AGFReport">AGF Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/leaveReport">Leave Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/travellingAllowReport">Travelling Allowance Report</a></li>
										<li aria-haspopup="true"><a href="/reports/extraWorkingReport">Extra Working Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/attendanceReport">Attendance Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/getNDCHistory">NDC History</a></li>
										<li aria-haspopup="true" ><a href="/reports/getContractReport">Contract Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/logTimeReport">Log Time Report</a></li>
									</ul>
								</li>
							@endif
						<!--End Super Admin login-->

						<!--General Manager Login-->
							@if($userType == '11' || $userType == '601')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
										Dashboard
									</a>							
								</li>

								@if($forInterviewer != 0)
									<li aria-haspopup="true">
										<a href="/candidateApplication/list" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
											Recruitment
										</a>							
									</li>
								@endif
								
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/employees">Employee</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
												Applications <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/empApplications/AGFList">AGF</a></li>
												<li aria-haspopup="true"><a href="/empApplications/exitPassList">Exit Pass</a></li>
												<li aria-haspopup="true"><a href="/empApplications/leaveList">Leave Application</a></li>
												<li aria-haspopup="true"><a href="/empApplications/travellingTranspList">Travelling Allow. Application</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="/empAttendances" class=""  style="font-size:14px;color: white;">
										<i class="fa fa-clock-o hor-icon"></i>
										{{($language == 1)?'Attendance': 'अटेंडन्स'}}
									</a>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i>{{($language == 1)?'Payroll': 'वेतनपट'}}<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										
										<li aria-haspopup="true" ><a href="/empPayroll/raiseReqSalarySlip">Salary Slips</a></li>
										<li aria-haspopup="true" ><a href="/empPayroll/form16">Form 16</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
											ESIC&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/admin/ESICBENIFITSEnglish.pdf" target="_blank">English</a></li>
												<li aria-haspopup="true"><a href="/admin/ESICBENEFITSMarathi.pdf" target="_blank">Marathi</a></li>
											</ul>
										</li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
											PF&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/admin/epfoenglish.pdf" target="_blank">English</a></li>
												<li aria-haspopup="true"><a href="/admin/epfomarathi.pdf" target="_blank">Marathi</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i>Apply<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/empApplications/empAGFList">AGF</a></li>
										<li aria-haspopup="true"><a href="/empApplications/empExitPassList">Exit Pass</a></li>
										<li aria-haspopup="true"><a href="/empApplications/empLeaveList">Leave Application</a></li>
									
										@if($transAllowed == 1)
											<li aria-haspopup="true"><a href="/empApplications/empTravellingAllownaceList">Travelling Allowance</a></li>
										@endif
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="/formsCirculars/employeeList" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> 
										{{($language == 1)?'Forms & Circulars': 'फॉर्म & सर्क्युलरस'}}
									</a>
								</li>
								
								<li aria-haspopup="true">
									<a href="/tickets/list" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> 
										{{($language == 1)?'Ticket For HR': 'फॉर्म & सर्क्युलरस'}}
									</a>
								</li>

								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/apply">Personal Resignation</a></li>
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
										<!-- <li aria-haspopup="true" ><a href="/employees/inActiveTeachingEmps">In Active Teaching</a></li>
										<li aria-haspopup="true" ><a href="/employees/inActiveNonTeachingEmps">In Active Non Teaching</a></li> -->
									</ul>
								</li>
								
								@if($appointStatus != "0")
									<li aria-haspopup="true">
										<a href="/appointments/requestList" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i> 
											Appointments
										</a>
									</li>
								@else
									<li aria-haspopup="true">
										<a href="/appointments" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i> 
											Appointments 
										</a>
									</li>
								@endif

								@if($empId == 1914)
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i> Reports <i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											<li aria-haspopup="true" ><a href="/reports/newJoinee">New Joinee Report</a></li>
											<li aria-haspopup="true" ><a href="/reports/exitPassReport">Exit Pass Report</a></li>
											<li aria-haspopup="true" ><a href="/reports/AGFReport">AGF Report</a></li>
											<li aria-haspopup="true" ><a href="/reports/leaveReport">Leave Report</a></li>
											<li aria-haspopup="true" ><a href="/reports/travellingAllowReport">Travelling Allowance Report</a></li>
											<li aria-haspopup="true"><a href="/candidateApplication/list">Recruitment Report</a></li>
											<li aria-haspopup="true" ><a href="/empAttendances/finalAttendanceSheet">Attendance Report</a></li>
											<li aria-haspopup="true" ><a href="/exitProces/standardProcess">NDC Report</a></li>
										</ul>
									</li>
								@endif
							@endif
						<!--End Employee login -->

						<!--Department Head login-->
							@if($userType == '21')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
										{{($language == 1)?'Dashboard':'होम'}}</a>							
								</li>
								@if($empId == 1118)
									<li aria-haspopup="true">
										<a href="/candidateApplication/list" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
										Candidate Applications</a>							
									</li>
								@endif
								@if($forInterviewer != 0)
									<li aria-haspopup="true">
										<a href="/candidateApplication/list" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
											Recruitment
										</a>							
									</li>
								@endif
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/employees">Employee</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">Applications <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/empApplications/AGFList">AGF</a></li>
												<li aria-haspopup="true"><a href="/empApplications/exitPassList">Exit Pass</a></li>
												<li aria-haspopup="true"><a href="/empApplications/leaveList">Leave Application</a></li>
												<li aria-haspopup="true"><a href="/empApplications/travellingTranspList">Travelling Allow. Application</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="/empAttendances" class=""  style="font-size:14px;color: white;">
										<i class="fa fa-clock-o"></i>
										{{($language == 1)?'Attendance': 'अटेंडन्स'}}
									</a>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i>{{($language == 1)?'Payroll': 'वेतनपट'}}<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/empPayroll/raiseReqSalarySlip">Salary Slips</a></li>
										<li aria-haspopup="true" ><a href="/empPayroll/form16">Form 16</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
											ESIC&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/admin/ESICBENIFITSEnglish.pdf" target="_blank">English</a></li>
												<li aria-haspopup="true"><a href="/admin/ESICBENEFITSMarathi.pdf" target="_blank">Marathi</a></li>
											</ul>
										</li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
											PF&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/admin/epfoenglish.pdf" target="_blank">English</a></li>
												<li aria-haspopup="true"><a href="/admin/epfomarathi.pdf" target="_blank">Marathi</a></li>
											</ul>
										</li>
									</ul>
								</li>
								
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i>Apply<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/empApplications/empAGFList">AGF</a></li>
										<li aria-haspopup="true"><a href="/empApplications/empExitPassList">Exit Pass</a></li>
										<li aria-haspopup="true"><a href="/empApplications/empLeaveList">Leave Application</a></li>
										@if($transAllowed == 1)
											<li aria-haspopup="true"><a href="/empApplications/empTravellingAllownaceList">Travelling Allowance</a></li>
										@endif
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="/formsCirculars/employeeList" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> 
										{{($language == 1)?'Forms & Circulars': 'फॉर्म & सर्क्युलरस'}}
									</a>
								</li>
								<li aria-haspopup="true">
									<a href="/tickets/list" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> 
										{{($language == 1)?'Ticket For HR': 'फॉर्म & सर्क्युलरस'}}
									</a>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/apply">Resignation</a></li>
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
										<!-- <li aria-haspopup="true" ><a href="/employees/inActiveTeachingEmps">In Active Teaching</a></li>
										<li aria-haspopup="true" ><a href="/employees/inActiveNonTeachingEmps">In Active Non Teaching</a></li> -->
									</ul>
								</li>
									@if($appointStatus != "0")
										<li aria-haspopup="true">
											<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> Appointments<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true" ><a href="/appointments/requestList">Appointment Request</a></li>
												<li aria-haspopup="true" ><a href="/appointments">Get Appointment</a></li>
											</ul>
										</li>
									@else
										<li aria-haspopup="true">
											<a href="/appointments" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> 
												Appointments
											</a>
										</li>
									@endif
							@endif
						<!--End Employee login -->

						<!--Employee login-->
							@if($userType == '31')
	  							@if($loginFlag == 1)
									<li aria-haspopup="true">
										<a href="/home" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
											{{($language == 1)?'Dashboard':'होम'}}</a>							
									</li>
									@if($forInterviewer != 0)
										<li aria-haspopup="true">
											<a href="/candidateApplication/list" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
												Recruitment
											</a>							
										</li>
									@endif
									<li aria-haspopup="true">
										<a href="/empAttendances" class=""  style="font-size:14px;color: white;">
											<i class="feather feather-codepen hor-icon"></i>
											{{($language == 1)?'Attendance': 'अटेंडन्स'}}
										</a>
									</li>
									
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i>{{($language == 1)?'Payroll': 'वेतनपट'}}<i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											
											<li aria-haspopup="true" ><a href="/empPayroll/raiseReqSalarySlip">Salary Slips</a></li>
											<li aria-haspopup="true" ><a href="/empPayroll/form16">Form 16</a></li>
											<li aria-haspopup="true">
												<a href="#" class="sub-icon">
												ESIC&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
												</a>
												<ul class="sub-menu">
													<li aria-haspopup="true"><a href="/admin/ESICBENIFITSEnglish.pdf" target="_blank">English</a></li>
													<li aria-haspopup="true"><a href="/admin/ESICBENEFITSMarathi.pdf" target="_blank">Marathi</a></li>
												</ul>
											</li>
											<li aria-haspopup="true">
												<a href="#" class="sub-icon">
												PF&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
												</a>
												<ul class="sub-menu">
													<li aria-haspopup="true"><a href="/admin/epfoenglish.pdf" target="_blank">English</a></li>
													<li aria-haspopup="true"><a href="/admin/epfomarathi.pdf" target="_blank">Marathi</a></li>
												</ul>
											</li>
										</ul>
									</li>
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i>Apply<i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											<li aria-haspopup="true"><a href="/empApplications/empAGFList">AGF</a></li>
											<li aria-haspopup="true"><a href="/empApplications/empExitPassList">Exit Pass</a></li>
											<li aria-haspopup="true"><a href="/empApplications/empLeaveList">Leave Application</a></li>
											@if($username == 'AWS2360')
												<li aria-haspopup="true"><a href="/empApplications/compOffApplication">CompOff Application</a></li>
												<li aria-haspopup="true"><a href="/empApplications/concessionList">Fees Concession</a></li>
												<li aria-haspopup="true"><a href="/creativeIdeas/list">Creative Ideas</a></li>
												<li aria-haspopup="true"><a href="/Projects/list">Projects</a></li>
												<li aria-haspopup="true"><a href="/Module/list">Module</a></li>
												<li aria-haspopup="true"><a href="/subModule/list">Sub Module</a></li>
												<li aria-haspopup="true"><a href="/developer/list">Developer</a></li>
												<li aria-haspopup="true"><a href="/projectCredentials/list">Project Credentials</a></li>




											@endif
											
											@if($transAllowed == 1)
												<li aria-haspopup="true"><a href="/empApplications/empTravellingAllownaceList">Travelling Allowance</a></li>
											@endif
										</ul>
									</li>
									<li aria-haspopup="true">
										<a href="/formsCirculars/employeeList" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i> 
											{{($language == 1)?'Forms & Circulars': 'फॉर्म & सर्क्युलरस'}}
										</a>
									</li>
									<li aria-haspopup="true">
										<a href="/tickets/list" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i> 
											{{($language == 1)?'Ticket For HR': 'फॉर्म & सर्क्युलरस'}}
										</a>
									</li>
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											<li aria-haspopup="true" ><a href="/exitProces/apply">Resignation</a></li>
										</ul>
									</li>
									
									@if($appointStatus != "0")
										<li aria-haspopup="true">
											<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> Appointments<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true" ><a href="/appointments/requestList">Appointment Request</a></li>
												<li aria-haspopup="true" ><a href="/appointments">Get Appointment</a></li>
											</ul>
										</li>
									@else
										<li aria-haspopup="true">
											<a href="/appointments" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> 
												Appointments
											</a>
										</li>
									@endif
								@else
	  								@if($deptUserType == '61')
										<li aria-haspopup="true">
											<a href="/dashboard" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
												{{($language == 1)?'Dashboard':'होम'}}</a>							
										</li>
										@if($forInterviewer != 0)
											<li aria-haspopup="true">
												<a href="/candidateApplication/list" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
													Recruitment
												</a>							
											</li>
										@endif
										<li aria-haspopup="true">
											<a href="/empAttendances" class=""  style="font-size:14px;color: white;">
												<i class="feather feather-codepen hor-icon"></i>
												{{($language == 1)?'Attendance': 'अटेंडन्स'}}
											</a>
										</li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> Performance Mgmt. <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true" ><a href="/employeeLetters/viewAppointmentLetter/{{$util->getLastAppointmentLetter($empId)}}">Appraisal</a></li>
											</ul>
										</li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i>{{($language == 1)?'Payroll': 'वेतनपट'}}<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												
												<li aria-haspopup="true" ><a href="/empPayroll/raiseReqSalarySlip">Salary Slips</a></li>
												<li aria-haspopup="true" ><a href="/empPayroll/form16">Form 16</a></li>
												<li aria-haspopup="true">
													<a href="#" class="sub-icon">
													ESIC&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
													</a>
													<ul class="sub-menu">
														<li aria-haspopup="true"><a href="/admin/ESICBENIFITSEnglish.pdf" target="_blank">English</a></li>
														<li aria-haspopup="true"><a href="/admin/ESICBENEFITSMarathi.pdf" target="_blank">Marathi</a></li>
													</ul>
												</li>
												<li aria-haspopup="true">
													<a href="#" class="sub-icon">
													PF&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
													</a>
													<ul class="sub-menu">
														<li aria-haspopup="true"><a href="/admin/epfoenglish.pdf" target="_blank">English</a></li>
														<li aria-haspopup="true"><a href="/admin/epfomarathi.pdf" target="_blank">Marathi</a></li>
													</ul>
												</li>
											</ul>
										</li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i>Apply<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/empApplications/empAGFList">AGF</a></li>
												<li aria-haspopup="true"><a href="/empApplications/empExitPassList">Exit Pass</a></li>
												<li aria-haspopup="true"><a href="/empApplications/empLeaveList">Leave Application</a></li>
												@if($transAllowed == 1)
													<li aria-haspopup="true"><a href="/empApplications/empTravellingAllownaceList">Travelling Allowance</a></li>
												@endif
											</ul>
										</li>
										<li aria-haspopup="true">
											<a href="/formsCirculars/employeeList" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> 
												{{($language == 1)?'Forms & Circulars': 'फॉर्म & सर्क्युलरस'}}
											</a>
										</li>
										<li aria-haspopup="true">
											<a href="/tickets/list" class="sub-icon"  style="font-size:14px;color: white;">
												<i class="feather feather-copy hor-icon"></i> 
												{{($language == 1)?'Ticket For HR': 'फॉर्म & सर्क्युलरस'}}
											</a>
										</li>
										
										@if($appointStatus != "0")
											<li aria-haspopup="true">
												<a href="/appointments/requestList" class="sub-icon"  style="font-size:14px;color: white;">
													<i class="feather feather-copy hor-icon"></i> 
													Appointments
												</a>
											</li>
										@else
											<li aria-haspopup="true">
												<a href="/appointments" class="sub-icon"  style="font-size:14px;color: white;">
													<i class="feather feather-copy hor-icon"></i> 
													Appointments
												</a>
											</li>
										@endif
									@endif
								@endif
							@endif
						<!---Employee Login -->

						<!-- HR Deparment -->
							@if($userType == '51'  || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
								@if($userType == '51')
									<li aria-haspopup="true">
										<a href="/home" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Dashboard</a>							
									</li>
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="fa fa-users hor-icon"></i> Recruitment  <i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											<li aria-haspopup="true"><a href="/candidateApplication/list">Candidate Applications</a></li>
											<li aria-haspopup="true"><a href="/jobApplications/interviewDList">Interview Drive</a></li>
										</ul>
									</li>								
								@else
									@if($forInterviewer != 0)
										<li aria-haspopup="true">
											<a href="/candidateApplication/list" class="" style="font-size:14px;color: white;"><i class="feather feather-home hor-icon"></i>
												Recruitment
											</a>							
										</li>
									@endif
								@endif
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/employees/nonTeachingEmps">Employees</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">Applications <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/empApplications/AGFList">AGF</a></li>
												<li aria-haspopup="true"><a href="/empApplications/exitPassList">Exit Pass</a></li>
												<li aria-haspopup="true"><a href="/empApplications/leaveList">Leave Application</a></li>
												<li aria-haspopup="true"><a href="/empApplications/travellingTranspList">Travelling Allow. Application</a></li>
											</ul>
										</li>
										<li aria-haspopup="true"><a href="/holidays">Holiday List</a></li>
										<li aria-haspopup="true"><a href="/hrPolicies">HR Policy</a></li>
										<li aria-haspopup="true"><a href="/empAdvRs">Salary Advance</a></li>
										<li aria-haspopup="true"><a href="/empAttendances/salaryHoldList">Salary Hold/Relase</a></li>
										<li aria-haspopup="true"><a href="/empDebits">Other Deduction</a></li>	
										<li aria-haspopup="true"><a href="/notices">Notice Board</a></li>
										<li aria-haspopup="true"><a href="/employees/changeTime">Change Office Time</a></li>
										<li aria-haspopup="true"><a href="/employees/changAuthority">Change Authority</a></li>
										<li aria-haspopup="true"><a href="/commonChanges">HRMS Change</a></li>
										<li aria-haspopup="true"><a href="/resourceRequest">Resource Requests</a></li>
										<li aria-haspopup="true"><a href="/employees/profileRequestList">Profile Update Requests</a></li>
									</ul>
								</li>
								
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-clock-o hor-icon"></i> Attendance <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										@if($user->id == 4481 || $user->userType == '00')
											<li aria-haspopup="true"><a href="/empAttendances/uploadAttendanceSheet">Upload Biometric Sheet</a></li>
										@endif
										<li aria-haspopup="true"><a href="/empAttendances">Attendance List</a></li>
										@if($userType == '51' || $userType == '201' || $userType == '401' || $userType == '501')
											<!-- <li aria-haspopup="true"><a href="/empAttendances/uploadAttendanceSheet">Upload Attendance Sheet</a></li> -->
											<li aria-haspopup="true"><a href="/empAttendances/finalAttendanceSheet">Final Attendance Sheet</a></li>
	  									@endif
									</ul>
								</li>
								@if($userType != '51')
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="fa fa-user hor-icon"></i> Accounts <i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											<li aria-haspopup="true"><a href="/accounts/salarySheet">Salary Sheet</a></li>
											<li aria-haspopup="true"><a href="/empAttendances">Attendance Sheet</a></li>
											<li aria-haspopup="true"><a href="/accounts">MR Report</a></li>
											<li aria-haspopup="true"><a href="/empAdvRs">Advance Amount</a></li>
										</ul>
									</li>
								@endif
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Performance Mgmt. <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/apprisal">Appraisal</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
										<li aria-haspopup="true" ><a href="/employees/deactive/teachingEmps">Deactive Teaching</a></li>
										<li aria-haspopup="true" ><a href="/employees/deactive/nonTeachingEmps">Deactive Non Teaching</a></li>
										<li aria-haspopup="true" ><a href="/employees/leftEmployeeList">Left Employees</a></li>
									</ul>
								</li>
								@if($userType == '51')
									<li aria-haspopup="true">
										<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
											<i class="feather feather-copy hor-icon"></i>Masters<i class="fa fa-angle-down horizontal-icon"></i>
										</a>
										<ul class="sub-menu">
											<li aria-haspopup="true" ><a href="/contactusLandPage">Branch</a></li>
											<li aria-haspopup="true" ><a href="/departments">Department</a></li>
											<li aria-haspopup="true" ><a href="/designations">Designation</a></li>
											<li aria-haspopup="true" ><a href="/assets">Assets</a></li>
											<li aria-haspopup="true" ><a href="/leavePaymentPolicy">Leave Payment</a></li>
										</ul>
									</li>
								@endif

								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Forms & Circulars <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/signFiles">Upload Signature</a></li>
										<li aria-haspopup="true" ><a href="/letterHeads">Letter Head</a></li>
										<li aria-haspopup="true" ><a href="/formsCirculars">Circulars</a></li>
										<li aria-haspopup="true"><a href="/employeeLetters/list/1">Offer Letter</a></li>
										<li aria-haspopup="true"><a href="/employeeLetters/list/2">Appointment Letter</a></li>										
										<li aria-haspopup="true"><a href="/employeeLetters/list/3">Aggrement</a></li>
										<li aria-haspopup="true" ><a href="/employeeLetters/list/4">Experience Letter</a></li>
										<li aria-haspopup="true" ><a href="/employeeLetters/list/5">Warning Letter</a></li>
										<li aria-haspopup="true" ><a href="/employeeLetters/concernList">Concern Letter</a></li>
										<li aria-haspopup="true" ><a href="/employeeLetters/list/7">Transfer Letter (Internal Branch)</a></li>
										<li aria-haspopup="true" ><a href="/employeeLetters/list/8">Transfer Letter (Internal Department)</a></li>
										<li aria-haspopup="true" ><a href="/employeeLetters/list/9">Promotion Letter</a></li>
										<li aria-haspopup="true" ><a href="/formsCirculars/pfWidrawalForm">PF Widrawal Form</a></li>
										<li aria-haspopup="true" ><a href="/formsCirculars/staffConLetter">Staff Concession Letter</a></li>
									</ul>
								</li>
								
								<li aria-haspopup="true">
									<a href="/tickets/allTickets" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-cart-plus"></i> 
										Tickets
									</a>
								</li>
								
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Reports <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/reports/apprisalReport">Appraisal Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/paidLeaveReport">Paid Leave Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/pendingInfo">Pending Info Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/newJoinee">New Joinee Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/exitPassReport">Exit Pass Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/AGFReport">AGF Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/leaveReport">Leave Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/travellingAllowReport">Travelling Allowance Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/attendanceReport">Attendance Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/getNDCHistory">NDC History</a></li>
										<li aria-haspopup="true" ><a href="/reports/getContractReport">Contract Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/recruitementReport">Recruitement Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/arrearsReport">Arrears Report</a></li>
										<li aria-haspopup="true"><a href="/reports/retentionReport">Retention Report</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Tasks <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="#">Checklist</a></li>
										<li aria-haspopup="true" ><a href="#">Extra Task</a></li>

									</ul>
								</li>
							@endif
						<!-- End HR Department -->
						
						<!-- Account Deparment -->
							@if($userType == '61')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Dashboard</a>							
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/employees/teachingEmps">Teaching List</a></li>
										<li aria-haspopup="true"><a href="/employees/nonTeachingEmps">Non Teaching List</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">Applications <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/empApplications/AGFList">AGF</a></li>
												<li aria-haspopup="true"><a href="/empApplications/travellingTranspList">Travelling Allow. Application</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Payroll <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/empAttendances">Attendance Sheet</a></li>
										<li aria-haspopup="true"><a href="/empAttendances/finalAttendanceSheet">Final Attendance Sheet</a></li>
										<li aria-haspopup="true"><a href="/empAdvRs">Salary Advance</a></li>
										<li aria-haspopup="true"><a href="/empDebits">Other Deduction</a></li>										
										<li aria-haspopup="true"><a href="/accounts">MR Report</a></li>
										<li aria-haspopup="true"><a href="/accounts/salarySheet">Salary Sheet</a></li>										
										<li aria-haspopup="true"><a href="/apprisal">Apprisal</a></li>	
										<li aria-haspopup="true"><a href="/accounts/retention">Retention</a></li>	
																			
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="/tickets/allTickets" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-cart-plus"></i> 
										Tickets
									</a>
								</li>
								<li aria-haspopup="true">
									<a href="/employees/uploadEmpExcel" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-cart-plus"></i> 
										Upload Emp Data
									</a>
								</li>
								
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Reports <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/reports/newJoinee">New Joinee Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/exitPassReport">Exit Pass Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/AGFReport">AGF Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/leaveReport">Leave Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/travellingAllowReport">Travelling Allowance Report</a></li>
										<li aria-haspopup="true"><a href="#">Deduction Report</a></li>
										<li aria-haspopup="true"><a href="/reports/extraWorkingReport">Extra Working Report</a></li>
										<li aria-haspopup="true"><a href="/reports/retentionReport">Retention Report</a></li>
										<li aria-haspopup="true" ><a href="/reports/logTimeReport">Log Time Report</a></li>
									</ul>
								</li>
							@endif	
						<!--End Account Department -->

						<!-- IT Deparment-->
							@if($userType == '71')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Dashboard</a>							
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
												Employee&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/employees/teachingEmps">Teaching List</a></li>
												<li aria-haspopup="true"><a href="/employees/nonTeachingEmps">Non Teaching List</a></li>
											</ul>
										</li>
									</ul>
								</li>
								
								<li aria-haspopup="true">
									<a href="/exitProces/standardProcess" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>NDC</a>							
								</li>
								<li aria-haspopup="true">
									<a href="/landingPage" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Landing Page</a>							
								</li>
							@endif	
						<!-- IT Deparment -->

						<!-- ERP Deparment -->
							@if($userType == '81')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Dashboard</a>							
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i>Fees Concession<i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
												Employee&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/employees/teachingEmps">Teaching List</a></li>
												<li aria-haspopup="true"><a href="/employees/nonTeachingEmps">Non Teaching List</a></li>
											</ul>
										</li>
										<li aria-haspopup="true"><a href="/employees/feesConcession">Other</a></li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
									</ul>
								</li>
								
							@endif	
						<!-- End Store Department -->

						<!-- Store Deparment -->
							@if($userType == '91')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Dashboard</a>							
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
												Employee&nbsp;&nbsp;<i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/employees/teachingEmps">Teaching List</a></li>
												<li aria-haspopup="true"><a href="/employees/nonTeachingEmps">Non Teaching List</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="feather feather-copy hor-icon"></i> Off Boarding <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true" ><a href="/exitProces/standardProcess">Standard Process</a></li>
									</ul>
								</li>
							@endif
						<!-- End Store Department -->

						<!-- Security Deparment -->
							@if($userType == '101')
								<li aria-haspopup="true">
									<a href="/home" class="" style="font-size:14px;color: white;"><i class="fa fa-home hor-icon"></i>Dashboard</a>							
								</li>
								<li aria-haspopup="true">
									<a href="#" class="sub-icon"  style="font-size:14px;color: white;">
										<i class="fa fa-user hor-icon"></i> Employees <i class="fa fa-angle-down horizontal-icon"></i>
									</a>
									<ul class="sub-menu">
										<li aria-haspopup="true"><a href="/employees">Employee</a></li>
										<li aria-haspopup="true">
											<a href="#" class="sub-icon">
												Applications <i class="fa fa-angle-down horizontal-icon"></i>
											</a>
											<ul class="sub-menu">
												<li aria-haspopup="true"><a href="/empApplications/AGFList">AGF</a></li>
												<li aria-haspopup="true"><a href="/empApplications/exitPassList">Exit Pass</a></li>
												<li aria-haspopup="true"><a href="/empApplications/leaveList">Leave Application</a></li>
												<li aria-haspopup="true"><a href="/empApplications/travellingTranspList">Travelling Allow. Application</a></li>
											</ul>
										</li>
									</ul>
								</li>
							@endif
						<!-- End Store Department -->
					</ul>
				</nav>
				<!--Nav-->
			</div>
		</div>
	</div>
@endif