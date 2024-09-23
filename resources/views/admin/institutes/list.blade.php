@extends('layouts.auth-admin', ['parent' => 'administration', 'child' => "institutelist"])

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
				<h1 class="m-0">Manage Universities</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item active">Institutes</li>
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
						@if(is_admin())
						<a href="{{route('admin.institute.new')}}" class="float-right">Add</a>
						@endif
						<h3 class="card-title">List</h3>
					</div>
					<div class="card-body">
						<table id="filterInstitutesLists" class="table table-bordered table-hover">
						<tfoot>
						<tr>
							<th>University Name</th>
							<th>Approved By</th>
							<th>University Website</th>
							<th>Address</th>
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
        	'url': "{{route('admin.institutes.filter')}}",
        	data: {
                
            }
        },
        "pageLength": 10,
		'columns': [
			{ "title": "University Name", data: 'university_name' },
			{ "title": "Approved By", data: 'approved_by' },
			{ "title": "University Website", data: 'university_website' },
			{ "title": "Verification", data: 'verification' },
			{ "title": "Status", data: 'status' },
			{ "title": "Action", data: null },
		],
		"columnDefs": [
			// {targets: 0, visible: false},
			{"targets": -1, "data": null, "orderable": false, "searchable": false, render: function(data, type, row, meta)
                {
                    if(type === 'display')
                    {
                        data = '<div class="btn-group show" style="">';
                            data += '<a href="'+hostpath+'institutes/'+row.hash+'"><button type="submit" class="btn btn-info"><i class="fa fa-regular fa-eye"></i> Info</button></a>';
                            if(userType=='Admin' || (userType=='AltAdmin' && row.type!=='Admin') || ((userType!=='Admin' && userType!=='AltAdmin') && (row.type!=='Admin' && row.type!=='AltAdmin')))
                            {
	                            data += '<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="sr-only">Toggle primary</span> </button>';
	                            data += '<div class="dropdown-menu bg-info">';
	                            		if(userType==='Admin')
	                            		{
			                            	data += '<a href="'+hostpath+'institutes/'+row.hash+'/edit" class="dropdown-item bg-info">Edit</a>';
			                                data += '<form action="'+hostpath+'institutes/'+row.hash+'" method="POST">{!! method_field("delete") !!} {!! csrf_field() !!}<button type="button" class="dropdown-item bg-danger notifyConfirmForDelete">Remove</button></form>';
			                            } 
	                            data += '</div>';
	                        }
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
});
</script>
@endpush