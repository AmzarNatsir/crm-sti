<?php $page = 'survey-list'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content pb-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Contacts</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                    </ol>
                </nav>
            </div>
        </div>
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
                                <label class="form-label">Surveyor</label>
                                <select id="filter_surveyor" class="form-select select2">
                                    <option value="">All Surveyors</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
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
                                <label class="form-label">WhatsApp Number</label>
                                <input type="text" class="form-control" id="filter_wa" placeholder="Search by WhatsApp">
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
                    <input type="text" id="survey-search" class="form-control" placeholder="Global Search...">
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('surveys.create') }}" class="btn btn-primary">
                    <i class="ti ti-square-rounded-plus-filled me-1"></i>New Survey
                </a>
            </div>
        </div>
        <!-- /Search Filter -->

        <!-- Survey List Card -->
        <div class="card border-0 rounded-0">
            <div class="card-body">
                <div class="table-responsive custom-table">
                    <table class="table table-nowrap" id="survey-list-table">
                        <thead class="table-light">
                            <tr>
                                <th>Contact Name</th>
                                <th>Contact Type</th>
                                <th>Commodity</th>
                                <th>Location (Sub-district)</th>
                                <th>Date Created</th>
                                <th>Status</th>
                                <th>Surveyor</th>
                                <th class="text-end no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="row align-items-center mt-3">
                    <div class="col-md-6">
                        <div class="datatable-length" id="survey-list-length"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="datatable-paginate" id="survey-list-paginate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.footer')
    @endcomponent
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewSurveyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="fw-semibold">Survey Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="preview-survey-body">
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label mb-0">Follow-up User:</label>
                        <select id="followup-user-select" class="form-select" style="width: 200px;">
                            <option value="">Not Assigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="btn-assign-followup" class="btn btn-sm btn-info">
                            <i class="ti ti-user-check me-1"></i>Assign
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <div id="promote-btn-wrapper" style="display:none;">
                            <button type="button" id="preview-promote-btn" class="btn btn-success">
                                <i class="ti ti-user-plus me-1"></i>Promote to Prospect
                            </button>
                        </div>
                        <a href="#" id="preview-edit-btn" class="btn btn-primary">Edit Survey</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ URL::asset('js/survey-list.js') }}"></script>
@endpush

@endsection
