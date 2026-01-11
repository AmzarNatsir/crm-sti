@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Sales</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="{{route('sales.create')}}" class="btn btn-primary"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Sale</a>
                </div>
            </div>

            <div class="card border-0 rounded-0 mb-3">
                <div class="card-body">
                    <div class="row align-items-end g-2">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control form-control-sm" id="start_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control form-control-sm" id="end_date">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Customer</label>
                            <select class="form-select form-select-sm select2" id="customer_id">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select form-select-sm" id="payment_method_id">
                                <option value="">All Methods</option>
                                @foreach($payment_methods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm w-100" id="btn-filter"><i class="ti ti-filter me-1"></i>Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search" id="sales-search">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="sales-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Delivery</th>
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
        $('.select2').select2({
            width: '100%'
        });

        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: false, // Keeping it false as the controller returns the full mapped data, but we'll reload on filter
            ajax: {
                url: "{{ route('sales.datatables') }}",
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.customer_id = $('#customer_id').val();
                    d.payment_method_id = $('#payment_method_id').val();
                }
            },
            columns: [
                { data: 'nom', name: 'nom' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'customer', name: 'customer' },
                { data: 'date', name: 'date' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'total', name: 'total' },
                { data: 'status', name: 'status', render: function(data) {
                    let badgeClass = data === 'paid' ? 'bg-success' : 'bg-danger';
                    return `<span class="badge ${badgeClass}">${data.toUpperCase()}</span>`;
                }},
                { data: 'delivery_status', name: 'delivery_status', render: function(data) {
                    let badgeClass = data === 'completed' ? 'bg-success' : 'bg-warning';
                    return `<span class="badge ${badgeClass}">${data.toUpperCase()}</span>`;
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $('#btn-filter').click(function() {
            table.ajax.reload();
        });

        $('#sales-search').keyup(function() {
            table.search($(this).val()).draw();
        });
    });
</script>
@endpush
