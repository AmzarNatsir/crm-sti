@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">CRM Dashboard</h4>
                <p class="text-muted mb-0">Customer Relationship Insights & Performance</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="daterangepick form-control w-auto d-flex align-items-center">
                    <i class="ti ti-calendar text-dark me-2"></i>
                    <span class="reportrange-picker-field text-dark">{{ date('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- KPI Row -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="avatar avatar-md bg-transparent-primary text-primary border border-primary">
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="badge bg-success">Live</span>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Total Customers</p>
                            <h4 class="fw-bold">{{ number_format($totalCustomers) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="avatar avatar-md bg-transparent-success text-success border border-success">
                                <i class="ti ti-heart-handshake"></i>
                            </span>
                            <span class="badge bg-primary">Lifetime</span>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Avg CLTV</p>
                            <h4 class="fw-bold">Rp {{ number_format($avgCLTV, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="avatar avatar-md bg-transparent-info text-info border border-info">
                                <i class="ti ti-receipt-2"></i>
                            </span>
                            <span class="badge bg-info">Monthly</span>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Avg ARPU</p>
                            <h4 class="fw-bold">Rp {{ number_format($avgARPU, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="avatar avatar-md bg-transparent-danger text-danger border border-danger">
                                <i class="ti ti-user-minus"></i>
                            </span>
                            <span class="badge bg-danger">Churn</span>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Avg Churn Rate</p>
                            <h4 class="fw-bold">{{ number_format($churnRate, 1) }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Commodity Distribution (Pie/Donut) -->
            <div class="col-xl-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Commodity Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="commodity-chart"></div>
                    </div>
                </div>
            </div>

            <!-- ROI Campaign List -->
            <div class="col-xl-8 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Campaign ROI Analysis</h5>
                        <a href="{{ url('ref-compign') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Campaign Name</th>
                                        <th>Revenue</th>
                                        <th>Budget</th>
                                        <th class="text-center">ROI</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($campaigns as $camp)
                                    <tr>
                                        <td>{{ $camp->name }}</td>
                                        <td>Rp {{ number_format($camp->actual_revenue, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($camp->badget, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="fw-bold {{ $camp->roi >= 1 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($camp->roi, 2) }}x
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $camp->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $camp->status }}
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

        <!-- Area Mapping Row -->
        <div class="row">
            <!-- Top 5 By Regency -->
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Customer Mapping (By Regency)</h5>
                        <span class="badge bg-soft-info text-info">Top 5</span>
                    </div>
                    <div class="card-body">
                        <div id="regency-chart"></div>
                    </div>
                </div>
            </div>
            <!-- Top 5 By Province -->
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Customer Mapping (By Province)</h5>
                        <span class="badge bg-soft-primary text-primary">Top 5</span>
                    </div>
                    <div class="card-body">
                        <div id="province-chart"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ URL::asset('build/plugins/apexchart/apexcharts.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // 1. Commodity Distribution Chart (Donut)
        var commodityOptions = {
            series: [
                @foreach($commodityDistribution as $item)
                    {{ $item->count }},
                @endforeach
            ],
            labels: [
                @foreach($commodityDistribution as $item)
                    '{{ $item->name }}',
                @endforeach
            ],
            chart: {
                type: 'donut',
                height: 350
            },
            legend: {
                position: 'bottom'
            },
            colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0']
        };
        new ApexCharts(document.querySelector("#commodity-chart"), commodityOptions).render();

        // 2. Regency Bar Chart
        var regencyOptions = {
            series: [{
                name: 'Customers',
                data: [
                    @foreach($topRegencies as $item)
                        {{ $item->count }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            xaxis: {
                categories: [
                    @foreach($topRegencies as $item)
                        '{{ $item->name }}',
                    @endforeach
                ],
            },
            colors: ['#00B5B5']
        };
        new ApexCharts(document.querySelector("#regency-chart"), regencyOptions).render();

        // 3. Province Bar Chart
        var provinceOptions = {
            series: [{
                name: 'Customers',
                data: [
                    @foreach($topProvinces as $item)
                        {{ $item->count }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: '45%'
                }
            },
            xaxis: {
                categories: [
                    @foreach($topProvinces as $item)
                        '{{ $item->name }}',
                    @endforeach
                ],
            },
            colors: ['#4C66E4']
        };
        new ApexCharts(document.querySelector("#province-chart"), provinceOptions).render();
    });
</script>
@endpush
