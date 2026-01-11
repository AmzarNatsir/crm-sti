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
                    <h4 class="mb-1">Common Position</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Common Position</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-primary btn-add-position" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add Add New Position</a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- card start -->
            <div class="card border-0 rounded-0">
                <div class="card-body">
                    <!-- Position List -->
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="positions_list">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- /Type List -->

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
            <h5 class="fw-semibold" id="offcanvas-title">Add New Position</h5>
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
<script>
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

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
                    url: url,
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(response) {
                        showToast('success', response.success || 'Position deleted successfully');
                        $('#positions_list').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        showToast('error', 'Error deleting position');
                    }
                });
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

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#offcanvas_add').offcanvas('hide');
                    showToast('success', response.success || 'Saved successfully');
                    $('#positions_list').DataTable().ajax.reload();
                    submitBtn.prop('disabled', false).text(originalBtnText);
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
                        });
                    } else {
                        showToast('error', 'An error occurred. Please try again.');
                    }
                }
            });
        });

        var table = $('#positions_list').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{{ route('common-position.datatables') }}",
            columns: [
                { data: 'nom', name: 'nom' },
                { data: 'name', name: 'name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'id', render: function(data, type, row) {
                    return '<div class="dropdown"><a href="#" class="btn btn-icon btn-sm" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item btn-edit-position" href="javascript:void(0);" data-id="' + data + '"><i class="ti ti-edit me-1"></i> Edit</a></li><li><form action="{{ url("common-position") }}/' + data + '" method="POST" style="display:inline;" class="delete-form">@csrf @method("DELETE")<button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-1"></i> Delete</button></form></li></ul></div>';
                }, orderable: false, searchable: false }
            ]
        });

        $(document).on('click', '.btn-edit-position', function() {
            var id = $(this).data('id');
            var url = "{{ url('common-position') }}/" + id + "/edit";

            $.ajax({
                url: url,
                success: function(response) {
                    $('#offcanvas-title').text('Edit Position');
                    $('#offcanvas-add-body').html(response);
                    var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_add'));
                    offcanvas.show();
                }
            });
        });

        $('.btn-add-position').on('click', function() {
            $('#offcanvas-title').text('Add New Position');
            $.ajax({
                url: "{{ route('common-position.create') }}",
                success: function(response) {
                    $('#offcanvas-add-body').html(response);
                }
            });
        });
    });
</script>
@endpush
