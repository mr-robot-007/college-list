@if(Session::has('error'))
<div class="card-block">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-xl-12">
			<div class="alert alert-danger background-danger">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<strong>Error!</strong> <span>{{ Session::get('error') }}</span>
			</div>
		</div>
	</div>
</div>
@endif

@if(Session::has('customAlertMessage'))
<div class="card-block">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-xl-12">
			<div class="alert alert-danger background-danger">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<strong>Alert!</strong> <span>{!! Session::get('customErrorMessage') !!}</span>
			</div>
		</div>
	</div>
</div>
@endif

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

@if(Session::has('customWarningMessage'))
<div class="card-block">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-xl-12">
			<div class="alert alert-warning background-warning">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-times-circle-o"></i></button>
				<strong>Warning!</strong> <span> {!! Session::get('customWarningMessage')  !!}</span>
			</div>
		</div>
	</div>
</div>
@endif