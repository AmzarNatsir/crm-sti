$(document).ready(function () {
    // Check if DataTable already exists and destroy it before reinitializing
    if ($.fn.DataTable.isDataTable('#prospects_list')) {
        $('#prospects_list').DataTable().destroy();
    }

    // Initialize DataTable
    const table = $('#prospects_list').DataTable({
        searchDelay: 500,
        processing: true,
        serverSide: true,
        ajax: {
            url: window.prospectsDatatableUrl,
            type: "GET",
            data: function (d) {
                d.contact_type = $('#filter_contact_type').val();
                d.commodity_id = $('#filter_commodity').val();
                d.name = $('#filter_name').val();
                d.identity_no = $('#filter_identity_no').val();
                d.phone = $('#filter_phone').val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
                }
            },
            {
                data: 'photo_profile',
                name: 'photo_profile',
                render: function (data, type, row) {
                    var img_path = data ? (window.customersBaseUrl.replace('/customers', '') + '/storage/' + data) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=random';
                    return '<div class="avatar avatar-md rounded-circle"><img src="' + img_path + '" alt="img" class="rounded-circle img-preview-trigger" style="cursor: pointer;"></div>';
                }
            },
            { data: 'contact_type', name: 'contact_type' },
            {
                data: 'status',
                name: 'status',
                render: function (data, type, row) {
                    if (!data) return '<span class="badge bg-secondary">Unknown</span>';

                    if (data.startsWith('Follow-up')) {
                        return '<span class="badge bg-warning text-dark">' + data + '</span>';
                    }
                    if (data === 'Stalled') {
                        return '<span class="badge bg-danger">' + data + '</span>';
                    }

                    return '<span class="badge bg-success">' + data + '</span>';
                }
            },
            { data: 'commodity_name', name: 'commodity_name' },
            { data: 'name', name: 'name' },
            { data: 'identity_no', name: 'identity_no' },
            { data: 'phone', name: 'phone' },
            { data: 'address', name: 'address' },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    let location = [];
                    if (row.village) location.push(row.village);
                    if (row.sub_district) location.push(row.sub_district);
                    if (row.district) location.push(row.district);
                    if (row.province) location.push(row.province);
                    return location.length > 0 ? location.join(', ') : '-';
                }
            },
            { data: 'created_at', name: 'created_at' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: function (data, type, row) {
                    return `
                        <div class="dropdown table-action"> 
                            <a href="#" class="action-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item btn-view-customer" href="javascript:void(0);" data-id="${row.id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-eye text-blue"></i> View</a>
                                <a class="dropdown-item btn-edit-customer" href="javascript:void(0);" data-id="${row.id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-edit text-blue"></i> Edit</a>
                                <a class="dropdown-item btn-promote-prospect" href="javascript:void(0);" data-id="${row.id}"><i class="ti ti-arrow-up text-success"></i> Promote to Customer</a>
                            </div>
                        </div>
                    `;
                }
            }
        ],
        order: [[10, 'desc']], // Default sort by Created Date (column index 10)
        dom: '<"top"i>rt<"bottom"lp><"clear">', // Removed 'f' for filter box as we use custom search
        language: {
            paginate: {
                previous: '<i class="ti ti-chevron-left"></i>',
                next: '<i class="ti ti-chevron-right"></i>'
            }
        },
        initComplete: function () {
            // Move pagination to our custom wrappers
            $('.datatable-length').append($('.dataTables_length'));
            $('.datatable-paginate').append($('.dataTables_paginate'));
        }
    });

    console.log('Prospect list script loaded');

    // Filter Buttons
    $(document).on('click', '#filter_apply', function () {
        $('#prospects_list').DataTable().ajax.reload();
    });

    $(document).on('click', '#filter_reset', function () {
        $('#filter_contact_type').val('').trigger('change');
        $('#filter_commodity').val('').trigger('change');
        $('#filter_name').val('');
        $('#filter_identity_no').val('');
        $('#filter_phone').val('');
        $('#prospects_list').DataTable().ajax.reload();
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

    // View handler
    $(document).on('click', '.btn-view-customer', function () {
        var id = $(this).data('id');
        var url = window.customersBaseUrl + '/' + id + '/edit';
        $('#offcanvas-title').text('View Prospect');
        $.ajax({
            url: url,
            success: function (response) {
                $('#offcanvas-add-body').html(response);
                // Disable all inputs for view mode
                $('#offcanvas-add-body input, #offcanvas-add-body select, #offcanvas-add-body textarea').prop('disabled', true);
                // Hide submit button
                $('#offcanvas-add-body button[type="submit"]').hide();
            }
        });
    });

    // Promote to Customer Action
    $(document).on('click', '.btn-promote-prospect', function () {
        const id = $(this).data('id');
        const btn = $(this);

        Swal.fire({
            title: 'Promote to Customer?',
            text: "This prospect will be promoted to a customer.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Promote',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `${window.prospectsBaseUrl}/${id}/promote`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error.responseJSON ? error.responseJSON.message : error.statusText}`
                    )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire({
                    title: 'Success!',
                    text: result.value.message,
                    icon: 'success'
                });
                table.draw(false); // Refresh table
            }
        });
    });

    // Select All checkbox
    $('#select-all').on('click', function () {
        const isChecked = $(this).prop('checked');
        $('#prospects_list tbody input[type="checkbox"]').prop('checked', isChecked);
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
