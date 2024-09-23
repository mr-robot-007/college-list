@if(isset($mode) && $mode=='Add')
	@php($childMenu = 'adduser')
@else
	@php($childMenu = 'userlist')
@endif

@extends('layouts.auth-admin', ['parent' => 'users', 'child' => $childMenu])

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">User</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{route('admin.users')}}">Users</a></li>
					<li class="breadcrumb-item active">Detail</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				@include('shared.messages')
				
				<div class="card card-primary">
					<div class="card-header">
						@if(is_admin())
						<a href="{{route('admin.user.edit', [encryptString(@$user->id)])}}" class="float-right">Edit</a>
						@endif()
						<h3 class="card-title">User Detail</h3>
					</div>
					<div class="card-body">
						<div class="card-body">
							<dl class="row">
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">First Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->first_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Last Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->last_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Email</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->email}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">User Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->username}}</dd>

								<!-- dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Password</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->password}}</dd -->

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">User Type</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->type}}</dd>

								
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Phone</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->phone}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Address</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$user->address}}</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
@endsection
