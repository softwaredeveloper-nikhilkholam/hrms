<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="" name="description">
		<meta content="" name="author">
		<meta name="keywords" content=""/>
		<meta rel="icon" href="{{asset('admin/assets/images/brand/favicon.ico')}}" type="image/x-icon"/>
		<title>AWS - HRMS</title>
		<link rel="icon" href="{{asset('admin/assets/images/brand/favicon.ico')}}" type="image/x-icon"/>
		<link href="{{asset('admin/assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/css/skin-modes.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/plugins/animated/animated.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/plugins/icons/icons.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />
		<link href="{{asset('admin/assets/switcher/css/switcher.css')}}" rel="stylesheet"/>
		<link href="{{asset('admin/assets/switcher/demo.css')}}" rel="stylesheet"/>
	</head>
	<body style="background-image: url('/landingpage/sliders/<?php echo $imageName; ?>');background-repeat: no-repeat;background-repeat:no-repeat;background-size:contain;background-position:center;">
		<div class="page">
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col-md-9 p-md-0"></div>
							<div class="col-md-3 p-md-0">
								<div class="card p-2" style="border:2px solid;">
									<div class="pl-4 pt-4 pb-2 text-center">
										<a class="header-brand" href="index.html">
											<img src="{{asset('landingpage/images/logo.png')}}" style="width: 180px;" class="header-brand-img custom-logo" alt="Dayonelogo">
										</a>
									</div>
									<div class="p-5 pt-0">
										<h3 class="mb-2 text-center"><i class="fa fa-lock" style="font-size:30px;color:red;" aria-hidden="true"></i></h3>
										<b>@include('partials.messages1')</b>
									</div>
									<form method="POST" action="{{ route('postLogin') }}" class="card-body pt-3" id="login" name="login">
										@csrf
										<div class="form-group">
											<label class="form-label">Username</label>
											<input id="username" type="text" class="form-control" name="username" required autocomplete="username" autofocus>
											@error('username')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="form-group">
											<label class="form-label">Password</label>
											<input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
											@error('password')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="form-group">
											<label class="custom-control custom-checkbox">
												<input class="custom-control-input" type="checkbox" name="showPassword"  onclick="myFunction()">
												<span class="custom-control-label">Show Password</span>
											</label>	
										</div>
										<div class="submit">
											<button type="submit" class="btn btn-primary btn-block">
												{{ __('Login') }}
											</button>
										</div>
										
									</form>								
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="{{asset('admin/assets/plugins/jquery/jquery.min.js')}}"></script>
		<script src="{{asset('admin/assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{asset('admin/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
		<script src="{{asset('admin/assets/plugins/select2/select2.full.min.js')}}"></script>
		<script src="{{asset('admin/assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>
		<script src="{{asset('admin/assets/js/custom.js')}}"></script>
		<script src="{{asset('admin/assets/switcher/js/switcher.js')}}"></script>
		<script  type="text/javascript" src="{{asset('/admin/assets/js/demo.js')}}"></script>
	</body>
</html>
