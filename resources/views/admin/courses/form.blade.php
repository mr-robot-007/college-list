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
                <h1 class="m-0">{{((isset($isMyProfile) && $isMyProfile) ? 'My Profile' : 'Courses')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                    @if(!@$isMyProfile) <li class="breadcrumb-item"><a href="{{route('admin.courses')}}">Courses</a></li> @endif
                    <li class="breadcrumb-item active">{{@$mode}}</li>
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
                        	<a href="{{route('admin.course.detail', [encryptString(@$course->id)])}}" class="float-right">View</a>
						@endif
                        <h3 class="card-title">{{((@$course->id > 0) ? 'Edit' : 'Add')}} Course</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($mode) && $mode=='Add')
                        <form name="addCourseFRM" id="addCourseFRM" action="{{route('admin.course.store')}}" method="post" enctype="multipart/form-data">
                        @else
                        <form name="updateCourseFRM" id="updateCourseFRM" action="{{route('admin.course.update', [encryptString($course->id)])}}" method="post" enctype="multipart/form-data">
                        {!! method_field("PATCH") !!}
                        @endif
                        {{csrf_field()}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Title</label>
                                        <input type="text" name="title" id="title" value="{{old('title', @$course->title)}}" class="form-control" maxlength="255" placeholder="Title" required />
                                        @if($errors->has('title'))
                                        <div class="error text-danger">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
									<div class="form-group required">
										<label>University</label>
										<select name="institute_id" id="institute_id" value="{{old('institute_id', @$institute_id)}}" class="form-control" placeholder="Instructor Name" required>
											<option value=""> Select University </option>
											@foreach (@$institutes as $institute )
											<option value ="{{$institute->id}}" {{@$course->institute_id == $institute->id ? "selected" : ""}}>{{$institute->university_name}}</option>
											@endforeach
										</select>
									</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Duration</label>
                                        <input type="text" name="duration" id="duration" value="{{old('duration', @$course->duration)}}" class="form-control" maxlength="255" placeholder="Duration" required />
                                        @if($errors->has('duration'))
                                        <div class="error text-danger">{{ $errors->first('duration') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Type</label>
                                        <input type="text" name="type" id="type" value="{{old('type', @$course->type)}}" class="form-control" maxlength="255" placeholder="Type" required />
                                        @if($errors->has('type'))
                                        <div class="error text-danger">{{ $errors->first('type') }}</div>
                                        @endif  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Visit</label>
                                        <input type="text" name="visit" id="visit" value="{{old('visit', @$course->visit)}}" class="form-control" maxlength="255" placeholder="Visit" required />
                                        @if($errors->has('visit'))
                                        <div class="error text-danger">{{ $errors->first('visit') }}</div>
                                        @endif
                                    </div>
                                </div>
                                @for ($i = 1; $i <= 10; $i++)
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Passout-{{$i}}</label>
                                                <input type="text" name="passout_{{$i}}" id="passout_{{$i}}" value="{{old('passout_'.$i, @$course['passout_'.$i])}}" class="form-control" maxlength="255" placeholder="Passout-{{$i}}" />
                                                @if($errors->has('passout_'.$i))
                                                <div class="error text-danger">{{ $errors->first('passout_'.$i) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fees-{{$i}}</label>
                                                <input type="text" name="fees_{{$i}}" id="fees_{{$i}}" value="{{old('fees_'.$i, @$course['fees_'.$i])}}" class="form-control" maxlength="255" placeholder="Fees-{{$i}}" />
                                                @if($errors->has('fees_'.$i))
                                                <div class="error text-danger">{{ $errors->first('fees_'.$i) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endfor

        

                                @if(@$mode == 'Edit')
                                <div class="col-md-6">
									<div class="form-group required">
										<label>Status</label>
										<select name="status" id="status" value="{{old('status', @$instructor_name)}}" class="form-control" placeholder="Instructor Name" required>
											<option value ="Active" {{ $course->status == 'Active' ? "selected" : ""}}>{{"Active"}}</option>
											<option value ="Inactive" {{ $course->status == 'Inactive' ? "selected" : ""}}>{{"Inactive"}}</option>
											<option value ="Blocked" {{ $course->status == 'Blocked' ? "selected" : ""}}>{{"Blocked"}}</option>
										</select>
									</div>
								</div>
                                @endif
								
                            </div>
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
