$(document).ready(function () {
	if ($('#permission_list_table').length > 0) {
		var table = $('#permission_list_table').DataTable({
			"bFilter": true,
			"bInfo": false,
			"ordering": true,
			"order": [[0, 'asc']],
			"autoWidth": true,
			"language": {
				search: ' ',
				sLengthMenu: '_MENU_',
				searchPlaceholder: "Search",
				info: "_START_ - _END_ of _TOTAL_ items",
				"lengthMenu": "Show _MENU_ entries",
				paginate: {
					next: '<i class="ti ti-chevron-right"></i> ',
					previous: '<i class="ti ti-chevron-left"></i> '
				},
			},
			initComplete: (settings, json) => {
				$('.dataTables_paginate').appendTo('.datatable-paginate');
				$('.dataTables_length').appendTo('.datatable-length');
			},
			"ajax": "/permissions/datatables",
			"columns": [
				{ "data": "subject" },
				{ "data": "name" },
				{ "data": "guard_name" },
				{
					"data": "roles",
					"render": function (data, type, row) {
						if (!data || data.length === 0) return '<span class="badge badge-soft-secondary">No Roles</span>';
						let badges = '';
						data.forEach(role => {
							badges += '<span class="badge badge-soft-info me-1">' + role + '</span>';
						});
						return badges;
					}
				},
				{ "data": "created" },
				{
					"render": function (data, type, row) {
						return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item btn-edit-permission" href="javascript:void(0);" data-id="' + row['id'] + '"><i class="ti ti-edit text-blue"></i> Edit</a><a class="dropdown-item btn-delete-permission" href="javascript:void(0);" data-id="' + row['id'] + '"><i class="ti ti-trash"></i> Delete</a></div></div>';
					}
				}
			],
			"columnDefs": [
				{ "visible": false, "targets": 0 }
			],
			"drawCallback": function (settings) {
				var api = this.api();
				var rows = api.rows({ page: 'current' }).nodes();
				var last = null;

				api.column(0, { page: 'current' }).data().each(function (group, i) {
					if (last !== group) {
						$(rows).eq(i).before(
							'<tr class="group"><td colspan="6" class="bg-light fw-bold">' + group + '</td></tr>'
						);
						last = group;
					}
				});
			}
		});

		$('#search_permissions').on('keyup', function () {
			table.search(this.value).draw();
		});
	}

	$(document).on('click', '.btn-add-permission', function () {
		const offcanvasElement = document.getElementById('offcanvas_add_permission');
		const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
		offcanvas.show();
		$.ajax({
			url: `/permissions/create`,
			type: 'GET',
			success: function (res) {
				$('#offcanvas-add-permission-body').html(res);
			},
			error: function () {
				$('#offcanvas-add-permission-body').html(
					'<div class="alert alert-danger">Failed to load data</div>'
				);
			}
		});
	});

	$(document).on('click', '.btn-edit-permission', function () {
		const permissionId = $(this).data('id');
		const offcanvasElement = document.getElementById('offcanvas_edit_permission');
		const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
		offcanvas.show();
		$.ajax({
			url: `/permissions/${permissionId}/edit`,
			type: 'GET',
			success: function (res) {
				$('#offcanvas-edit-permission-body').html(res);
			},
			error: function () {
				$('#offcanvas-edit-permission-body').html(
					'<div class="alert alert-danger">Failed to load data</div>'
				);
			}
		});
	});

	$(document).on('click', '.btn-delete-permission', function () {
		const permissionId = $(this).data('id');
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: `/permissions/${permissionId}`,
					type: 'DELETE',
					data: {
						_token: $('meta[name="csrf-token"]').attr('content')
					},
					success: function (res) {
						showToast('success', 'Permission deleted successfully');
						$('#permission_list_table').DataTable().ajax.reload();
					},
					error: function () {
						showToast('error', 'Failed to delete permission');
					}
				});
			}
		});
	});
});