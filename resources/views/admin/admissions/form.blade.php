
@if(isset($mode) && $mode=='Add')
    @php($childMenu = 'admissions')
@else
    @php($childMenu = 'admissions')
@endif

@if(isset($mode) && $mode == 'Edit' && !is_admin())
    @php($disabled = "disabled")
@else
    @php($disabled="")
@endif



@extends('layouts.auth-admin', ['parent' => 'admissions', 'child' => $childMenu])


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
                    @if(!@$isMyProfile) <li class="breadcrumb-item"><a href="{{route('admin.courses')}}">Admissions</a></li> @endif
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
                <!-- {{$errors}} -->
                <div class="card card-primary">
                    <div class="card-header">
						@if(@$mode != 'Add')
                        	<a href="{{route('admin.admission.detail', [encryptString(@$admission->id)])}}" class="float-right">View</a>
						@endif
                        <h3 class="card-title">{{((@$admission->id > 0) ? 'Edit' : 'Add')}} Admission Info</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($mode) && $mode=='Add')
                        <form name="addAdmissionFRM" id="addAdmissionFRM" action="{{route('admin.admission.store')}}" method="post" enctype="multipart/form-data">
                        @else
                        <form name="updateAdmissionFRM" id="updateAdmissionFRM" action="{{route('admin.admission.update', [encryptString(@$admission->id)])}}" method="post" enctype="multipart/form-data">
                        {!! method_field("PATCH") !!}
                        @endif
                        {{csrf_field()}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>University</label>
                                        <select name="university_id" id="university_id" {{$disabled}}  class="form-control" placeholder="Instructor Name" required>
											<option value=""> Select University </option>
											@foreach (@$institutes as $institute )
											<option value ="{{$institute->id}}" {{old('university_id',@$admission->university_id) == $institute->id ? "selected" : ""}}>{{$institute->university_name}}</option>
											@endforeach
										</select>
                                    </div>
                                </div>
                                <div class="col-md-6">
									<div class="form-group required">
										<label>Course</label>
										<select name="course_id" id="course_id" {{$disabled}}  value="{{old('course_id', @$admission->course_id)}}" class="form-control" placeholder="Instructor Name" required>
											<option value=""> Select Course </option>
										</select>
									</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Passout Year</label>
                                        <input type="text" name="passout" id="passout" {{$disabled}}  value="{{old('passout', @$admission->passout)}}" class="form-control" maxlength="255" placeholder="2020-24" required />
                                        @if($errors->has('passout'))
                                        <div class="error text-danger">{{ $errors->first('passout') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Total Fees</label>
                                        <input type="number" name="total" id="total" {{$disabled}}  value="{{old('total', @$admission->total)}}" class="form-control" maxlength="255" placeholder="Total" required />
                                        @if($errors->has('total'))
                                        <div class="error text-danger">{{ $errors->first('total') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Student Name</label>
                                        <input type="text" name="student_name" id="student_name" {{$disabled}}  value="{{old('student_name', @$admission->student_name)}}" class="form-control" maxlength="255" placeholder="Student Name" required />
                                        @if($errors->has('student_name'))
                                        <div class="error text-danger">{{ $errors->first('student_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Father's Name</label>
                                        <input type="text" name="father_name" id="father_name" {{$disabled}}  value="{{old('father_name', @$admission->father_name)}}" class="form-control" maxlength="255" placeholder="Father's Name" required />
                                        @if($errors->has('father_name'))
                                        <div class="error text-danger">{{ $errors->first('father_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Mother's Name</label>
                                        <input type="text" name="mother_name" id="mother_name" {{$disabled}}  value="{{old('mother_name', @$admission->mother_name)}}" class="form-control" maxlength="255" placeholder="Mother's Name" required />
                                        @if($errors->has('mother_name'))
                                        <div class="error text-danger">{{ $errors->first('mother_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Address</label>
                                        <textarea type="text" name="address" id="address" {{$disabled}}  class="form-control" maxlength="255" placeholder="Address" rows="3" required>{{old('address', @$admission->address)}}</textarea>
                                        @if($errors->has('address'))
                                        <div class="error text-danger">{{ $errors->first('address') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Mobile</label>
                                        <input type="text" name="mobile" id="mobile" {{$disabled}}  value="{{old('mobile', @$admission->mobile)}}" class="form-control" maxlength="15" placeholder="Mobile" required />
                                        @if($errors->has('mobile'))
                                        <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" {{$disabled}}  value="{{old('email', @$admission->email)}}" class="form-control" maxlength="255" placeholder="Email" required />
                                        @if($errors->has('email'))
                                        <div class="error text-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                                @for ($i = 1; $i <= 5; $i++)
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fees-{{$i}} Amount</label>
                                                <input type="number" name="fees{{$i.'_amount'}}" {{ @$admission['fees'.$i.'_status'] == 'Paid' || @$admission['fees'.$i.'_status'] == 'Approved'  ? $disabled : '' }} id="fees{{$i.'_amount'}}" 
                                                    value="{{ old('fees'.$i.'_amount', @$admission['fees'.$i.'_amount'] ?? '') }}" 
                                                    class="form-control" maxlength="255" placeholder="Fees-{{$i}} Amount" />
                                                @if($errors->has('fees'.$i.'_amount'))
                                                <div class="error text-danger">{{ $errors->first('fees'.$i.'_amount') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fees-{{$i}} Date</label>
                                                <input type="date" name="fees{{$i.'_date'}}" id="fees{{$i.'_date'}}"  {{ @$admission['fees'.$i.'_status'] == 'Paid' || @$admission['fees'.$i.'_status'] == 'Approved'  ? $disabled : '' }}
                                                    value="{{ old('fees'.$i.'_date', @$admission['fees'.$i.'_date'] ?? '') }}" 
                                                    class="form-control" placeholder="Date" />
                                                @if($errors->has('fees'.$i.'_date'))
                                                <div class="error text-danger">{{ $errors->first('fees'.$i.'_date') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fees-{{$i}} Transaction Id</label>
                                                <input type="text" name="fees{{$i.'_trans_id'}}" id="fees{{$i.'_trans_id'}}"  {{ @$admission['fees'.$i.'_status'] == 'Paid' || @$admission['fees'.$i.'_status'] == 'Approved'  ? $disabled : '' }}
                                                    value="{{ old('fees'.$i.'_trans_id', @$admission['fees'.$i.'_trans_id'] ?? '') }}" 
                                                    class="form-control" maxlength="255" placeholder="Transaction Id" />
                                                @if($errors->has('fees'.$i.'_trans_id'))
                                                <div class="error text-danger">{{ $errors->first('fees'.$i.'_trans_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fees-{{$i}} Status</label>
                                                <select name="{{'fees'.$i.'_status'}}" id="{{'fees'.$i.'_status'}}" {{ @$admission['fees'.$i.'_status'] == 'Paid' || @$admission['fees'.$i.'_status'] == 'Approved'  ? $disabled : '' }}
                                                 value="{{old(('fees'.$i.'_status'), 'Pending')}}" class="form-control" placeholder="Instructor Name" required>
                                                    <option value ="Pending" {{ @$admission['fees'.$i.'_status'] == 'Pending' ? "selected" : ""}}>{{@$admission['fees'.$i.'_status'] == "Approved" ? "Approved" : "Pending"}}</option>
                                                    <option value ="Paid" {{ @$admission['fees'.$i.'_status'] == 'Paid' ? "selected" : ""}}>{{"Paid"}}</option>
                                                    @if(is_admin())
                                                    <option value ="Approved" {{ @$admission['fees'.$i.'_status'] == 'Approved' ? "selected" : ""}}>{{"Approved"}}</option>
                                                    @endif()
                                                </select>
                                                @if($errors->has('fees_'.$i))
                                                <div class="error text-danger">{{ $errors->first('fees'.$i.'_status') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endfor

        

                                @if(@$mode == 'Edit' && is_admin())
                                <div class="col-md-6">
									<div class="form-group required">
										<label>Status</label>
										<select name="status" id="status" value="{{old('status', @$instructor_name)}}" class="form-control" placeholder="Instructor Name" required>
											<option value ="Active" {{ @$admission->status == 'Active' ? "selected" : ""}}>{{"Active"}}</option>
											<option value ="Inactive" {{ @$admission->status == 'Inactive' ? "selected" : ""}}>{{"Inactive"}}</option>
											<option value ="Blocked" {{ @$admission->status == 'Blocked' ? "selected" : ""}}>{{"Blocked"}}</option>
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
<script>
$(document).ready(function()
{
	
	var userType = "{{$currentUser->type}}";
    var oldCourse = "{{old('course_id',@$admission->course_id)}}";


    $("#university_id").change(function(){
        console.log('hi');
        var universityId = $('#university_id').val();
        $.ajax({url: "/university/courses/"+universityId, success: function(result){
            // console.log(result);
            var selectHTML = '';
            result[0].forEach(course => {
                selectHTML += `<option  ${course['id'] == oldCourse ? 'selected': ''} value="${course['id']}">${course['title']}-${course['subject'] ? course['subject'] : 'N/A'}</option>`
            });
            console.log(selectHTML);
            $("#course_id").html(selectHTML);
        }});
        });
	$('#institute_title, #course_name').change(function() {
        listTable.ajax.reload();
    });
    $('#university_id').change();
});
</script>

@endpush
