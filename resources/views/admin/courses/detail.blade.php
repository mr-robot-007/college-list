@if(isset($mode) && $mode=='Add')
	@php($childMenu = 'addcourse')
@else
	@php($childMenu = 'courselist')
@endif

@extends('layouts.auth-admin', ['parent' => 'courses', 'child' => $childMenu])

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Course</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{route('admin.course.list')}}">Courses</a></li>
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
						@if(hasPermission('edit-course') ||is_customer())
						<a href="{{route('admin.course.edit', [encryptString(@$course->id)])}}" class="float-right">Edit</a>
						@endif()
						<h3 class="card-title">Course Detail</h3>
					</div>
					<div class="card-body">
						<div class="card-body">
							<dl class="row">
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Title</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->title}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Description</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->description}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Instructor Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$instructor_name}}</dd>
								
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Status</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->status}}</dd>

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
@endsection
