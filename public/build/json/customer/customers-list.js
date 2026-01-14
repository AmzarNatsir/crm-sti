// Customer DataTable and Offcanvas handling
$(document).ready(function () {
    if ($('#customers_list').length > 0) {
        $('#customers_list').DataTable({
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
            ajax: {
                url: window.customersDatatableUrl,
                data: function (d) {
                    d.commodity_id = $('#filter_commodity').val();
                    d.name = $('#filter_name').val();
                    d.identity_no = $('#filter_identity_no').val();
                    d.phone = $('#filter_phone').val();
                }
            },
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
                        var img_path = data ? (window.customersBaseUrl.replace('/customers', '') + '/storage/' + data) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=random';
                        return '<div class="avatar avatar-md rounded-circle"><img src="' + img_path + '" alt="img" class="rounded-circle img-preview-trigger" style="cursor: pointer;"></div>';
                    }, orderable: false, searchable: false
                },
                // {
                //     data: 'type',
                //     name: 'type',
                //     render: function (data, type, row) {
                //         var class_name, status_name;
                //         if (data == "lead") {
                //             class_name = "badge-soft-primary";
                //             status_name = "Lead";
                //         } else if (data == "prospect") {
                //             class_name = "badge-soft-danger";
                //             status_name = "Prospect";
                //         } else {
                //             class_name = "badge-soft-success";
                //             status_name = "Customer";
                //         }
                //         return '<span class="badge badge-tag ' + class_name + '">' + status_name + '</span>';
                //     }
                // },
                { data: 'commodity_name', name: 'commodity_name' },
                { data: 'name', name: 'name' },
                { data: 'identity_no', name: 'identity_no' },
                { data: 'date_of_birth', name: 'date_of_birth' },
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
                            '<a class="dropdown-item" href="' + window.customersBaseUrl + '/' + row.id + '/summary"><i class="ti ti-chart-bar text-primary"></i> Summary</a>' +
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

    $(document).on('click', '#filter_apply', function () {
        $('#customers_list').DataTable().ajax.reload();
    });

    $(document).on('click', '#filter_reset', function () {
        $('#filter_commodity').val('').trigger('change');
        $('#filter_name').val('');
        $('#filter_identity_no').val('');
        $('#filter_phone').val('');
        $('#customers_list').DataTable().ajax.reload();
    });

    // Add Customer button
    $(document).on('click', '.btn-add-customer', function () {
        $('#offcanvas-title').text('Add New Customer');
        $.ajax({
            url: window.customersBaseUrl + '/create',
            success: function (response) {
                $('#offcanvas-add-body').html(response);
            }
        });
    });

    // Edit Customer button
    $(document).on('click', '.btn-edit-customer', function () {
        var id = $(this).data('id');
        var url = window.customersBaseUrl + '/' + id + '/edit';
        $('#offcanvas-title').text('Edit Customer');
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
                $('#customers_list').DataTable().ajax.reload(null, false);
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

    // Delete confirmation using SweetAlert2
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

    // Global Image Preview Handler
    $(document).on('click', '.img-preview-trigger', function () {
        var imgSrc = $(this).attr('src');
        if (imgSrc) {
            $('#globalPreviewImage').attr('src', imgSrc);
            var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            myModal.show();
        }
    });

    // ========================================
    // CUSTOMER IMPORT FUNCTIONALITY
    // ========================================

    var selectedFile = null;
    var previewData = null;

    // Reset import modal when opened
    $('#importModal').on('show.bs.modal', function () {
        resetImportModal();
    });

    function resetImportModal() {
        selectedFile = null;
        previewData = null;
        $('#importFile').val('');
        $('#fileInfo').hide();
        $('#fileError').hide();
        $('#uploadSection').show();
        $('#previewSection').hide();
        $('#progressSection').hide();
        $('#btnPreview').hide();
        $('#btnImport').hide();
        $('#btnClose').prop('disabled', false).text('Close');
    }

    // File selection handler
    $('#importFile').on('change', function (e) {
        var file = e.target.files[0];
        $('#fileError').hide();

        if (!file) {
            $('#fileInfo').hide();
            $('#btnPreview').hide();
            selectedFile = null;
            return;
        }

        // Validate file type
        var validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls)$/i)) {
            $('#fileError').text('Please upload a valid Excel file (.xlsx or .xls)').show();
            $('#fileInfo').hide();
            $('#btnPreview').hide();
            selectedFile = null;
            return;
        }

        // Validate file size (5MB max)
        var maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if (file.size > maxSize) {
            $('#fileError').text('File size exceeds 5MB limit').show();
            $('#fileInfo').hide();
            $('#btnPreview').hide();
            selectedFile = null;
            return;
        }

        // File is valid
        selectedFile = file;
        var fileSizeKB = (file.size / 1024).toFixed(2);
        $('#fileName').text(file.name);
        $('#fileSize').text(fileSizeKB + ' KB');
        $('#fileInfo').show();
        $('#btnPreview').show();
    });

    // Preview button handler
    $('#btnPreview').on('click', function () {
        if (!selectedFile) {
            Swal.fire('Error', 'Please select a file first', 'error');
            return;
        }

        var formData = new FormData();
        formData.append('file', selectedFile);
        formData.append('_token', window.csrfToken);

        // Show loading
        var btn = $(this);
        var originalText = btn.html();
        btn.prop('disabled', true).html('<i class="ti ti-loader me-1"></i>Processing...');

        $.ajax({
            url: window.importPreviewUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    previewData = response.data;
                    displayPreview(response.data);
                    $('#uploadSection').hide();
                    $('#previewSection').show();
                    btn.hide();
                    $('#btnImport').show();
                } else {
                    Swal.fire('Error', response.message || 'Failed to process file', 'error');
                }
                btn.prop('disabled', false).html(originalText);
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error processing file';
                Swal.fire('Error', msg, 'error');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    function displayPreview(data) {
        // Update summary stats
        $('#totalRows').text(data.total_rows);
        $('#validRows').text(data.valid_count);
        $('#invalidRows').text(data.invalid_count);

        // Build preview table
        var tbody = $('#previewTableBody');
        tbody.empty();

        // Show valid rows first (limited to 100)
        data.preview.forEach(function (row) {
            var tr = $('<tr>');
            tr.append('<td>' + row.row_number + '</td>');
            tr.append('<td><span class="badge badge-soft-success">Valid</span></td>');
            tr.append('<td>' + (row.data.name || '-') + '</td>');
            tr.append('<td>' + (row.data.identity_no || '-') + '</td>');
            tr.append('<td>' + (row.data.phone || '-') + '</td>');
            tr.append('<td>' + (row.data.email || '-') + '</td>');
            tr.append('<td>' + (row.data.address || '-') + '</td>');
            tr.append('<td>-</td>');
            tbody.append(tr);
        });

        // Show invalid rows
        data.invalid_rows.forEach(function (row) {
            var tr = $('<tr class="table-danger">');
            tr.append('<td>' + row.row_number + '</td>');
            tr.append('<td><span class="badge badge-soft-danger">Invalid</span></td>');
            tr.append('<td>' + (row.data.name || '-') + '</td>');
            tr.append('<td>' + (row.data.identity_no || '-') + '</td>');
            tr.append('<td>' + (row.data.phone || '-') + '</td>');
            tr.append('<td>' + (row.data.email || '-') + '</td>');
            tr.append('<td>' + (row.data.address || '-') + '</td>');
            tr.append('<td class="text-danger"><small>' + row.errors.join(', ') + '</small></td>');
            tbody.append(tr);
        });
    }

    // Import button handler
    $('#btnImport').on('click', function () {
        if (!selectedFile || !previewData) {
            Swal.fire('Error', 'No data to import', 'error');
            return;
        }

        if (previewData.valid_count === 0) {
            Swal.fire('Error', 'No valid rows to import', 'error');
            return;
        }

        // Confirm import
        Swal.fire({
            title: 'Confirm Import',
            html: 'You are about to import <strong>' + previewData.valid_count + '</strong> valid records.<br>Continue?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Import!'
        }).then((result) => {
            if (result.isConfirmed) {
                processImport();
            }
        });
    });

    function processImport() {
        var formData = new FormData();
        formData.append('file', selectedFile);
        formData.append('_token', window.csrfToken);

        // Show progress section
        $('#previewSection').hide();
        $('#progressSection').show();
        $('#btnImport').hide();
        $('#btnClose').prop('disabled', true);

        // Initialize progress
        updateProgress(0, 0, previewData.valid_count);

        $.ajax({
            url: window.importProcessUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                // Simulate progress (since we can't track real progress easily)
                var progressInterval = setInterval(function () {
                    var currentPercent = parseInt($('#progressBar').attr('aria-valuenow'));
                    if (currentPercent < 90) {
                        updateProgress(currentPercent + 10, Math.floor((currentPercent + 10) / 100 * previewData.valid_count), previewData.valid_count);
                    }
                }, 500);

                xhr.addEventListener('loadend', function () {
                    clearInterval(progressInterval);
                });

                return xhr;
            },
            success: function (response) {
                if (response.success) {
                    // Complete progress
                    updateProgress(100, response.data.imported, response.data.imported);

                    // Show success message
                    setTimeout(function () {
                        Swal.fire({
                            title: 'Import Selesai!',
                            html: '<strong>Berhasil:</strong> ' + response.data.imported + ' data<br>' +
                                '<strong>Gagal:</strong> ' + response.data.failed + ' data',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload datatable and close modal
                            $('#customers_list').DataTable().ajax.reload();
                            $('#importModal').modal('hide');
                            resetImportModal();
                        });
                    }, 500);
                } else {
                    Swal.fire('Error', response.message || 'Import failed', 'error');
                    $('#btnClose').prop('disabled', false);
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error importing data';
                Swal.fire('Error', msg, 'error');
                $('#progressSection').hide();
                $('#previewSection').show();
                $('#btnImport').show();
                $('#btnClose').prop('disabled', false);
            }
        });
    }

    function updateProgress(percent, processed, total) {
        $('#progressBar').css('width', percent + '%')
            .attr('aria-valuenow', percent)
            .text(percent + '%');
        $('#progressPercent').text(percent);
        $('#processedCount').text(processed);
        $('#totalCount').text(total);
    }
});
