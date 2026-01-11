<?php $page = 'products'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Products <span class="badge badge-soft-primary ms-2">{{ $count }}</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                        <div class="dropdown-menu  dropdown-menu-end">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Export as
                                        PDF</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as
                                        Excel </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- card start -->
            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary btn-add-product" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Product</a>
                </div>
                <div class="card-body">

                    <!-- Product List -->
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="products_list">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Merk</th>
                                    <th>Price</th>
                                    <th>Margin</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-6">
                            <div class="datatable-length"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="datatable-paginate"></div>
                        </div>
                    </div>
                    <!-- /Product List -->

                </div>
            </div>
            <!-- card end -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold" id="offcanvas-title">Add New Product</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="offcanvas-add-body"></div>
    </div>
@endsection

@push('scripts')
<script src="{{URL::asset('build/plugins/inputmask/inputmask.min.js')}}"></script>
<script>
    function initMask() {
        if(typeof Inputmask !== "undefined"){
            Inputmask({
                alias: "numeric",
                groupSeparator: ".",
                radixPoint: ",",
                autoGroup: true,
                digits: 0,
                digitsOptional: false,
                prefix: "",
                placeholder: "0",
                removeMaskOnSubmit: false,
                rightAlign: false,
                allowMinus: false
            }).mask(document.querySelectorAll("input[name='price']"));

            Inputmask({
                alias: "numeric",
                groupSeparator: ".",
                radixPoint: ",",
                autoGroup: true,
                digits: 1,
                digitsOptional: false,
                placeholder: "0",
                removeMaskOnSubmit: false,
                rightAlign: false,
                allowMinus: false
            }).mask(document.querySelectorAll("input[name='margin']"));
        }
    }



    $(document).on('submit', '.delete-form', function(e) {
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

    $(document).ready(function() {
        // AJAX Form Submission for Add/Edit
        $(document).on('submit', '#offcanvas_add form', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            var originalBtnText = submitBtn.text();
            
            submitBtn.prop('disabled', true).text('Saving...');

            // Remove formatting for Price and Margin before serialization if needed (or handle in backend, which we do)
            // But validation errors need to be handled carefully. 
            // Better to let backend handle sanitization.
            
            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(originalBtnText);
                    
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = form.find('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            if (input.next('.invalid-feedback').length === 0) {
                                input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                            }
                            // Also handle Select2 container error styling if needed
                            if (input.hasClass('select2-hidden-accessible')) {
                                input.next('.select2-container').after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                            }
                        });
                    } else {
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred. Please try again.';
                        alert(msg);
                    }
                }
            });
        });

        var table = $('#products_list').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{{ route('products.datatables') }}",
            columns: [
                { data: 'id', render: function(data, type, row) {
                    return '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>';
                }, orderable: false, searchable: false},
                {
                    data: 'image',
                    render: function(data, type, row) {
                        var img_url = data ? "{{ asset('storage/') }}/" + data : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(row.name) + '&background=random';
                        return '<div class="avatar avatar-md rounded-circle"><img src="' + img_url + '" alt="img" class="rounded-circle img-preview-trigger" style="cursor: pointer;"></div>';
                    }, orderable: false, searchable: false
                },
                { data: 'name', name: 'name' },
                { data: 'category', name: 'category' }, // Now carries Type info
                { data: 'merk', name: 'merk' },
                { data: 'price', name: 'price' },
                { data: 'margin', name: 'margin' },
                { data: 'status', name: 'is_active' },
                { data: 'created_at', name: 'created_at' },
                { data: 'id', render: function(data, type, row) {
                    return '<div class="dropdown"><a href="#" class="btn btn-icon btn-sm" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item btn-edit-product" href="javascript:void(0);" data-id="' + data + '"><i class="ti ti-edit me-1"></i> Edit</a></li><li><form action="{{ url("products") }}/' + data + '" method="POST" style="display:inline;" class="delete-form">@csrf @method("DELETE")<button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-1"></i> Delete</button></form></li></ul></div>';
                }, orderable: false, searchable: false }
            ]
        });



        $(document).on('click', '.btn-edit-product', function() {
            var id = $(this).data('id');
            var url = "{{ url('products') }}/" + id + "/edit";
            
            $.ajax({
                url: url,
                success: function(response) {
                    $('#offcanvas-title').text('Edit Product');
                    $('#offcanvas-add-body').html(response);
                    initMask();
                    // Init Select2
                    $('#offcanvas_add .select2').select2({
                        dropdownParent: $('#offcanvas_add'),
                        width: '100%'
                    });
                    var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_add'));
                    offcanvas.show();
                }
            });
        });

        $('.btn-add-product').on('click', function() {
            $('#offcanvas-title').text('Add New Product');
            $.ajax({
                url: "{{ route('products.create') }}",
                success: function(response) {
                    $('#offcanvas-add-body').html(response);
                    initMask();
                    // Init Select2
                    $('#offcanvas_add .select2').select2({
                        dropdownParent: $('#offcanvas_add'),
                        width: '100%'
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
</script>
@endpush
