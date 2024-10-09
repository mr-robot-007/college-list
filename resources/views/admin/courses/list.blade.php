@extends('layouts.auth-admin', ['parent' => 'administration', 'child' => "courselist"])

@push("shared-css")
<link rel="stylesheet" href="/static/components/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="/static/components/plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
<link rel="stylesheet" href="/static/components/plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
@endpush

@push("shared-js")
<script src="/static/components/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/static/components/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/static/components/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/static/components/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/static/components/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/static/components/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
@endpush

@push('custom-css')
<style>
    #filterInstitutesLists {
        font-size: 0.9rem;
    }
    #filterInstitutesLists th,
    #filterInstitutesLists td {
        white-space: nowrap;
        padding: 0.5rem;
    }
    .dataTables_wrapper .dataTables_scroll {
        margin-bottom: 0.5em;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0.5em;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 0.5em;
    }
    #filterInstitutesLists td:first-child {
        font-weight: bold;
    }
</style>
@endpush

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Manage Courses</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item active">Courses</li>
				</ol>
			</div>
		</div>
		@if(is_admin())
		<div class="row">
			<div class="col-md-4">
				<div class="form-group required">
					<label>Course</label>
					<select name="course_name" id="course_name" class="form-control" placeholder="Course Title" required>
                        <option value=""> Select Course </option>
						@foreach($courses as $course)
							<option value="{{ $course }}">{{ $course }}</option>
						@endforeach
                    </select>
				</div>
			</div>
			@if(is_admin())
			<div class="col-md-4">
				<div class="form-group required">
					<label>University</label>
					<select name="institute_id" id="institute_title" class="form-control" placeholder="Institute Title" required>
                        <option value=""> Select University </option>
                        @foreach ($institutes as $institute )
                            <option value ="{{$institute->id}}">{{$institute->university_name}}</option>
                        @endforeach
                    </select>
				</div>
			</div>
			@endif
			<div class="col-md-2">
				<label>&nbsp;</label><br />
				<a href="{{route("admin.courses")}}" class="btn btn btn-default">Reset</a>
			</div>
		</div>
		@endif
	</div>
</div>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				@include('shared.messages')
				
				<div class="card card-primary">
					<div class="card-header">
						@if(hasPermission('add-course') || is_customer())
						<a href="{{route('admin.course.new')}}" class="float-right">Add</a>
						@endif
						<h3 class="card-title">List</h3>
					</div>
					<div class="card-body">
						<table id="filterInstitutesLists" class="table table-bordered table-hover">
						<tfoot>
						<tr>
							<th>S.No.</th>
							<th>University Name</th>
							<th>Title</th>	
							<th>Type</th>
							<th>Duration</th>
							<th>Visit</th>
							<th>Eligibility</th>
							<th>Passout-1 / Fees-1</th>
							<th>Passout-2 / Fees-2</th>
							<th>Passout-3 / Fees-3</th>
							<th>Passout-4 / Fees-4</th>
							<!-- <th>Passout-5 / Fees-5</th>
							<th>Passout-6 / Fees-6</th>
							<th>Passout-7 / Fees-7</th>
							<th>Passout-8 / Fees-8</th>
							<th>Passout-9 / Fees-9</th>
							<th>Passout-10 / Fees-10</th> -->
							<th>Status</th>
							<th style="width:100px;">Action</th>
						</tr>
						</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
@endsection

@push('custom-js')
<script>
$(document).ready(function()
{
	$('#filterInstitutesLists tfoot th').each(function()
	{
		let totalCol = $('#filterInstitutesLists tfoot th').length;
		if($(this).index()<(totalCol-1))
		{
			var title = $(this).text();
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
		}
	});
	var userType = "{{$currentUser->type}}";

	var listTable = $('#filterInstitutesLists').DataTable({
		"iDisplayLength": 10,
		"aLengthMenu": [10, 25, 50, 100],
		'processing': true,
		'serverSide': true,
		'serverMethod': 'get',
		'scrollCollapse': true,
		'scrollY': false,
		'scrollX': true,		
		'responsive': false,
		"autoWidth": false,
		"lengthChange": true,
		'order': [[1, 'asc']],
		initComplete: function()
		{
			this.api()
                .columns()
                .every(function()
                {
                	var that = this;

                	$('input', this.footer()).on('keyup change clear', function()
                	{
                		if(that.search() !== this.value)
                		{
                			that.search(this.value).draw();
                		}
                	});
                });
        },
        'ajax': {
        	'url': "{{route('admin.courses.filter')}}",
        	'data': function(d) {
                d.institute_id = $('#institute_title').val();
                d.course_name = $('#course_name').val();
            }
        },
        "pageLength": 10,
		'columns': [
			{ 
                "title": "S.No.", 
                "data": null,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
			{ "title": "University Name", data: 'university_name' },
			{ "title": "Title", data: 'title' },
			{ "title": "Type", data: 'type' },
			{ "title": "Duration", data: 'duration' },
			{ "title": "Visit", data: 'visit' },
			{ "title": "Eligibility", data:'eligibility'},
			{ 
                "title": "Passout-1 / Fees-1", 
                "render": function(data, type, row) {
                    return row['passout-1'] + ' / ' + row['fees-1'];
                }
            },
            { 
                "title": "Passout-2 / Fees-2", 
                "render": function(data, type, row) {
                    return row['passout-2'] + ' / ' + row['fees-2'];
                }
            },
            { 
                "title": "Passout-3 / Fees-3", 
                "render": function(data, type, row) {
                    return row['passout-3'] + ' / ' + row['fees-3'];
                }
            },
            { 
                "title": "Passout-4 / Fees-4", 
                "render": function(data, type, row) {
                    return row['passout-4'] + ' / ' + row['fees-4'];
                }
            },
            // { 
            //     "title": "Passout-5 / Fees-5", 
            //     "render": function(data, type, row) {
            //         return row['passout-5'] + ' / ' + row['fees-5'];
            //     }
            // },
            // { 
            //     "title": "Passout-6 / Fees-6", 
            //     "render": function(data, type, row) {
            //         return row['passout-6'] + ' / ' + row['fees-6'];
            //     }
            // },
            // { 
            //     "title": "Passout-7 / Fees-7", 
            //     "render": function(data, type, row) {
            //         return row['passout-7'] + ' / ' + row['fees-7'];
            //     }
            // },
            // { 
            //     "title": "Passout-8 / Fees-8", 
            //     "render": function(data, type, row) {
            //         return row['passout-8'] + ' / ' + row['fees-8'];
            //     }
            // },
            // { 
            //     "title": "Passout-9 / Fees-9", 
            //     "render": function(data, type, row) {
            //         return row['passout-9'] + ' / ' + row['fees-9'];
            //     }
            // },
            // { 
            //     "title": "Passout-10 / Fees-10", 
            //     "render": function(data, type, row) {
            //         return row['passout-10'] + ' / ' + row['fees-10'];
            //     }
            // },
			{ "title": "Status", data: 'status' },
			{ "title": "Action", data: null },
		],
		"columnDefs": [
			// {targets: 0, visible: false},
			{"targets": -1, "data": null, "orderable": false, "searchable": false, render: function(data, type, row, meta)
                {
                    if(type === 'display')
                    {
                        data = '<div class="btn-group show">';
                            data += '<a href="'+hostpath+'courses/'+row.hash+'"><button type="submit" class="btn btn-info"><i class="fa fa-regular fa-eye"></i> Info</button></a>';
                            @if(hasPermission('view-courses') || is_customer())
	                            data += '<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="sr-only">Toggle primary</span> </button>';
							@endif()
							data += '<div class="dropdown-menu bg-info">';
							@if(hasPermission('edit-course') || is_customer())
								data += '<a href="'+hostpath+'courses/'+row.hash+'/edit" class="dropdown-item bg-info">Edit</a>';
							@endif()
							@if(hasPermission('delete-course') || is_customer())
								data += '<form action="'+hostpath+'courses/'+row.hash+'" method="POST">{!! method_field("delete") !!} {!! csrf_field() !!}<button type="button" class="dropdown-item bg-danger notifyConfirmForDelete">Remove</button></form>';
							@endif()
							data += '</div>';
	                        
                        data += '</div>';
                    }
                    return data;
                }
            }
        ],
		"rowCallback": function(row, data, index)
		{
			if(data.stage == "completed")
			{
				$('td', row).addClass('bg-success');
			} else if(data.stage == "attempted")
			{
				$('td', row).addClass('bg-warning');
			}
		}
    });
	$('#institute_title, #course_name').change(function() {
        listTable.ajax.reload();
    });
});
</script>
@endpush