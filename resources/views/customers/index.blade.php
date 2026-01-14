<?php $page = 'customers'; ?>
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
                    <h4 class="mb-1">Customers <?= Route::currentRouteName(); ?><span class="badge badge-soft-primary ms-2">125</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customers</li>
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
                                        PDF
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as
                                        Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- table header -->
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
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
    <div class="d-flex align-items-center shadow p-1 rounded border bg-white view-icons">
        <a href="{{url('customers')}}" class="btn btn-sm p-1 border-0 fs-14 active"><i class="ti ti-list-tree"></i></a>
    </div>
    <a href="javascript:void(0);" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal"><i class="ti ti-file-import me-1"></i>Import Customer</a>
    <a href="javascript:void(0);" class="btn btn-primary btn-add-customer" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Customer</a>
</div>
            </div>
            <!-- table header -->

            <!-- leads List -->
            <div class="table-responsive table-nowrap custom-table">
                <table class="table table-nowrap" id="customers_list">
                    <thead class="table-light">
                        <tr>
                            <th class="no-sort">
                                <div class="form-check form-check-md">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th>Image</th>
                            <!-- <th>Type</th> -->
                            <th>Commodity</th>
                            <th>Customer Name</th>
                            <th>Identity No</th>
                            <th>Date of Birth</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>District / Regency</th>
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
            <!-- /leads List -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

    <!-- Offcanvas Add/Edit -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold" id="offcanvas-title">Add New Customer</h5>
            <button type="button" class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="offcanvas-add-body"></div>
    </div>

    <!-- Import Customer Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Customer Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Step 1: File Upload -->
                    <div id="uploadSection">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Instructions:</strong> Download the template, fill in your customer data, then upload the file to preview and import.
                        </div>
                        
                        <div class="mb-3">
                            <a href="{{ route('customers.import.template') }}" class="btn btn-outline-primary">
                                <i class="ti ti-download me-1"></i>Download Template
                            </a>
                        </div>

                        <div class="mb-3">
                            <label for="importFile" class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="importFile" accept=".xlsx,.xls">
                            <div class="form-text">Maximum file size: 5MB. Accepted formats: .xlsx, .xls</div>
                            <div id="fileError" class="text-danger mt-2" style="display: none;"></div>
                        </div>

                        <div id="fileInfo" class="alert alert-success" style="display: none;">
                            <i class="ti ti-file-check me-2"></i>
                            <strong>File selected:</strong> <span id="fileName"></span> (<span id="fileSize"></span>)
                        </div>
                    </div>

                    <!-- Step 2: Preview Section -->
                    <div id="previewSection" style="display: none;">
                        <div class="alert alert-primary">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Preview:</strong> Review the data below before importing. Invalid rows are highlighted in red.
                        </div>

                        <!-- Summary Stats -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0" id="totalRows">0</h3>
                                        <p class="text-muted mb-0">Total Rows</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 text-success" id="validRows">0</h3>
                                        <p class="text-muted mb-0">Valid Rows</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0 text-danger" id="invalidRows">0</h3>
                                        <p class="text-muted mb-0">Invalid Rows</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Table -->
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-sm" id="previewTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Row</th>
                                        <th>Status</th>
                                        <th>Name</th>
                                        <th>Identity No</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Errors</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Step 3: Progress Section -->
                    <div id="progressSection" style="display: none;">
                        <div class="alert alert-info">
                            <i class="ti ti-loader me-2"></i>
                            <strong>Importing...</strong> Please wait while we import your data.
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Progress: <span id="progressPercent">0</span>%</label>
                            <div class="progress" style="height: 25px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    0%
                                </div>
                            </div>
                        </div>

                        <div id="progressInfo" class="text-center">
                            <p class="mb-0">Processing: <span id="processedCount">0</span> / <span id="totalCount">0</span> records</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnClose">Close</button>
                    <button type="button" class="btn btn-primary" id="btnPreview" style="display: none;">
                        <i class="ti ti-eye me-1"></i>Preview Data
                    </button>
                    <button type="button" class="btn btn-success" id="btnImport" style="display: none;">
                        <i class="ti ti-upload me-1"></i>Import Data
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    window.customersDatatableUrl = "{{ route('customers.datatables') }}";
    window.customersBaseUrl = "{{ url('customers') }}";
    window.csrfToken = "{{ csrf_token() }}";
    window.importPreviewUrl = "{{ route('customers.import.preview') }}";
    window.importProcessUrl = "{{ route('customers.import.process') }}";
</script>
@endpush
