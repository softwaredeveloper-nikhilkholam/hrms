<?php
use App\Helpers\Utility;
$util = new Utility();
$userRole = Session()->get('userRole');
$name = Session()->get('name');
$userType = Session()->get('userType');
$userProfile = Session()->get('userProfile');
$profilePhoto = Session()->get('profilePhoto');
$user = Auth::user();
$empId = $user->empId;
$loginFlag = $user->loginFlag;
 
$appCount=0; 
if($empId != 0)
{
	if($userType == '31')
		$appCount = count($util->getPersonalNotifications());
	else
		$appCount = count($util->getNotifications()) +  count($util->getPersonalNotifications());

	$duty = $util->getDuty($empId);
}
else
{
	if($userType == '51' || $userType == '61')
		$appCount = count($util->getNotifications());
	else
		$appCount=0;
}



?>
<style>
	.fa-stack[data-count]:after{
  position:absolute;
  right:0%;
  top:1%;
  content: attr(data-count);
  font-size:50%;
  padding:.6em;
  border-radius:700px;
  line-height:.20em;
  color: white;
  background:rgba(255,0,0,.85);
  text-align:center;
  min-width:1em;
  font-weight:bold;
}

.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
	</style>
<div class="container">
	<div class="d-flex">
		<a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a>
			<a class="header-brand" href="/home">
			<img  src="{{asset('landingpage/images/logo.png')}}" style="width: 180px;" class="header-brand-img desktop-lgo" alt="AWS-HRMS">
			<img src="{{asset('admin/assets/images/brand/logo-white.png')}}" class="header-brand-img dark-logo" alt="AWS-HRMS">
			<img src="{{asset('admin/assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="AWS-HRMS">
		</a>
		@if($userType == '00')
			<a class="header-brand" href="/home">
				<div class="mt-1">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-home" style="font-size:40px;" aria-hidden="true"></i>
				</div><!-- SEARCH -->
			</a>
		@endif
		<div class="d-flex order-lg-2 my-auto ml-auto">
			<!-- <div class="dropdown profile-dropdown">
				<a href="/notificationList">
					<span class="fa-stack fa-2x" id="showNotify" data-count="{{$appCount}}">
						<i class="fa fa-circle fa-stack-2x"></i>
						<i class="fa fa-bell fa-stack-1x fa-inverse"></i>
					</span>
				</a>
			</div> -->
			<div class="dropdown profile-dropdown">
				<a href="#" class="nav-link pr-1 pl-0 leading-none" data-toggle="dropdown">
					<span>
						@if($userType == '00')
							<img src="{{asset('admin/images/employees/aws.png')}}" alt="img" class="avatar avatar-md bradius">
						@else
							@if($profilePhoto == '')
								<img src="{{asset('admin/images/employees/aws.png')}}" alt="img" class="avatar avatar-md bradius">
							@else  
								<img alt="img" class="avatar avatar-md bradius" src="/admin/profilePhotos/{{$profilePhoto}}"> </img>   
							@endif
						@endif
					</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
					<div class="p-3 text-center border-bottom">
						<a href="#" class="text-center user pb-0 font-weight-bold">{{$name}}</a>
						<p class="text-center user-semi-title">{{$userRole}}</p>
					</div>
					@if($userType == '31')
						<a href="/changeLanguage/1" class="dropdown-item d-flex"> 
							<div class="d-flex"> <span class="my-auto">English</span> </div>
						</a>
						<a href="/changeLanguage/2" class="dropdown-item d-flex">
							<div class="d-flex"> <span class="my-auto">मराठी</span> </div>
						</a>
					@endif
					
					@if($empId != '')
						<a class="dropdown-item d-flex" href="/employees/{{$empId}}">
							<i class="feather feather-user mr-3 fs-16 my-auto"></i>
							<div class="mt-1">Profile</div>
						</a>
						<a class="dropdown-item d-flex" href="/employees/{{$empId}}/profileInfo">
							<i class="feather feather-user mr-3 fs-16 my-auto"></i>
							<div class="mt-1">Update Profile Photo</div>
						</a>
						<a class="dropdown-item d-flex" data-toggle="tooltip" title="{{$duty}}">
							<i class="feather feather-edit-2 mr-3 fs-16 my-auto"></i>
							<div class="mt-1" class="tooltip">Assigned Duty</div>
						</a>						
					@endif
					<a class="dropdown-item d-flex" href="/empChangePassword">
						<i class="feather feather-edit-2 mr-3 fs-16 my-auto"></i>
						<div class="mt-1">Change Password</div>
					</a>
					
					<a class="dropdown-item d-flex" href="/logout">
						<i class="feather feather-power mr-3 fs-16 my-auto"></i>
						<div class="mt-1">Sign Out</div>
					</a>

				</div>
			</div>
		</div>
	</div>
</div>
