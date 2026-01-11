@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Campaign Reference</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Campaigns</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="{{route('ref-compign.create')}}" class="btn btn-primary"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Campaign</a>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="compign-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Campaign Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Target Budget</th>
                                    <th>Status</th>
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

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#compign-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ref-compign.datatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'badget', name: 'badget' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $(document).on('change', '.status-toggle', function() {
            let id = $(this).data('id');
            let status = $(this).prop('checked') ? 'active' : 'inactive';

            $.ajax({
                url: "{{ route('ref-compign.update-status') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },
                success: function(response) {
                    showToast('success', response.success);
                },
                error: function(xhr) {
                    showToast('error', 'Error updating status');
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
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
                        url: "{{ url('ref-compign') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            table.ajax.reload();
                            showToast('success', response.success);
                        },
                        error: function(xhr) {
                            showToast('error', 'Error deleting campaign');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
