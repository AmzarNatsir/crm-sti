@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Approval Center</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="approvalTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Requester</th>
                                <th>Details</th>
                                <th>Submitted At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let table = $('#approvalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('approvals.datatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'category', name: 'category', render: function(data) {
                    return data.replace(/_/g, ' ').toUpperCase();
                }},
                { data: 'requester_name', name: 'requester_name' },
                { data: 'details', name: 'details' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        window.approve = function(id) {
            Swal.fire({
                title: 'Approve Request?',
                text: "Add a note (optional)",
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                showLoaderOnConfirm: true,
                preConfirm: (note) => {
                    return $.ajax({
                        url: `{{ url('approvals') }}/${id}/action`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: 'approve',
                            notes: note
                        }
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error.responseJSON.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Approved!', 'Request has been approved.', 'success');
                    table.ajax.reload();
                }
            });
        }

        window.reject = function(id) {
            Swal.fire({
                title: 'Reject Request?',
                text: "Reason for rejection",
                input: 'text',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write a reason!'
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Reject',
                showLoaderOnConfirm: true,
                preConfirm: (note) => {
                    return $.ajax({
                        url: `{{ url('approvals') }}/${id}/action`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: 'reject',
                            notes: note
                        }
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error.responseJSON.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Rejected!', 'Request has been rejected.', 'success');
                    table.ajax.reload();
                }
            });
        }
    });
</script>
@endpush
