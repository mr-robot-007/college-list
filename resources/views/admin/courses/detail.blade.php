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
					<li class="breadcrumb-item"><a href="{{route('admin.courses')}}">Courses</a></li>
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

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Subject</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->subject}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Status</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->status}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Institute</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->university_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Type</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->type}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Duration</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->duration}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Visit</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->visit}}</dd>

								@foreach($passouts as $item)
									@if(!empty($item['passout']))
										<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Passout/Fees</dt>
										<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">
											{{ $item['passout'] }} 
											@if(!empty($item['fees'])) / ₹{{ $item['fees'] }} @endif
										</dd>
									@endif
								@endforeach
								

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
@endsection
