<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ ((@$title!='') ? $title : 'College List') }}
	</title>

	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/static/components/plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="/static/components/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/static/components/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="card card-outline card-primary">
			<!-- div class="card-header text-center"><a href="/" class="h1"><b>My</b>Ojt</a></div -->
			<div class="card-body">
				<p class="login-box-msg">Sign in to start your session</p>
				
				@if(Session::has('success'))
				<div class="card-block">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-xl-12">
							<div class="alert alert-success background-success">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
								<strong>Success!</strong> <span> {{ Session::get('success') }}</span>
							</div>
						</div>
					</div>
				</div>
				@endif
				
				@if(Session::has('error'))
					<div class="card-block">
						<div class="row">
							<div class="col-sm-12 col-md-12 col-xl-12">
								<div class="alert alert-danger background-danger">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i
											class="icofont icofont-close-line-circled text-white"></i></button>
									<strong>Error!</strong>
									<span>{{ Session::get('error') }}</span>
								</div>
							</div>
						</div>
					</div>
				@endif
				<form name="userLoginFRM" id="userLoginFRM"
					action="{{ route('admin.login.proceess') }}" method="post">
					{{ csrf_field() }}
					<div class="input-group mb-3">
						<input type="text" name="username" class="form-control" placeholder="Username / Email"
							maxlength="150" />
						<div class="input-group-append">
							<div class="input-group-text"><span class="fas fa-lock"></span></div>
						</div>
						@if($errors->has('email'))
							<div class="error text-danger">{{ $errors->first('email') }}</div>
						@endif
					</div>
					<div class="input-group mb-3">
						<input type="password" id="password" name="password" class="form-control" placeholder="Password"
							maxlength="50" />
						<div class="input-group-append">
							<div class="input-group-text password"><span class="fas fa-eye"></span></div>
						</div>
						@if($errors->has('password'))
							<div class="error text-danger">{{ $errors->first('password') }}</div>
						@endif
					</div>
					<div class="row">
						<div class="col-8">
							<a href="{{route('password.request')}}">Forgot password?</a>
							<div class="icheck-primary">
								<input type="checkbox" name="remember" id="remember" />
								<label for="remember">Remember Me</label>
							</div>
						</div>
						<div class="col-4 mt-3"><button type="submit" class="btn btn-primary btn-block">Sign In</button>
						</div>
					</div>
				</form>
				<!-- p class="mb-1"><a href="forgot-password.html">I forgot my password</a></p>
			<p class="mb-0"><a href="register.html" class="text-center">Register a new membership</a></p -->
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="/static/components/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="/static/components/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="/static/components/dist/js/adminlte.min.js"></script>

	<script>
		const passwordField = document.getElementById("password");
		const togglePassword = document.querySelector(".password span");
		console.log(passwordField, togglePassword); // Check if these elements are correctly selected

		togglePassword.addEventListener("click", function () {
			console.log(passwordField.type); // Debugging: Check the type of the password field
			if (passwordField.type === "password") {
				passwordField.type = "text";
				togglePassword.classList.remove("fa-eye");
				togglePassword.classList.add("fa-eye-slash");
			} else {
				passwordField.type = "password";
				togglePassword.classList.remove("fa-eye-slash");
				togglePassword.classList.add("fa-eye");
			}
		});
	</script>

</body>

</html>