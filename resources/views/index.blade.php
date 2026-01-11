<?php $page = 'index'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-0">Home</h4>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <div class="daterangepick form-control w-auto d-flex align-items-center">
                        <i class="ti ti-calendar text-dark me-2"></i>
                        <span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Start Welcome Wrap -->
            <div class="welcome-wrap mb-4">
                <div class=" d-flex align-items-center justify-content-between flex-wrap gap-3 bg-dark rounded p-4">
                    <div class="">
                        <h2 class="mb-1 text-white fs-24">Welcome Back, {{ auth()->user()->name }}</h2>
                        <p class="text-light fs-14 mb-0">14 New Companies Subscribed Today !!!</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{url('company')}}" class="btn btn-danger btn-sm">Companies</a>
                        <a href="{{url('packages')}}" class="btn btn-light btn-sm">All Packages</a>
                    </div>
                </div>
            </div>
            <!-- Endc Welcome Wrap -->
        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
