$(document).ready(function () {
    if ($('#manage-users-list').length > 0) {
        $('#manage-users-list').DataTable({
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
            "processing": true,
            "ajax": {
                "url": window.usersDatatableUrl,
                "type": "GET"
            },
            "columns": [
                {
                    "render": function (data, type, row) {
                        return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
                    }
                },
                {
                    "render": function (data, type, row) {
                        return '<h6 class="d-flex align-items-center fs-14 fw-medium mb-0"><a href="javascript:void(0);" class="d-flex flex-column">' + row['name'] + '</a></h6>';
                    }
                },
                {
                    "render": function (data, type, row) {
                        return '<div><span class="d-block fw-medium text-dark">' + row['employee_name'] + '</span><span class="fs-12 text-muted">' + row['employee_number'] + '</span></div>';
                    }
                },
                { "data": "email" },
                {
                    "render": function (data, type, row) {
                        var roles = row['roles'];
                        var roles_badge = '';
                        roles.forEach(function (role) {
                            roles_badge += '<span class="badge badge-pill bg-success me-1">' + role + '</span>';
                        });
                        return roles_badge;
                    }
                },
                // { "data": "roles" },
                { "data": "created" },
                { "data": "last_activity" },
                {
                    "render": function (data, type, row) {
                        if (row['status'] == true) { var class_name = "bg-success"; var status_name = "Active" } else { var class_name = "bg-danger"; var status_name = "Inactive" }
                        return '<span class="badge badge-pill badge-status ' + class_name + '" >' + status_name + '</span>';
                    }
                },
                {
                    "render": function (data, type, row) {
                        return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item btn-edit-user" href="javascript:void(0);" data-id="' + row['id'] + '"><i class="ti ti-edit text-blue"></i> Edit</a><a class="dropdown-item btn-delete-user" href="#" data-id="' + row['id'] + '" data-bs-toggle="modal" data-bs-target="#delete_contact"><i class="ti ti-trash"></i> Delete</a></div></div>';
                    }
                }
            ]
        });
    }

    // Auto-open offcanvas if hash is in URL
    if (window.location.hash === '#offcanvas_add') {
        const el = document.getElementById('offcanvas_add');
        if (el) {
            const offcanvas = new bootstrap.Offcanvas(el);
            offcanvas.show();
            // Remove hash from URL without reloading
            history.replaceState(null, null, ' ');
        }
    }
});
$(document).on('click', '.btn-edit-user', function () {
    const userId = $(this).data('id');
    const offcanvasElement = document.getElementById('offcanvas_edit');
    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvas.show();
    $.ajax({
        url: `/users/${userId}/edit`, // route edit
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

$(document).on('click', '.btn-delete-user', function () {
    const userId = $(this).data('id');
    $.ajax({
        url: `/users/${userId}`, // route edit
        type: 'GET',
        success: function (res) {
            $('#offcanvas-delete-body').html(res);
        },
        error: function () {
            $('#offcanvas-delete-body').html(
                '<div class="alert alert-danger">Failed to load data</div>'
            );
        }
    });
});
