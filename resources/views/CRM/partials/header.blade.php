<?php

$user = Auth::user();

?>
<div class="header-left active">
	<a href="/selectApplication/4" class="logo logo-normal">
		<img src="{{asset('landingpage/images/logo.png')}}" alt="Logo">
		<img src="{{asset('admin/assets/images/brand/logo-white.png')}}" class="white-logo" alt="Logo">
	</a>
	<a href="index-2.html" class="logo-small">
		<img src="{{asset('landingpage/images/logo.png')}}" alt="Logo">
	</a>
	<a id="toggle_btn" href="javascript:void(0);">
		<i class="ti ti-arrow-bar-to-left"></i>
	</a>
</div>
<!-- /Logo -->

<a id="mobile_btn" class="mobile_btn" href="#sidebar">
	<span class="bar-icon">
		<span></span>
		<span></span>
		<span></span>
	</span>
</a>

<div class="header-user">
	<ul class="nav user-menu">
		
		<!-- Search -->
		<li class="nav-item nav-search-inputs me-auto">
			<div class="top-nav-search">
				
			</div>
		</li>
		<!-- /Search -->

		<!-- Nav List -->
		<li class="nav-item nav-list">
			<ul class="nav">
				<li>
					<div>
						<a href="#" class="btn btn-icon border btn-menubar btnFullscreen">
							<i class="ti ti-maximize"></i>
						</a>
					</div>
				</li>
				<li class="dark-mode-list">
					<a href="javascript:void(0);" id="dark-mode-toggle" class="dark-mode-toggle">
						<i class="ti ti-sun light-mode active"></i>
						<i class="ti ti-moon dark-mode"></i>
					</a>
				</li>
			</ul>
		</li>
		<!-- /Nav List -->
		
		<!-- Profile Dropdown -->
		<li class="nav-item dropdown has-arrow main-drop">
			<a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
				<span class="user-info">
					<span class="user-letter">
						<img src="{{asset('/CRM/img/profiles/avatar-20.jpg')}}" alt="Profile">
					</span>
					<span class="badge badge-success rounded-pill"></span>
				</span>
			</a>
			<div class="dropdown-menu menu-drop-user">
				<div class="profilename">
					<a class="dropdown-item" href="index-2.html">
						<i class="ti ti-layout-2"></i> Dashboard
					</a>
					<a class="dropdown-item" href="profile.html">
						<i class="ti ti-user-pin"></i> My Profile
					</a>
					<a class="dropdown-item" href="/logout">
						<i class="ti ti-lock"></i> Logout
					</a>
				</div>
			</div>
		</li>
		<!-- /Profile Dropdown -->

	</ul>
</div>

<!-- Mobile Menu -->
<div class="dropdown mobile-user-menu">
	<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
	<div class="dropdown-menu">
		<a class="dropdown-item" href="index-2.html">
			<i class="ti ti-layout-2"></i> Dashboard
		</a>
		<a class="dropdown-item" href="profile.html">
			<i class="ti ti-user-pin"></i> My Profile
		</a>
		<a class="dropdown-item" href="login.html">
			<i class="ti ti-lock"></i> Logout
		</a>
	</div>
</div>
<!-- /Mobile Menu -->