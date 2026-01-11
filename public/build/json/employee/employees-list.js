// Employee DataTable and Offcanvas handling
$(document).ready(function () {
    if ($('#employees_list').length > 0) {
        var table = $('#employees_list').DataTable({
            bFilter: false,
            bInfo: false,
            ordering: true,
            autoWidth: true,
            language: {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                info: "_START_ - _END_ of _TOTAL_ items",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="ti ti-chevron-right"></i>',
                    previous: '<i class="ti ti-chevron-left"></i>'
                }
            },
            initComplete: (settings, json) => {
                $('.dataTables_paginate').appendTo('.datatable-paginate');
                $('.dataTables_length').appendTo('.datatable-length');
            },
            serverSide: true,
            processing: true,
            ajax: window.employeesDatatableUrl,
            columns: [
                {
                    "render": function (data, type, row) {
                        return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
                    }, orderable: false, searchable: false
                },
                {
                    data: 'photo',
                    name: 'photo',
                    render: function (data, type, row) {
                        var img_path = data ? (window.employeesBaseUrl.replace('/employees', '') + '/storage/' + data) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=random';
                        return '<div class="avatar avatar-md rounded-circle"><img src="' + img_path + '" alt="img" class="rounded-circle img-preview-trigger" style="cursor: pointer;"></div>';
                    }, orderable: false, searchable: false
                },
                { data: 'employee_number', name: 'employee_number' },
                { data: 'identitiy_number', name: 'identitiy_number' },
                { data: 'name', name: 'name' },
                { data: 'position_name', name: 'position.name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'status', name: 'status' },
                { data: 'join_date', name: 'join_date' },
                {
                    "render": function (data, type, row) {
                        return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-right">' +
                            '<a class="dropdown-item btn-edit-employee" data-id="' + row.id + '" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add" href="#"><i class="ti ti-edit text-blue"></i> Edit</a>' +
                            '<button type="button" class="dropdown-item btn-delete-employee" data-id="' + row.id + '"><i class="ti ti-trash"></i> Delete</button>' +
                            '</div></div>';
                    }, orderable: false, searchable: false
                }
            ]
        });

        // Custom search
        $('#custom-search').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    // Add Employee button
    $(document).on('click', '.btn-add-employee', function () {
        $('#offcanvas-title').text('Add New Employee');
        $.ajax({
            url: window.employeesBaseUrl + '/create',
            success: function (response) {
                $('#offcanvas-add-body').html(response);
            }
        });
    });

    // Edit Employee button
    $(document).on('click', '.btn-edit-employee', function () {
        var id = $(this).data('id');
        var url = window.employeesBaseUrl + '/' + id + '/edit';
        $('#offcanvas-title').text('Edit Employee');
        $.ajax({
            url: url,
            success: function (response) {
                $('#offcanvas-add-body').html(response);
            }
        });
    });

    // Submit Add/Edit form via AJAX
    $(document).on('submit', '#offcanvas_add form', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Saving...');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                $('#employees_list').DataTable().ajax.reload(null, false);
                var offcanvasEl = document.getElementById('offcanvas_add');
                var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if (offcanvas) offcanvas.hide();

                if (window.showToast) {
                    window.showToast('success', response.message || 'Saved successfully');
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).text(originalText);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        var input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback d-block">' + messages[0] + '</div>');
                    });
                } else {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred. Please try again.';
                    if (window.showToast) {
                        window.showToast('error', msg);
                    } else {
                        alert(msg);
                    }
                }
            }
        });
    });

    // Delete handling
    $(document).on('click', '.btn-delete-employee', function (e) {
        var id = $(this).data('id');
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
                    url: window.employeesBaseUrl + '/' + id,
                    method: 'DELETE',
                    data: {
                        _token: window.csrfToken
                    },
                    success: function (response) {
                        $('#employees_list').DataTable().ajax.reload(null, false);
                        if (window.showToast) {
                            window.showToast('success', response.message || 'Deleted successfully');
                        }
                    },
                    error: function (xhr) {
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred. Please try again.';
                        if (window.showToast) {
                            window.showToast('error', msg);
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    });

    // Global Image Preview Handler
    $(document).on('click', '.img-preview-trigger', function () {
        var imgSrc = $(this).attr('src');
        if (imgSrc) {
            $('#globalPreviewImage').attr('src', imgSrc);
            var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            myModal.show();
        }
    });
});
