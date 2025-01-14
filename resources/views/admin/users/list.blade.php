@extends('layouts.auth-admin', ['parent' => 'administration', 'child' => "userlist"])

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
				<h1 class="m-0">Manage Users</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
					<li class="breadcrumb-item active">Users</li>
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
						@if(is_admin() || is_customer())
						<a href="{{route('admin.user.new')}}" class="float-right">Add</a>
						@endif
						<h3 class="card-title">List</h3>
					</div>
					<div class="card-body">
						<table id="filterUsersLists" class="table table-bordered table-hover">
						<tfoot>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Type</th>
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
	$('#filterUsersLists tfoot th').each(function()
	{
		let totalCol = $('#filterUsersLists tfoot th').length;
		if($(this).index()<(totalCol-1))
		{
			var title = $(this).text();
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
		}
	});
	var userType = "{{$currentUser->type}}";
	var canEdit = "{{hasPermission('edit-user')}}";
	var canDelete = "{{hasPermission('delete-user')}}";

	var listTable = $('#filterUsersLists').DataTable({
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
            'url': "{{route('admin.users.filter')}}",
            'data': function(d) {
                d.branch_id = $('#branch_name').val();
				d.institute_id = $('#institute_title').val();
            }
        },
        "pageLength": 10,
		'columns': [
			{ "title": "Name", data: 'name' },
			{ "title": "Email", data: 'email' },
			{ "title": "Phone", data: 'phone' },
			{ "title": "Type", data: 'type' },
			{ "title": "Status", data: 'status' },
			{ "title": "Action", data: null },
		],
		"columnDefs": [
			// {targets: 0, visible: false},
			{"targets": 4, "data": null, "orderable": false, "searchable": false, render: function(data, type, row, meta)
                {
                    if(type === 'display')
                    {
                        data = '<div class="btn-group show">';
                            data += '<a href="'+hostpath+'users/'+row.hash+'"><button type="submit" class="btn btn-info"><i class="fa fa-regular fa-eye"></i> Info</button></a>';
                            @if(hasPermission('view-users') || is_customer())
	                            data += '<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="sr-only">Toggle primary</span> </button>';
	                            data += '<div class="dropdown-menu bg-info">';
								@if(hasPermission('edit-user') || is_customer())
									data += '<a href="'+hostpath+'users/'+row.hash+'/edit" class="dropdown-item bg-info">Edit</a>';
									data += '<a href="'+hostpath+'users/'+row.hash+`/toggleBlock" class="dropdown-item bg-info">${row.status =="Blocked" ? "Unblock":"Block"}</a>`;
								@endif()
								@if(hasPermission('delete-user') || is_customer())
									data += '<form action="'+hostpath+'users/'+row.hash+'" method="POST">{!! method_field("delete") !!} {!! csrf_field() !!}<button type="button" class="dropdown-item bg-danger notifyConfirmForDelete">Remove</button></form>';
			                    @endif()
										
	                            data += '</div>';
	                        @endif()
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
	$('#branch_name').change(function() {
        listTable.ajax.reload();
    });
	$('#institute_title').change(function() {
        listTable.ajax.reload();
    });
});
</script>
@endpush