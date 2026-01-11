@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Activities</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Activities</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                 <a href="{{ route('activities.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Add Activity</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap mb-0" id="activityTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>User</th>
                                <th>Notes</th>
                                <th>Follow Up Date</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
<script type="text/javascript">
    $(function () {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#activityTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('activities.datatables') }}",
            order: [[7, 'desc']], // Default order by Created At
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'type', name: 'type'},
                {data: 'customer_name', name: 'customer.name'},
                {data: 'user_name', name: 'user.name'},
                {data: 'notes', name: 'notes'},
                {data: 'follow_up_date', name: 'follow_up_date'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('body').on('click', '.deleteActivity', function () {
            var activity_id = $(this).data("id");
            if(confirm("Are you sure want to delete !")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('activities') }}"+'/'+activity_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
</script>
@endpush
