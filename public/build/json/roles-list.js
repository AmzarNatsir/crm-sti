$(document).ready(function () {

	if ($('#roles_list').length > 0) {
		$('#roles_list').DataTable({
			"bFilter": false,
			"bInfo": false,
			"ordering": true,
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
			"ajax": window.rolesDatatableUrl,
			"columns": [
				{
					"render": function (data, type, row) {
						return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
					}
				},
				{ "data": "name" },
				{
					"data": "permissions_count",
					"render": function (data, type, row) {
						return '<span class="badge badge-soft-primary">' + data + ' Permissions</span>';
					}
				},
				{ "data": "created" },
				{
					"render": function (data, type, row) {
						return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item btn-edit-role" href="javascript:void(0);" data-id="' + row['id'] + '"><i class="ti ti-edit text-blue"></i> Edit</a><a class="dropdown-item btn-delete-role" href="javascript:void(0);" data-id="' + row['id'] + '"><i class="ti ti-trash"></i> Delete</a></div></div>';
					}
				}
			]
		});
	}

});
$(document).on('click', '.btn-add-role', function () {
	const offcanvasElement = document.getElementById('offcanvas_add');
	const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
	offcanvas.show();
	$.ajax({
		url: `/roles/create`,
		type: 'GET',
		success: function (res) {
			$('#offcanvas-add-body').html(res);
		},
		error: function () {
			$('#offcanvas-add-body').html(
				'<div class="alert alert-danger">Failed to load data</div>'
			);
		}
	});
});

$(document).on('click', '.btn-edit-role', function () {
	const roleId = $(this).data('id');
	const offcanvasElement = document.getElementById('offcanvas_edit');
	const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
	offcanvas.show();
	$.ajax({
		url: `/roles/${roleId}/edit`,
		type: 'GET',
		success: function (res) {
			$('#offcanvas-edit-body').html(res);
		},
		error: function () {
			$('#offcanvas-edit-body').html(
				'<div class="alert alert-danger">Failed to load data</div>'
			);
		}
	});
});

$(document).on('click', '.btn-delete-role', function () {
	const roleId = $(this).data('id');
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
				url: `/roles/${roleId}`,
				type: 'DELETE',
				data: {
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: function (res) {
					showToast('success', 'Role deleted successfully');
					$('#roles_list').DataTable().ajax.reload();
				},
				error: function () {
					showToast('error', 'Failed to delete role');
				}
			});
		}
	});
});
