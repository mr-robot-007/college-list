@if(isset($mode) && $mode=='Add')
	@php($childMenu = 'userlist')
@else
	@php($childMenu = 'userlist')
@endif

@extends('layouts.auth-admin', ['parent' => 'administration', 'child' => $childMenu])

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">{{((isset($isMyProfile) && $isMyProfile) ? 'My Profile' : 'Users')}}</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					@if(!@$isMyProfile) <li class="breadcrumb-item"><a href="{{route('admin.users')}}">Users</a></li> @endif
					<li class="breadcrumb-item active">{{((isset($isMyProfile) && $isMyProfile) ? 'My Profile' : $mode)}}</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<section class="content" id="scriptInitiater" data-method="userOBJ.initUserAddScript()">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				@include('shared.messages')
				
				<div class="card card-primary">
					<div class="card-header">
						@if(@$mode != 'Add')
						<a href="{{route('admin.user.detail', [encryptString(@$user->id)])}}" class="float-right">View</a>
						@endif
						<h3 class="card-title">{{((@$user->id > 0) ? 'Edit' : 'Add')}}</h3>
					</div>
					<div class="card-body">
						@if(!isset($mode))
						<form name="updateMyProfileFRM" id="updateMyProfileFRM" action="{{route('admin.profile.update')}}" method="post" enctype="multipart/form-data">
						@elseif(isset($mode) && $mode=='Add')
						<form name="addUserFRM" id="addUserFRM" action="{{route('admin.user.store')}}" method="post" enctype="multipart/form-data">
						@elseif(isset($mode) && $mode=='EditPassword')
						<form name="updateUserPasswordFRM" id="updateUserPasswordFRM" action="{{route('users.update.password')}}" method="post" enctype="multipart/form-data">
						@else
						<form name="updateUserProfileFRM" id="updateUserProfileFRM" action="{{route('admin.user.update', [encryptString($user->id)])}}" method="post" enctype="multipart/form-data">
						{!! method_field("PATCH") !!}
						@endif
						{{csrf_field()}}
						<div class="card-body">
							<div class="row">
								@if(@$mode != 'EditPassword')
								<div class="col-md-6">
									<div class="form-group required">
										<label>First Name</label>
										<input type="text" name="first_name" id="first_name" value="{{old('first_name', @$user->first_name)}}" class="form-control" maxlength="100" placeholder="First Name" required />
										@if($errors->has('first_name'))
			                            <div class="error text-danger">{{ $errors->first('first_name') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group required">
										<label>Last Name</label>
										<input type="text" name="last_name" id="last_name" value="{{old('last_name', @$user->last_name)}}" class="form-control" maxlength="100" placeholder="Last Name" required />
										@if($errors->has('last_name'))
			                            <div class="error text-danger">{{ $errors->first('last_name') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group required">
										<label>Email</label>
										<input type="text" name="email" id="email" value="{{old('email', @$user->email)}}" class="form-control" maxlength="250" placeholder="email" {{((@$user->id>0 && $user->email!='' && !is_admin()) ? 'readonly' : '' )}} required />
										@if($errors->has('email'))
			                            <div class="error text-danger">{{ $errors->first('email') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group required">
										<label>User Name</label>
										<input type="text" name="username" id="username" value="{{old('username', @$user->username)}}" class="form-control" placeholder="username" maxlength="100" {{((@$user->id>0 && $user->username!='' && !is_admin()) ? 'readonly' : '' )}} required />
										@if($errors->has('username'))
			                            <div class="error text-danger">{{ $errors->first('username') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Phone</label>
										<input type="text" name="phone" id="phone" value="{{old('phone', @$user->phone)}}" class="form-control" placeholder="Phone" maxlength="100" />
										@if($errors->has('phone'))
			                            <div class="error text-danger">{{ $errors->first('phone') }}</div>
			                            @endif
									</div>
								</div>
								@endif
								@if(@$mode != 'EditPassword' && (hasPermission('edit-user') || hasPermission('add-user') || is_customer()) && !@$isMyProfile)
									<div class="col-md-6">
										<div class="form-group required">
											<label>User Type</label>
											<select name="type" id="user_type" value="{{old('type', @$user->type)}}" class="form-control" placeholder="User Type" required>
												<option value=""> Select User Type </option>
												
												@if(is_admin())
												<option value="Admin" {{@$user->type =='Admin'? "selected" : "" }}> Admin </option>
												<option value="AltAdmin" {{@$user->type =='AltAdmin'? "selected" : "" }} > Alt Admin </option>
												<option value="User" {{@$user->type =='User'? "selected" : "" }} > User </option>
												@endif
											</select>
										</div>
									</div>
								@endif

								@if(isset($mode) && $mode=='Add')
								<div class="col-md-6">
									<div class="form-group required">
										<label>Password</label>
										<input type="password" name="password" id="password" value="{{old('password', @$user->password)}}" class="form-control" placeholder="Password" maxlength="100" required />
										@if($errors->has('password'))
			                            <div class="error text-danger">{{ $errors->first('password') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group required">
										<label>Confirm Password</label>
										<input type="password" name="confirm_password" id="confirm_password" value="{{old('confirm_password', @$user->confirm_password)}}" class="form-control" placeholder="Confirm Password" maxlength="100" required />
										@if($errors->has('confirm_password'))
			                            <div class="error text-danger">{{ $errors->first('confirm_password') }}</div>
			                            @endif
									</div>
									</div>
								@endif


								@if(@$mode=='EditPassword')
								<div class="col-md-12">
									<div class="form-group required">
										<label>Current Password</label>
										<input type="password" name="current_password" id="current_password" value="{{old('current_password')}}" class="form-control" placeholder="Current Password" maxlength="100" required />
										@if($errors->has('current_password'))
			                            <div class="error text-danger">{{ $errors->first('current_password') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group required">
										<label>New Password</label>
										<input type="password" name="new_password" id="new_password" value="{{old('new_password')}}" class="form-control" placeholder="New Password" maxlength="100" required />
										@if($errors->has('new_password'))
			                            <div class="error text-danger">{{ $errors->first('new_password') }}</div>
			                            @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group required">
										<label>Cofirm New Password</label>
										<input type="password" name="confirm_new_password" id="confirm_new_password" value="{{old('confirm_new_password')}}" class="form-control" placeholder="Confirm New Password" maxlength="100" required />
										@if($errors->has('confirm_new_password'))
			                            <div class="error text-danger">{{ $errors->first('confirm_new_password') }}</div>
			                            @endif
									</div>
								</div>
								@endif

								


							</div>
							
							
						<div class="card-footer"><button type="submit" class="btn btn-primary">Submit</button></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('shared-js')
<script type="text/javascript" src="/static/js/user.js"></script>
@endpush