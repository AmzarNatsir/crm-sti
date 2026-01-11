<?php $page = 'sales-delivery-reports'; ?>
@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Sales Delivery Reports</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Delivery Reports</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Filter -->
            <div class="card border-0 mb-3 shadow-none">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filter<i class="ti ti-chevron-down ms-2"></i></a>
                            <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                    <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
                                    <div class="mb-3">
                                        <label class="form-label">Delivery Staff</label>
                                        <select id="filter_employee" class="form-select select2">
                                            <option value="">All Personnel</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" id="filter_start_date" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" id="filter_end_date" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Export As</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-danger w-100" id="export_pdf"><i class="ti ti-file-type-pdf me-1"></i>PDF</button>
                                            <button type="button" class="btn btn-outline-success w-100" id="export_excel"><i class="ti ti-file-type-xls me-1"></i>Excel</button>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-outline-light w-100" id="filter_reset">Reset</button>
                                        <button type="button" class="btn btn-primary w-100" id="filter_apply">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filter -->

            <!-- Delivery List -->
            <div class="card border-0">
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="delivery_report_table">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Delivery Date</th>
                                    <th>Arrival Date</th>
                                    <th>Personnel</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="row align-items-center mt-3">
                        <div class="col-md-6">
                            <div class="datatable-length"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="datatable-paginate"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Delivery List -->

        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#delivery_report_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reports.sales-delivery.datatables') }}",
                data: function(d) {
                    d.employee_id = $('#filter_employee').val();
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                }
            },
            columns: [
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'delivery_date', name: 'delivery_date' },
                { data: 'arrival_date', name: 'arrival_date' },
                { data: 'employee_name', name: 'employee_name' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data) {
                        let badgeClass = data === 'Completed' ? 'badge-soft-success' : 'badge-soft-primary';
                        return `<span class="badge ${badgeClass}">${data.toUpperCase()}</span>`;
                    }
                },
            ],
            order: [[2, 'desc']],
            dom: '<"top">rt<"bottom"lp><"clear">',
            initComplete: function () {
                $('.datatable-length').append($('.dataTables_length'));
                $('.datatable-paginate').append($('.dataTables_paginate'));
            }
        });

        $('#filter_apply').on('click', function() {
            table.draw();
        });

        $('#filter_reset').on('click', function() {
            $('#filter_employee').val('').trigger('change');
            $('#filter_start_date').val('');
            $('#filter_end_date').val('');
            table.draw();
        });

        $('#export_excel').on('click', function() {
            const employee = $('#filter_employee').val();
            const start = $('#filter_start_date').val();
            const end = $('#filter_end_date').val();
            window.location.href = "{{ route('reports.sales-delivery.export.excel') }}?employee_id=" + employee + "&start_date=" + start + "&end_date=" + end;
        });

        $('#export_pdf').on('click', function() {
            const employee = $('#filter_employee').val();
            const start = $('#filter_start_date').val();
            const end = $('#filter_end_date').val();
            window.open("{{ route('reports.sales-delivery.export.pdf') }}?employee_id=" + employee + "&start_date=" + start + "&end_date=" + end, '_blank');
        });
    });
</script>
@endpush
