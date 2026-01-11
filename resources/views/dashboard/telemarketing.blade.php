@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Dashboard Insight Survey & Telemarketing</h4>
                <p class="text-muted mb-0">Ringkasan hasil survey untuk pengambilan keputusan bisnis pupuk</p>
            </div>
        </div>

        <!-- Summary KPI Counters -->
        <div class="row">
            <div class="col-xl-2 col-sm-4 col-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-body p-3">
                        <p class="text-sm text-muted mb-1">Survey Masuk</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $stats['survey_in'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-4 col-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-body p-3">
                        <p class="text-sm text-muted mb-1">Prospek Petani</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $stats['prospect_farmers'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-4 col-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-body p-3">
                        <p class="text-sm text-muted mb-1">Pelanggan Aktif</p>
                        <h4 class="mb-0 fw-bold text-info">{{ $stats['active_customers'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-4 col-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-body p-3">
                        <p class="text-sm text-muted mb-1">Kasus/Tiket</p>
                        <h4 class="mb-0 fw-bold text-danger">{{ $stats['cases_tickets'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-4 col-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-body p-3">
                        <p class="text-sm text-muted mb-1">Mitra Toko</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $stats['store_partners'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-sm-4 col-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-body p-3">
                        <p class="text-sm text-muted mb-1">Kelompok Tani</p>
                        <h4 class="mb-0 fw-bold text-secondary">{{ $stats['farmer_groups'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decision Highlights -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3"><i class="ti ti-bulb text-warning me-2"></i>Decision Highlights</h5>
                <div class="row">
                    @foreach($decisionHighlights as $highlight)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-pinnacle text-primary me-2 fs-14"></i>
                            <span class="text-sm">{{ $highlight }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Insights Grid -->
        <div class="row">
            <!-- Prospek Petani -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-users-group text-success me-2"></i>Prospek Petani</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($prospectInsights as $insight)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="ti ti-point-filled text-success me-2 fs-10"></i>
                                <span class="text-sm">{{ $insight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Pelanggan -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-id-badge-2 text-info me-2"></i>Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($customerInsights as $insight)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="ti ti-point-filled text-info me-2 fs-10"></i>
                                <span class="text-sm">{{ $insight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Penyelesaian Masalah -->
            <div class="col-xl-4 col-lg-12 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-ticket text-danger me-2"></i>Penyelesaian Masalah</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($problemSolving as $insight)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="ti ti-point-filled text-danger me-2 fs-10"></i>
                                <span class="text-sm">{{ $insight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partner Insights Grid -->
        <div class="row">
            <!-- Mitra Toko -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-building-store text-warning me-2"></i>Mitra Toko / Pengecer</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($partnerInsights['store'] as $insight)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="ti ti-circle-check text-warning me-2 fs-12"></i>
                                <span class="text-sm">{{ $insight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Mitra Pengepul -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-truck-delivery text-primary me-2"></i>Mitra Pengepul</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($partnerInsights['collector'] as $insight)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="ti ti-circle-check text-primary me-2 fs-12"></i>
                                <span class="text-sm">{{ $insight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Kelompok Tani -->
            <div class="col-xl-4 col-lg-12 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-box-model-2 text-secondary me-2"></i>Kelompok Tani</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($partnerInsights['group'] as $insight)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="ti ti-circle-check text-secondary me-2 fs-12"></i>
                                <span class="text-sm">{{ $insight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow Up Priority Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0"><i class="ti ti-clipboard-list text-primary me-2"></i>Follow-Up Prioritas</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fs-12">Segmen</th>
                                <th class="fs-12">Isu</th>
                                <th class="fs-12">Aksi</th>
                                <th class="fs-12">PIC</th>
                                <th class="fs-12 text-center">Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($followUps as $item)
                            <tr>
                                <td class="text-sm fw-medium">{{ $item['segment'] }}</td>
                                <td class="text-sm">{{ $item['issue'] }}</td>
                                <td class="text-sm"><span class="badge badge-soft-primary">{{ $item['action'] }}</span></td>
                                <td class="text-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs rounded-circle me-2 bg-primary">
                                            <span class="fs-10 text-white">{{ substr($item['pic'], 0, 1) }}</span>
                                        </div>
                                        <span>{{ $item['pic'] }}</span>
                                    </div>
                                </td>
                                <td class="text-sm text-center">
                                    <span class="badge bg-info">
                                        {{ $item['deadline'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
