<?php
use App\EmpDet;
$empCardStatus = EmpDet::where('id', Auth::user()->empId)->value('idCardStatus');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
	<head>
		@include('partials.head')
	</head>
	<body class="" id="index1">
		<div id="global-loader">
			<img src="{{asset('admin/assets/images/svgs/loader.svg')}}" alt="loader">
		</div>
		<div class="page">
			<div class="page-main">
				<div class="hor-header header">
					@include('partials.header')
					<style>
						.required-asterisk {
							color: red !important;
							font-size: 22px;
							margin-left: 4px; /* Optional: adds space like &nbsp; */
						}
					</style>
				</div>
				@if(Auth::user()->userType == '11' || Auth::user()->userType == '21' || Auth::user()->userType == '31')
					@if($empCardStatus != 'Pending')
						@include('partials.sidebar') 
					@endif
				@else
					@include('partials.sidebar') 
				@endif
				
				<div class="main-content" style="background-image: url('/admin/assets/images/photos/logo2.jpg');" style="max-width: 2000px !important;">
					@include('partials.messages')
					@yield('content')
				</div><!-- end app-content-->
			</div>
			<footer class="footer">
                @include('partials.footer')            
			</footer>
		</div>
		<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>
		@include('partials.script')
	</body>
</html>