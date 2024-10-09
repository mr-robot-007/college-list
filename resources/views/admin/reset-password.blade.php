
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Recover Password (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <div class="card-header text-center">
      <a href="{{route('admin.login')}}" class="h1"><b>Naman</b>College List</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
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
      <form action="{{route('password.update')}}" method="post">
        {{csrf_field()}}
        <input name="token" value = "{{@$token}}" hidden >
        <div class="input-group mb-3">
          <input name="email" type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="password" type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="confirmpassword" type="password" class="form-control" placeholder="Confirm Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Change password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="{{route('admin.login')}}">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/static/components/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/static/components/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/components/dist/js/adminlte.min.js"></script>
</body>
</html>
