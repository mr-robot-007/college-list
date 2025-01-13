@extends('layouts.auth-admin', ['parent' => 'admissions', 'child' => "admissions"])

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
		width: 100% !important;
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

	@media (max-width: 768px) {
    #filterInstitutesLists {
        font-size: 12px; /* Reduce font size */
    }
    #filterInstitutesLists th,
    #filterInstitutesLists td {
        padding: 2px; /* Reduce padding */
    }
    .table thead th {
        white-space: wrap; /* Prevent column headers from wrapping */
		vertical-align: middle;
    }
    .table tfoot th {
        white-space: wrap; /* Prevent column headers from wrapping */
		vertical-align: middle;
    }
}
</style>
@endpush

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Manage Admissions</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item active">Admissions</li>
				</ol>
			</div>
		</div>
		<div class="row">
			@if(is_admin())
			<div class="col-md-3">
				<div class="form-group required">
					<label>Center</label>
					<select name="center_id" id="center_id" class="form-control" placeholder="Course Title" required>
                        <option value=""> Select Course </option>
						@foreach($centers as $center)
							<option value="{{ $center->id }}">{{ $center->first_name.''.$center->last_name }}</option>
						@endforeach
                    </select>
				</div>
			</div>
			@endif()
			<div class="col-md-3">
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
			<div class="col-md-3">
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
			<div class="col-md-2">
				<label>&nbsp;</label><br />
				<a href="{{route("admin.admissions")}}" class="btn btn btn-default">Reset</a>
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
						@if(hasPermission('add-course') || is_center())
						<a href="{{route('admin.admission.new')}}" class="float-right">Add</a>
						@endif
						<h3 class="card-title">List</h3>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="filterInstitutesLists" class="table table-bordered table-hover">
								<thead>
									<th>S.No</th>
									<th>Student Info</th>
									<th>University</th>
									<th>Fees-1</th>
									<th>Fees-2</th>
									<th>Fees-3</th>
									<th>Fees-4</th>
									<th>Fees-5</th>
									<th>Action</th>
								</thead>
							</table>
						</div>
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
	$(window).on('resize', function() {
		if ($(window).width() <= 768) {
			$('#filterInstitutesLists').css('font-size', '12px');
			$('#filterInstitutesLists th, #filterInstitutesLists td').css('padding', '4px');
		} else {
			$('#filterInstitutesLists').css('font-size', '');
			$('#filterInstitutesLists th, #filterInstitutesLists td').css('padding', '');
		}
	}).trigger('resize');
	var userType = "{{$currentUser->type}}";

	var listTable = $('#filterInstitutesLists').DataTable({
		"autoWidth": false,
		"iDisplayLength": 10,
		"aLengthMenu": [10, 25, 50, 100],
		'processing': true,
		'serverSide': true,
		'serverMethod': 'get',
		'scrollCollapse': true,
		'scrollY': false,
		'scrollX': true,	
		"autoWidth": false,
		"lengthChange": true,
		'ordering': false, 
		"columnDefs": [
			{
				targets: [-1], // Specify column indexes to hide on mobile
				visible: true,
				responsivePriority: 2 // This makes the columns hidden on smaller devices
			},
			{
				targets: '_all',
				className: 'dt-head-center dt-body-center', // Center-align all cells
				defaultContent: '-' // Placeholder for empty cells
			}
		],

        'ajax': {
        	'url': "{{route('admin.admissions.filter')}}",
        	'data': function(d) {
                d.institute_id = $('#institute_title').val();
                d.course_name = $('#course_name').val();
                d.center_id = $('#center_id').val();
            }
        },
        "pageLength": 10,
		'columns': [
			{ 
                "title": "S.No.", 
                "data": null,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1  @if(is_admin())+'<br>('+row.center+')'@endif();
                }
            },
			{ "title": "Student Info", 
				"render": function(data, type, row) {
                    return '<b>Name: </b>'+row['student_name'];
                }
			 },
			{ "title": "University", "render": function(data, type, row) {
                    return'<b>University: </b>'+row['university_name']+
					'<br><b>Course: </b>'+row['course']+
					'<br><b>Passout: </b>'+row['passout']+
					'<br><b>Total Fees: </b>'+row['total']
					;
                } 
			},
			{ "title": "Fees-1",
				"render": function(data, type, row) {
					var statusClass = '';
                    if (row['fees1_status'] === 'Paid') {
                        statusClass = 'yellow'; // Add yellow background for paid
                    } else if (row['fees1_status'] === 'Pending') {
                        statusClass = 'lightblue'; // Add light blue background for pending
                    } else if (row['fees1_status'] === 'Approved') {
                        statusClass = 'lightgreen'; // Add green background for approved
                    }
					return '<b>Amount: </b>'+row['fees1_amount']+
					'<br><b>Date: </b>'+row['fees1_date']+
					'<br><b>Status: </b><span style="padding:2px; border-radius:5px; background-color:' + statusClass + ';">' + row['fees1_status'] + '</span>';
                }
			 },
			{ "title": "Fees-2",
				"render": function(data, type, row) {
					var statusClass = '';
                    if (row['fees2_status'] === 'Paid') {
                        statusClass = 'yellow'; // Add yellow background for paid
                    } else if (row['fees2_status'] === 'Pending') {
                        statusClass = 'lightblue'; // Add light blue background for pending
                    } else if (row['fees2_status'] === 'Approved') {
                        statusClass = 'lightgreen'; // Add green background for approved
                    }
					return '<b>Amount: </b>'+row['fees2_amount']+
					'<br><b>Date: </b>'+row['fees2_date']+
					'<br><b>Status: </b><span style="padding:2px; border-radius:5px; background-color:' + statusClass + ';">' + row['fees2_status'] + '</span>';
                }
			 },
			{ "title": "Fees-3",
				"render": function(data, type, row) {
					if (row['fees3_status'] === 'Paid') {
                        statusClass = 'yellow'; // Add yellow background for paid
                    } else if (row['fees3_status'] === 'Pending') {
                        statusClass = 'lightblue'; // Add light blue background for pending
                    } else if (row['fees3_status'] === 'Approved') {
                        statusClass = 'lightgreen'; // Add green background for approved
                    }
                    return'<b>Amount: </b>'+row['fees3_amount']+
					'<br><b>Date: </b>'+row['fees3_date']+
					'<br><b>Status: </b><span style="padding:2px; border-radius:5px; background-color:' + statusClass + ';">' + row['fees3_status'] + '</span>'
					;
                }
			 },
			{ "title": "Fees-4",
				"render": function(data, type, row) {
					if (row['fees4_status'] === 'Paid') {
                        statusClass = 'yellow'; // Add yellow background for paid
                    } else if (row['fees4_status'] === 'Pending') {
                        statusClass = 'lightblue'; // Add light blue background for pending
                    } else if (row['fees4_status'] === 'Approved') {
                        statusClass = 'lightgreen'; // Add green background for approved
                    }
                    return'<b>Amount: </b>'+row['fees4_amount']+
					'<br><b>Date: </b>'+row['fees4_date']+
					'<br><b>Status: </b><span style="padding:2px; border-radius:5px; background-color:' + statusClass + ';">' + row['fees4_status'] + '</span>'
					;
                }
			 },
			{ "title": "Fees-5",
				"render": function(data, type, row) {
					if (row['fees5_status'] === 'Paid') {
                        statusClass = 'yellow'; // Add yellow background for paid
                    } else if (row['fees5_status'] === 'Pending') {
                        statusClass = 'lightblue'; // Add light blue background for pending
                    } else if (row['fees5_status'] === 'Approved') {
                        statusClass = 'lightgreen'; // Add green background for approved
                    }
                    return'<b>Amount: </b>'+row['fees5_amount']+
					'<br><b>Date: </b>'+row['fees5_date']+
					'<br><b>Status: </b><span style="padding:2px; border-radius:5px; background-color:' + statusClass + ';">' + row['fees5_status'] + '</span>'
					;
                }
			 },
			{ "title": "Action", data: null },
		],
		"columnDefs": [
			{"targets": -1, "data": null, "orderable": false, "searchable": false, render: function(data, type, row, meta)
                {
                    if(type === 'display')
                    {
                        data = '<div class="btn-group show">';
                            data += '<a href="'+hostpath+'admissions/'+row.hash+'"><button type="submit" class="btn btn-info"><i class="fa fa-regular fa-eye"></i> Info</button></a>';
                            @if(hasPermission('view-courses') || is_center())
	                            data += '<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="sr-only">Toggle primary</span> </button>';
							@endif()
							data += '<div class="dropdown-menu bg-info">';
							@if(hasPermission('edit-course') || is_center())
								data += '<a href="'+hostpath+'admissions/'+row.hash+'/edit" class="dropdown-item bg-info">Edit</a>';
							@endif()
							@if(hasPermission('delete-course') || is_customer())
								data += '<form action="'+hostpath+'admissions/'+row.hash+'" method="POST">{!! method_field("delete") !!} {!! csrf_field() !!}<button type="button" class="dropdown-item bg-danger notifyConfirmForDelete">Remove</button></form>';
							@endif()
							data += '</div>';
	                        
                        data += '</div>';
                    }
                    return data;
                }
            },
			{
				targets: '_all',
				className: 'dt-head-center dt-body-center', // Center-align all cells
				defaultContent: '-' // Placeholder for empty cells
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
	$('#institute_title, #course_name,#center_id').change(function() {
        listTable.ajax.reload();
    });
});
</script>
@endpush