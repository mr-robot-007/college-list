@extends('layouts.auth-admin', ['parent' => 'dashboard', 'child' => 'dashboard'])


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
	
}
	
	
</style>
@endpush


@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			
			<div class="col-sm-6">
				<h1 class="m-0">Course List</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item active">Courses</li>
				</ol>
			</div>
		</div>
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
			<div class="col-md-2 mb-4 ">
				<label>&nbsp;</label><br>
				<a href="{{route("admin.courses")}}" class="btn btn-secondary">Reset</a>
			</div>
		</div>
		<div class="row">
			
		<div class="col-md-4">
				<a href="https://forms.gle/AUrEeER1aVGomsUu8" target="_blank" class="btn btn-primary" id="filterButton">Add Admission</a>
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
						<h3 class="card-title">List</h3>
					</div>
					<div class="card-body">
					<div class="table-responsive">
						<table id="filterInstitutesLists" class="table table-bordered table-hover">
							<thead>
								<th>S No.</th>
								<th>University Name</th>
								<th>Approved By</th>
								<th>Verification</th>
								<th>Website</th>
								<th>Course</th>
								<th>Subject</th>
								<th>Type</th>
								<th>Duration</th>
								<th>Eligibility</th>
								<th>Visit</th>
								<th>Passout/Fees</th>
								<th>Passout/Fees</th>
								<th>Passout/Fees</th>
								<th>Passout/Fees</th>
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
		// 'order': [[1, 'asc']],
		'ordering': false,  // Disable ordering
		"columnDefs": [
			{
				targets: [1, 3], // Specify column indexes to hide on mobile
				visible: false,
				responsivePriority: 2 // This makes the columns hidden on smaller devices
			},
			{
				targets: '_all',
				className: 'dt-head-center dt-body-center', // Center-align all cells
				defaultContent: '-' // Placeholder for empty cells
			}
		],
		
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
			{ "title": "Approved By", data: 'approved_by' },
			{ "title": "Verification", data: 'verification' },
			{ "title": "Website", data: 'website' },
			{ "title": "Course", data: 'title' },
			{ "title": "Subject", data: 'subject' },
			{ "title": "Type", data: 'type' },
			{ "title": "Duration", data: 'duration' },
			{ "title": "Eligibility", data:'eligibility'},
			{ "title": "Visit", data: 'visit' },
			{ 
                "title": "Passout/Fees", 
                "render": function(data, type, row) {
                    return row['passout-1'] + '/' + row['fees-1'];
                }
            },
            { 
                "title": "Passout/Fees", 
                "render": function(data, type, row) {
                    return row['passout-2'] + '/' + row['fees-2'];
                }
            },
            { 
                "title": "Passout/Fees", 
                "render": function(data, type, row) {
                    return row['passout-3'] + '/' + row['fees-3'];
                }
            },
            { 
                "title": "Passout/Fees", 
                "render": function(data, type, row) {
                    return row['passout-4'] + '/' + row['fees-4'];
                }
            },
            // { 
            //     "title": "Passout/Fees", 
            //     "render": function(data, type, row) {
            //         return row['passout-5'] + '/' + row['fees-5'];
            //     }
            // },
            // { 
            //     "title": "Passout/Fees", 
            //     "render": function(data, type, row) {
            //         return row['passout-6'] + '/' + row['fees-6'];
            //     }
            // },
            // { 
            //     "title": "Passout/Fees", 
            //     "render": function(data, type, row) {
            //         return row['passout-7'] + '/' + row['fees-7'];
            //     }
            // },
            // { 
            //     "title": "Passout/Fees", 
            //     "render": function(data, type, row) {
            //         return row['passout-8'] + '/' + row['fees-8'];
            //     }
            // },
            // { 
            //     "title": "Passout/Fees", 
            //     "render": function(data, type, row) {
            //         return row['passout-9'] + '/' + row['fees-9'];
            //     }
            // },
            // { 
            //     "title": "Passout/Fees", 
            //     "render": function(data, type, row) {
            //         return row['passout-10'] + '/' + row['fees-10'];
            //     }
            // },
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
	$('#institute_title').change(function() {
        listTable.ajax.reload();
    });
	$('#course_name').change(function() {
        listTable.ajax.reload();
    });
});
</script>
@endpush


