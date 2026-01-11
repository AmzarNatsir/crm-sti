@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Product Dashboard</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Analytics</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-primary text-primary border border-primary">
                                    <i class="ti ti-package"></i>
                                </span>
                                <span class="badge bg-success">{{ $activeProducts }} Active</span>
                            </div>
                            <div>
                                <p class="mb-1">Total Products</p>
                                <h4>{{ number_format($totalProducts) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-success text-success border border-success">
                                    <i class="ti ti-shopping-cart"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Total Units Sold</p>
                                <h4>{{ number_format($totalUnitsSold) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-info text-info border border-info">
                                    <i class="ti ti-currency-dollar"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Market Value (Current Portfolio)</p>
                                <h4>Rp {{ number_format($totalStockValue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-warning text-warning border border-warning">
                                    <i class="ti ti-chart-pie"></i>
                                </span>
                                <span class="badge bg-info">{{ $categorySales->count() }} Categories</span>
                            </div>
                            <div>
                                <p class="mb-1">Category Coverage</p>
                                <h4>98.5% <small class="text-muted fs-12">Active selling</small></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Monthly Sales Trend -->
                <div class="col-xl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Monthly Revenue Performance</h5>
                        </div>
                        <div class="card-body">
                            <div id="trend-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Brand Revenue -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Revenue by Brand (Top 10)</h5>
                        </div>
                        <div class="card-body">
                            <div id="brand-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Category Revenue -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Category Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div id="category-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Top Revenue Products Table -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Top Revenue Generators</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-end">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topRevenueProducts as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->image)
                                                        <img src="{{ asset($item->product->image) }}" class="avatar avatar-sm rounded me-2" alt="img">
                                                    @endif
                                                    <span>{{ $item->product->name ?? 'Unknown' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Top Moving Products Table -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Best Selling (Volume)</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-end">Units Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topMovingProducts as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Unknown' }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->total_qty) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
        // 1. Monthly Trend Chart
        var trendOptions = {
            series: [{
                name: 'Revenue',
                data: [
                    @foreach($monthlyTrend as $data)
                        {{ $data->revenue }},
                    @endforeach
                ]
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            colors: ['#775DD0'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: [
                    @foreach($monthlyTrend as $data)
                        '{{ $data->month }}',
                    @endforeach
                ],
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        };
        var trendChart = new ApexCharts(document.querySelector("#trend-chart"), trendOptions);
        trendChart.render();

        // 2. Brand Sales Bar Chart
        var brandOptions = {
            series: [{
                name: 'Revenue',
                data: [
                    @foreach($brandSales as $brand)
                        {{ $brand->revenue }},
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
            colors: ['#00E396'],
            xaxis: {
                categories: [
                    @foreach($brandSales as $brand)
                        '{{ $brand->name }}',
                    @endforeach
                ],
                labels: {
                    formatter: function (val) {
                        return (val/1000000).toFixed(1) + "M";
                    }
                }
            }
        };
        var brandChart = new ApexCharts(document.querySelector("#brand-chart"), brandOptions);
        brandChart.render();

        // 3. Category Distribution Pie Chart
        var categoryOptions = {
            series: [
                @foreach($categorySales as $cat)
                    {{ $cat->revenue }},
                @endforeach
            ],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: [
                @foreach($categorySales as $cat)
                    '{{ $cat->name }}',
                @endforeach
            ],
            legend: {
                position: 'bottom'
            }
        };
        var categoryChart = new ApexCharts(document.querySelector("#category-chart"), categoryOptions);
        categoryChart.render();
    });
</script>
@endpush
