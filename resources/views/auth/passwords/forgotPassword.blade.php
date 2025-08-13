<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="DayOne - It is one of the Major Dashboard Template which includes - HR, Employee and Job Dashboard. This template has multipurpose HTML template and also deals with Task, Project, Client and Support System Dashboard." name="description">
		<meta content="Spruko Technologies Private Limited" name="author">
		<meta name="keywords" content="admin dashboard, dashboard ui, backend, admin panel, admin template, dashboard template, admin, bootstrap, laravel, laravel admin panel, php admin panel, php admin dashboard, laravel admin template, laravel dashboard, laravel admin panel"/>
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
	<body>
		<div class="page login-bg1">
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col-md-9 p-md-0"></div>
							<div class="col-md-3 p-md-0">
							<div class="card p-2">
								<div class="pl-4 pt-4 pb-2 text-center">
									<a class="header-brand" href="index.html">
										<img src="{{asset('landingpage/images/logo.png')}}" style="width: 180px;" class="header-brand-img custom-logo" alt="Dayonelogo">
										<img src="{{asset('landingpage/images/logo.png')}}" style="width: 180px;" class="header-brand-img custom-logo-dark" alt="Dayonelogo">
									</a>
								</div>
								<div class="p-5 pt-0">
									<h3 class="mb-2 text-center"><i class="fa fa-lock" style="font-size:30px;color:red;" aria-hidden="true"></i></h3>
									<b>@include('partials.messages1')</b>
								</div>
                                <form method="POST" action="{{ route('empForgotPassword') }}" class="card-body pt-3" id="login" name="login">
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
									<div class="submit">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            {{ __('Forgot Password') }}
                                        </button>
									</div>
									<div class="text-center mt-3">
										<a class="btn btn-link" href="/login">
											{{ __('Login') }}
										</a>
									</div>
								</form>								
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
