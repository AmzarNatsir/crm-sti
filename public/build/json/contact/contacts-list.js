$(document).ready(function () {
	if ($('#contacts_list').length > 0) {
		$('#contacts_list').DataTable({
			"bFilter": false,
			"bInfo": false,
			"ordering": true,
			"autoWidth": true, "autoWidth": true,
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
			serverSide: true,
			processing: true,
			"ajax": window.contactsDatatableUrl,
			"columns": [
				{
					"render": function (data, type, row) {
						return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
					}
				},
				{ "data": "jenisKontak" },
				{
					"render": function (data, type, row) {
						return '<h6 class="d-flex align-items-center fs-14 fw-medium mb-0"><a href="leads-details" class="d-flex flex-column">' + row['namaLengkap'] + ' </span></a></h6>';
					}
				},
				{ "data": "noWa" },
				{ "data": "noAlternatif" },
				{
					"render": function (data, type, row) {
						return row['desa'] + "," + row['kabupaten'] + ", " + row['provinsi'];
					}
				},
				{ "data": "alamatLahanUsaha" },
				{ "data": "created_at" },
				{
					"render": function (data, type, row) {
						return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edit" href="#"><i class="ti ti-edit text-blue"></i> Edit</a><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_lead"><i class="ti ti-trash"></i> Delete</a><a class="dropdown-item" href="#"><i class="ti ti-clipboard-copy text-blue-light"></i> Promote to Prospect</a></div></div>';
					}
				}
			]
		});
	}

});
