@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Commodity Reference</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Commodities</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-primary btn-add-commodity"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Commodity</a>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="commodity-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Season (Month)</th>
                                    <th>Fertilization In Season</th>
                                    <th>Description</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        @component('components.footer')
        @endcomponent

    </div>

    <!-- Offcanvas for Add/Edit -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_commodity">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold" id="offcanvas-title">Add New Commodity</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="offcanvas-body"></div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#commodity-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ref-commodity.datatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'season', name: 'season' },
                { data: 'fertillization_in_season', name: 'fertillization_in_season' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Open Add Form
        $('.btn-add-commodity').on('click', function() {
            $('#offcanvas-title').text('Add New Commodity');
            $.ajax({
                url: "{{ route('ref-commodity.create') }}",
                success: function(response) {
                    $('#offcanvas-body').html(response);
                    var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_commodity'));
                    offcanvas.show();
                }
            });
        });

        // Open Edit Form
        $(document).on('click', '.open-edit', function() {
            var url = $(this).data('url');
            $('#offcanvas-title').text('Edit Commodity');
            $.ajax({
                url: url,
                success: function(response) {
                    $('#offcanvas-body').html(response);
                    var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_commodity'));
                    offcanvas.show();
                }
            });
        });

        // Submit Form via AJAX
        $(document).on('submit', '#offcanvas_commodity form', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            var originalBtnText = submitBtn.text();
            
            submitBtn.prop('disabled', true).text('Saving...');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#offcanvas_commodity').offcanvas('hide');
                    showToast('success', response.success);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(originalBtnText);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = form.find('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                        });
                    } else {
                        showToast('error', 'An error occurred. Please try again.');
                    }
                }
            });
        });

        // Delete Button
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = "{{ url('ref-commodity') }}/" + id;
            
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
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            showToast('success', response.success);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            showToast('error', 'Error deleting commodity');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
