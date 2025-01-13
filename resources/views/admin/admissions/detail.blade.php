@if(isset($mode) && $mode=='Add')
	@php($childMenu = 'admissions')
@else
	@php($childMenu = 'admissions')
@endif


@extends('layouts.auth-admin', ['parent' => 'admissions', 'child' => $childMenu])

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Admission Info</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{route('admin.admissions')}}">Admissions</a></li>
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
						<a href="{{route('admin.admission.edit', [encryptString(@$admission->id)])}}" class="float-right">Edit</a>
						@endif()
						<h3 class="card-title">Course Detail</h3>
					</div>
					<div class="card-body">
						<div class="card-body">
							<dl class="row">
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Center</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$center->first_name . ' '. $center->last_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Student Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->student_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Father Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->father_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Mother Name</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->mother_name}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">University</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$institute->university_name}}</dd>
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Course</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$course->title .'-' .$course->subject}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Total</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->total}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Passout</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->passout}}</dd>

								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Address</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->address}}</dd>
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Mobile</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->mobile}}</dd>
								<dt class="col-xl-2 col-lg-2 col-md-3 col-sm-4">Email</dt>
								<dd class="col-xl-10 col-lg-10 col-md-9 col-sm-8">{{@$admission->email}}</dd>
								<br>
								<table class="table">
									<thead>
										<tr>
											<th>Fees</th>
											<th>Amount</th>
											<th>Date</th>
											<th>Transaction ID</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										@foreach($fees as $fee)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>₹{{ $admission->{$fee['amount']} ?? 'N/A' }}</td>
												<td>{{ $admission->{$fee['date']} ?? 'N/A' }}</td>
												<td>{{ $admission->{$fee['trans_id']} ?? 'N/A' }}</td>
												<td>{{ $admission->{$fee['status']} ?? 'N/A' }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
								

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
@endsection
