<?php
use App\Helpers\Utility;
$util = new Utility();
$userType = Session()->get('userType');

?>
<div class="container">
	<div class="row align-items-center flex-row-reverse">
		<div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
			Copyright © 2021 <a href="#">AWS</a>.&nbsp;&nbsp;Designed By Aaryans Group & Developed By Nikhil Kholam.&nbsp;&nbsp; All rights reserved.
		</div>
	</div>
</div>
@if($userType != '00')
	<!--Sidebar-right-->
	<div class="sidebar sidebar-right sidebar-animate">
		<div class="card-header border-bottom pb-5">
			<h4 class="card-title">Notifications </h4>
			<div class="card-options">
				<a href="#" class="btn btn-sm btn-icon btn-light  text-primary"  data-toggle="sidebar-right" data-target=".sidebar-right"><i class="feather feather-x"></i> </a>
			</div>
		</div>
		<div class="">
			
		</div>
	</div>
	<!--/Sidebar-right-->
@endif