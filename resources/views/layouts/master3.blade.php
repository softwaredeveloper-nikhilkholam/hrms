<!DOCTYPE html>
<html lang="en" dir="ltr">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
	<head>
		@include('partials.head')
	</head>
	<body class="" id="index1">
		<!-- <div id="global-loader" >
			<img src="{{asset('admin/assets/images/svgs/loader.svg')}}" alt="loader">
		</div> -->
		<div class="page">
			<div class="page-main">
				<div class="hor-header header">
					@include('partials.header2')
					<style>
					.required-asterisk {
						color: red !important;
						font-size: 22px;
						margin-left: 4px; /* Optional: adds space like &nbsp; */
						}
					</style>
				</div>
				<div class="main-content">
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