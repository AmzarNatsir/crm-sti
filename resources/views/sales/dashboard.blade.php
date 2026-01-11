@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Sales Dashboard</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Performance</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Row 1: Sales Focus -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill border-dashed border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-success text-success border border-success">
                                    <i class="ti ti-calendar-check"></i>
                                </span>
                                <span class="badge badge-soft-success">Today</span>
                            </div>
                            <div>
                                <p class="mb-1">Today's Total Sales</p>
                                <h4>Rp {{ number_format($todaySales, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill border-dashed border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-primary text-primary border border-primary">
                                    <i class="ti ti-calendar-event"></i>
                                </span>
                                <span class="badge badge-soft-primary">This Month</span>
                            </div>
                            <div>
                                <p class="mb-1">Sales This Month</p>
                                <h4>Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-primary text-primary border border-primary">
                                    <i class="ti ti-currency-dollar"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Total Revenue (All Time)</p>
                                <h4>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: General Performance -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-success text-success border border-success">
                                    <i class="ti ti-shopping-cart"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Total Orders</p>
                                <h4>{{ number_format($totalOrders) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-info text-info border border-info">
                                    <i class="ti ti-chart-line"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Avg. Order Value</p>
                                <h4>Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-warning text-warning border border-warning">
                                    <i class="ti ti-brand-campaignmonitor"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Active Campaigns</p>
                                <h4>{{ number_format($totalCampaigns) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Logistics & Delivery -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-info text-info border border-info">
                                    <i class="ti ti-truck-delivery"></i>
                                </span>
                                <span class="badge badge-soft-info">This Month</span>
                            </div>
                            <div>
                                <p class="mb-1">Deliveries (Total/Done)</p>
                                <h4>{{ $deliveryStats->total_deliveries }} / {{ $deliveryStats->completed_deliveries }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-success text-success border border-success">
                                    <i class="ti ti-package"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Completed Items</p>
                                <h4>{{ number_format($completedItems) }} pcs</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-danger text-danger border border-danger">
                                    <i class="ti ti-clock-hour-4"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Delivery SLA (Avg Days)</p>
                                <h4>{{ $avgSla }} Days</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sales Trend -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Revenue Growth Trend</h5>
                        </div>
                        <div class="card-body">
                            <div id="sales-trend-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- YoY Comparison -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Sales Comparison (YoY)</h5>
                            <span class="badge badge-soft-primary">{{ $previousYear }} vs {{ $currentYear }}</span>
                        </div>
                        <div class="card-body">
                            <div id="sales-comparison-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Salesperson Performance -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Top Sales Performance</h5>
                        </div>
                        <div class="card-body">
                            <div id="salesperson-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Campaign Performance -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Revenue by Campaign</h5>
                        </div>
                        <div class="card-body">
                            <div id="campaign-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Sales Table -->
                <div class="col-xl-8 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Recent Transactions</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>Salesperson</th>
                                            <th class="text-end">Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentSales as $sale)
                                        <tr>
                                            <td><p class="text-primary mb-0">{{ $sale->invoice_no }}</p></td>
                                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $sale->sales->name ?? 'N/A' }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $sale->payment_status == 'paid' ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                                    {{ ucfirst($sale->payment_status) }}
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
                <!-- Payment Status Distribution -->
                <div class="col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Payment Status</h5>
                        </div>
                        <div class="card-body">
                            <div id="payment-status-chart"></div>
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
        // 1. Revenue Trend Chart
        var trendOptions = {
            series: [{
                name: 'Revenue',
                data: [
                    @foreach($salesTrend as $data)
                        {{ $data->revenue }},
                    @endforeach
                ]
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: { show: false }
            },
            stroke: {
                width: 4,
                curve: 'smooth'
            },
            colors: ['#008FFB'],
            xaxis: {
                categories: [
                    @foreach($salesTrend as $data)
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
        var trendChart = new ApexCharts(document.querySelector("#sales-trend-chart"), trendOptions);
        trendChart.render();

        // 2. Salesperson Bar Chart
        var salesOptions = {
            series: [{
                name: 'Total Revenue',
                data: [
                    @foreach($salespersonPerformance as $sale)
                        {{ $sale->total_revenue }},
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
                    @foreach($salespersonPerformance as $sale)
                        '{{ $sale->name }}',
                    @endforeach
                ],
                labels: {
                    formatter: function (val) {
                        return "Rp " + (val/1000000).toFixed(1) + "M";
                    }
                }
            }
        };
        var salesChart = new ApexCharts(document.querySelector("#salesperson-chart"), salesOptions);
        salesChart.render();

        // 3. Campaign Performance Donut Chart
        var campaignOptions = {
            series: [
                @foreach($campaignPerformance as $camp)
                    {{ $camp->revenue }},
                @endforeach
            ],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: [
                @foreach($campaignPerformance as $camp)
                    '{{ $camp->name }}',
                @endforeach
            ],
            legend: {
                position: 'bottom'
            }
        };
        var campaignChart = new ApexCharts(document.querySelector("#campaign-chart"), campaignOptions);
        campaignChart.render();

        // 4. Payment Status Pie Chart
        var paymentOptions = {
            series: [
                @foreach($paymentStatus as $status)
                    {{ $status->count }},
                @endforeach
            ],
            chart: {
                type: 'pie',
                height: 300
            },
            labels: [
                @foreach($paymentStatus as $status)
                    '{{ ucfirst($status->payment_status) }}',
                @endforeach
            ],
            colors: ['#00E396', '#FEB019', '#FF4560'],
            legend: {
                position: 'bottom'
            }
        };
        var paymentChart = new ApexCharts(document.querySelector("#payment-status-chart"), paymentOptions);
        paymentChart.render();

        // 5. Year-over-Year Comparison Chart
        var comparisonOptions = {
            series: [{
                name: 'Year {{ $previousYear }}',
                data: @json($previousYearData)
            }, {
                name: 'Year {{ $currentYear }}',
                data: @json($currentYearData)
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "Rp " + (val/1000000).toFixed(1) + "M";
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            colors: ['#FEB019', '#008FFB'],
        };
        var comparisonChart = new ApexCharts(document.querySelector("#sales-comparison-chart"), comparisonOptions);
        comparisonChart.render();
    });
</script>
@endpush
