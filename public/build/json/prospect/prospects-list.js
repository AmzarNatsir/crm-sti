// Prospect DataTable and Promotion handling
$(document).ready(function () {
    if ($('#prospects_list').length > 0) {
        $('#prospects_list').DataTable({
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
            ajax: window.prospectsDatatableUrl,
            columns: [
                {
                    "render": function (data, type, row) {
                        return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
                    }, orderable: false, searchable: false
                },
                {
                    data: 'photo_profile',
                    name: 'photo_profile',
                    render: function (data, type, row) {
                        var img_path = data ? (window.customersBaseUrl.replace('/customers', '') + '/' + data) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=random';
                        return '<div class="avatar avatar-md rounded-circle"><img src="' + img_path + '" alt="img" class="rounded-circle"></div>';
                    }, orderable: false, searchable: false
                },
                { data: 'commodity_name', name: 'commodity_name' },
                { data: 'name', name: 'name' },
                { data: 'identity_no', name: 'identity_no' },
                { data: 'company', name: 'company_name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'address', name: 'address' },
                { data: 'district', name: 'district' },
                { data: 'created_at', name: 'created_at' },
                {
                    "render": function (data, type, row) {
                        return '<div class="dropdown table-action"><a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-right">' +
                            '<a class="dropdown-item btn-promote-prospect" data-id="' + row.id + '" href="#"><i class="ti ti-user-check text-success"></i> Promote to Customer</a>' +
                            '<a class="dropdown-item btn-edit-customer" data-id="' + row.id + '" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add" href="#"><i class="ti ti-edit text-blue"></i> Edit</a>' +
                            '<form action="' + window.customersBaseUrl + '/' + row.id + '" method="POST" class="d-inline delete-form">' +
                            '<input type="hidden" name="_token" value="' + window.csrfToken + '">' +
                            '<input type="hidden" name="_method" value="DELETE">' +
                            '<button type="submit" class="dropdown-item btn-delete-customer"><i class="ti ti-trash"></i> Delete</button>' +
                            '</form></div></div>';
                    }, orderable: false, searchable: false
                }
            ]
        });
    }

    // Promote Prospect to Customer
    $(document).on('click', '.btn-promote-prospect', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = window.prospectsBaseUrl + '/' + id + '/promote';

        Swal.fire({
            title: 'Confirm Promotion',
            text: "Are you sure you want to promote this prospect to a customer?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, promote!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: window.csrfToken
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#prospects_list').DataTable().ajax.reload(null, false);
                            if (window.showToast) {
                                window.showToast('success', response.message);
                            }
                        }
                    },
                    error: function (xhr) {
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred.';
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

    // Add Prospect button
    $(document).on('click', '.btn-add-customer', function () {
        $('#offcanvas-title').text('Add New Prospect');
        $.ajax({
            url: window.customersBaseUrl + '/create',
            success: function (response) {
                $('#offcanvas-add-body').html(response);
                // Default type to prospect when adding from prospects page
                $('#offcanvas-add-body select[name="type"]').val('prospect');
            }
        });
    });

    // Edit button (duplicated from customers-list.js for isolation)
    $(document).on('click', '.btn-edit-customer', function () {
        var id = $(this).data('id');
        var url = window.customersBaseUrl + '/' + id + '/edit';
        $('#offcanvas-title').text('Edit Prospect');
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
                $('#prospects_list').DataTable().ajax.reload(null, false);
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

    // Delete confirmation (duplicated)
    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault();
        var form = this;
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
                form.submit();
            }
        });
    });
});
