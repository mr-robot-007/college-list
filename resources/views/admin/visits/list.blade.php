@extends('layouts.auth-admin', ['parent' => 'administration', 'child' => "visitlist"])

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

@section("content")
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Course Visit Stats</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item active">Visits</li>
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
						<h3 class="card-title">List</h3>
					</div>
					<div class="card-body">
						<table id="filterInstitutesLists" class="table table-bordered table-hover">
						<tfoot>
						<tr>
							<th>Center Name</th>
							<th>Course</th>
							<th>Visits</th>
							<th>Last Visited</th>
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
		'scrollCollapse': false,
		'scrollY': false,
		'scrollX': true,		
		'responsive': true,
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
        	'url': "{{route('admin.visits.filter')}}",
        	data: {
                
            }
        },
        "pageLength": 10,
		'columns': [
			{ "title": "Center Name", data: 'center_name' },
			{ "title": "Course", data: 'course_name' },
			{ "title": "Visits", data: 'visits' },
			{ "title": "Last Visit", data: 'last_visited' },
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
});
</script>
@endpush