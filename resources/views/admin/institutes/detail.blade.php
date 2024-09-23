@if(isset($mode) && $mode=='Add')
	@php($childMenu = 'addinstitute')
@else
	@php($childMenu = 'institutelist')
@endif

@extends('layouts.auth-admin', ['parent' => 'institutes', 'child' => $childMenu])

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Institute</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{route('admin.institutes')}}">Institutes</a></li>
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
						<a href="{{route('admin.institute.edit', [encryptString(@$institute->id)])}}" class="float-right">Edit</a>

						<h3 class="card-title">Institute Detail</h3>
					</div>
					<div class="card-body">
						<div class="card-body">
							<dl class="row">
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Title</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->title}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Person Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->person_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Person Contact</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->person_contact}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Address</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->address}}</dd>
								
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Status</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->status}}</dd>

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
@endsection
