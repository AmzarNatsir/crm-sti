<?php $page = 'permissions'; ?>
@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Permissions <span class="badge badge-soft-primary ms-2">{{ $count }}</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- card start -->
            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" id="search_permissions" placeholder="Search Permissions">
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary btn-add-permission"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Permission</a>
                </div>
                <div class="card-body">

                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="permission_list_table">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Permission Name</th>
                                    <th>Guard Name</th>
                                    <th>Roles</th>
                                    <th>Created</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

                </div>
            </div>
            <!-- card end -->

        </div>

        @component('components.footer')
        @endcomponent

    </div>

    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add_permission">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Add New Permission</h5>
            <button type="button" class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="offcanvas-add-permission-body"></div>
    </div>

    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_edit_permission">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Edit Permission</h5>
            <button type="button" class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="offcanvas-edit-permission-body"></div>
    </div>
@endsection
