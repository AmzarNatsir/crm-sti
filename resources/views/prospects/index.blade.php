<?php $page = 'prospects'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Prospects</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Prospects</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                        <div class="dropdown-menu  dropdown-menu-end">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Export as PDF</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as Excel</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Search Filter -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
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
                                    <label class="form-label">Contact Type</label>
                                    <select id="filter_contact_type" class="form-select select2">
                                        <option value="">All Contact Types</option>
                                        <option value="Farmer Prospect">Farmer Prospect</option>
                                        <option value="STI Customer">STI Customer</option>
                                        <option value="Shop/Retailer">Store/Retailer</option>
                                        <option value="Partner/Collector">Partner/Collector</option>
                                        <option value="Farmer Group Head">Farmer Group Head</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Commodity</label>
                                    <select class="form-select select2" id="filter_commodity">
                                        <option value="">All Commodities</option>
                                        @foreach($commodities as $commodity)
                                            <option value="{{ $commodity->id }}">{{ $commodity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="filter_name" placeholder="Search by name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ID Number</label>
                                    <input type="text" class="form-control" id="filter_identity_no" placeholder="Search by ID number">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="filter_phone" placeholder="Search by phone">
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-outline-light w-100" id="filter_reset">Reset</button>
                                    <button type="button" class="btn btn-primary w-100" id="filter_apply">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-primary btn-add-customer" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add Prospect</a>
                </div>
            </div>
            <!-- table header -->

            <!-- Prospect List -->
            <div class="table-responsive table-nowrap custom-table">
                <table class="table table-nowrap" id="prospects_list">
                    <thead class="table-light">
                        <tr>
                            <th class="no-sort">
                                <div class="form-check form-check-md">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th>Image</th>
                            <th>Contact Type</th>
                            <th>Status</th>
                            <th>Commodity</th>
                            <th>Prospect Name</th>
                            <th>Identity No</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Location</th>
                            <th>Created Date</th>
                            <th class="text-end no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="datatable-length"></div>
                </div>
                <div class="col-md-6">
                    <div class="datatable-paginate"></div>
                </div>
            </div>
            <!-- /Prospect List -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- Offcanvas Add/Edit -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold" id="offcanvas-title">Add New Prospect</h5>
            <button type="button" class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="offcanvas-add-body"></div>
    </div>

@endsection

@push('scripts')
<script>
    window.prospectsDatatableUrl = "{{ route('prospects.datatables') }}";
    window.prospectsBaseUrl = "{{ url('prospects') }}";
    window.customersBaseUrl = "{{ url('customers') }}";
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ URL::asset('js/prospect-list.js') }}"></script>
@endpush
