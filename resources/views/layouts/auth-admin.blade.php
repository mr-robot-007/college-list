<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{((@$title!='') ? $title : env('APP_NAME'))}}</title>

	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/static/components/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="/static/components/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="/static/components/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="/static/components/plugins/jqvmap/jqvmap.min.css">
	@stack('shared-css')
	<!-- Theme style -->
	<link rel="stylesheet" href="/static/components/dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="/static/components/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="/static/components/plugins/daterangepicker/daterangepicker.css">

	<link rel="stylesheet" href="/static/css/web.css">
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> -->
	<link rel="stylesheet" href="/static/components/plugins/summernote/summernote-bs4.min.css">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


	@stack('custom-css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
	<!-- Preloader -->
	<!-- div class="preloader flex-column justify-content-center align-items-center">
		<img class="animation__shake" src="/static/components/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" />
	</div -->
	
	@include('shared.admin-header')
	@include('shared.admin-sidebar')

	@yield('content')

	@include('shared.admin-footer')
</div>
<!-- jQuery -->
<script src="/static/components/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/static/components/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>$.widget.bridge('uibutton', $.ui.button)</script>
<!-- Bootstrap 4 -->
<script src="/static/components/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script> -->
<script src="/static/components/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- daterangepicker -->
<script src="/static/components/plugins/moment/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
<script src="/static/components/plugins/daterangepicker/daterangepicker.js"></script>
<!-- overlayScrollbars -->
<script src="/static/components/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="/static/components/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
@stack('shared-js')
<!-- AdminLTE App -->
<script src="/static/components/dist/js/adminlte.js"></script>
<script src="/static/js/web.js"></script>
@stack('custom-js')
</body>
</html>