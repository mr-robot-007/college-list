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
                <h1 class="m-0">{{((isset($isMyProfile) && $isMyProfile) ? 'My Profile' : 'Universities')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                    @if(!@$isMyProfile) <li class="breadcrumb-item"><a href="{{route('admin.institutes')}}">Universities</a></li> @endif
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
                        <a href="{{route('admin.institute.detail', [encryptString(@$institute->id)])}}" class="float-right">View</a>
                        @endif
                        <h3 class="card-title">{{((@$institute->id > 0) ? 'Edit' : 'Add')}} University</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($mode) && $mode=='Add')
                        <form name="addInstituteFRM" id="addInstituteFRM" action="{{route('admin.institute.store')}}" method="post" enctype="multipart/form-data">
                        @else
                        <form name="updateInstituteFRM" id="updateInstituteFRM" action="{{route('admin.institute.update', [encryptString($institute->id)])}}" method="post" enctype="multipart/form-data">
                        {!! method_field("PATCH") !!}
                        @endif
                        {{csrf_field()}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>University Name</label>
                                        <input type="text" name="university_name" id="university_name" value="{{old('university_name', @$institute->university_name)}}" class="form-control" maxlength="255" placeholder="University Name" required />
                                        @if($errors->has('university_name'))
                                        <div class="error text-danger">{{ $errors->first('university_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Approved By</label>
                                        <input type="text" name="approved_by" id="approved_by" value="{{old('approved_by', @$institute->approved_by)}}" class="form-control" maxlength="255" placeholder="Approved By" required />
                                        @if($errors->has('approved_by'))
                                        <div class="error text-danger">{{ $errors->first('approved_by') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>University Website</label>
                                        <input type="text" name="university_website" id="university_website" value="{{old('university_website', @$institute->university_website)}}" class="form-control" maxlength="255" placeholder="University Website" required />
                                        @if($errors->has('university_website'))
                                        <div class="error text-danger">{{ $errors->first('university_website') }}</div>
                                        @endif
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Verification</label>
                                        <input type="text" name="verification" id="verification" value="{{old('verification', @$institute->verification)}}" class="form-control" maxlength="255" placeholder="Verification" required />
                                        @if($errors->has('verification'))
                                        <div class="error text-danger">{{ $errors->first('verification') }}</div>
                                        @endif
                                    </div>
                                </div>
								@if(@$mode == 'Edit' && is_admin())
                                <div class="col-md-6">
									<div class="form-group required">
										<label>Status</label>
										<select name="status" id="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="Active" {{old('status', @$institute->status)=='Active'?'selected':''}}>Active</option>
											<option value="Inactive" {{old('status', @$institute->status)=='Inactive'?'selected':''}}>Inactive</option>
										</select>
									</div>
								</div>
								@endif()
                            </div>
                        </div>

                        <hr>

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
