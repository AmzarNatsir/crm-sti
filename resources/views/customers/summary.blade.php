@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Customer Summary: {{ $customer->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customers') }}">Customers</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Individual Analytics</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('customers') }}" class="btn btn-outline-primary"><i class="ti ti-arrow-left me-1"></i>Back to List</a>
                </div>
            </div>

            <div class="row">
                <!-- Customer Profile Card -->
                <div class="col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar avatar-xxl rounded-circle mb-3">
                                    <img src="{{ $customer->photo_profile ? asset($customer->photo_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=random' }}" alt="img" class="rounded-circle">
                                </div>
                                <h4 class="mb-1">{{ $customer->name }}</h4>
                                <p class="text-muted mb-0">{{ $customer->company_name }}</p>
                                <span class="badge {{ $customer->type == 'customer' ? 'badge-soft-success' : ($customer->type == 'prospect' ? 'badge-soft-danger' : 'badge-soft-primary') }} mt-2">
                                    {{ ucfirst($customer->type) }}
                                </span>
                            </div>
                            <div class="border-top pt-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="ti ti-phone me-2 text-primary"></i>
                                        <span>{{ $customer->phone }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="ti ti-mail me-2 text-primary"></i>
                                        <span>{{ $customer->email }}</span>
                                    </li>
                                    <li class="d-flex align-items-start mb-2">
                                        <i class="ti ti-map-pin me-2 text-primary mt-1"></i>
                                        <span>{{ $customer->address }}, {{ $customer->city }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Stats -->
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-md-4 d-flex">
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
                        <div class="col-md-4 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="avatar avatar-md bg-transparent-info text-info border border-info">
                                            <i class="ti ti-currency-dollar"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-1">Total Spent</p>
                                        <h4>Rp {{ number_format($totalSpend, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="avatar avatar-md bg-transparent-warning text-warning border border-warning">
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
                    </div>

                    <div class="card flex-fill mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Shopping Time Distribution (Hourly)</h5>
                        </div>
                        <div class="card-body">
                            <div id="time-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Top Products -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Favorite Products</h5>
                        </div>
                        <div class="card-body">
                            <div id="product-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Payment Methods -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Preferred Payment Methods</h5>
                        </div>
                        <div class="card-body">
                            <div id="payment-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Favorite Brands Table -->
                <div class="col-xl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Favorite Brands</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Brand Name</th>
                                            <th class="text-end">Units Purchased</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topBrands as $brand)
                                        <tr>
                                            <td>{{ $brand->name }}</td>
                                            <td class="text-end fw-bold">{{ number_format($brand->total_qty) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">No purchase history found.</td>
                                        </tr>
                                        @endforelse
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
        // 1. Top Products Bar Chart
        var productOptions = {
            series: [{
                name: 'Quantity Purchased',
                data: [
                    @foreach($topProducts as $item)
                        {{ $item->total_qty }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 300
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: [
                    @foreach($topProducts as $item)
                        '{{ $item->product->name ?? "Unknown" }}',
                    @endforeach
                ],
            },
            colors: ['#008FFB']
        };
        var productChart = new ApexCharts(document.querySelector("#product-chart"), productOptions);
        productChart.render();

        // 2. Payment Method Donut Chart
        var paymentOptions = {
            series: [
                @foreach($paymentMethods as $method)
                    {{ $method->count }},
                @endforeach
            ],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: [
                @foreach($paymentMethods as $method)
                    '{{ $method->name }}',
                @endforeach
            ],
            legend: {
                position: 'bottom'
            }
        };
        var paymentChart = new ApexCharts(document.querySelector("#payment-chart"), paymentOptions);
        paymentChart.render();

        // 3. Shopping Time Line Chart
        var timeOptions = {
            series: [{
                name: "Orders",
                data: [
                    @foreach($shoppingTime as $count)
                        {{ $count }},
                    @endforeach
                ]
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#775DD0'],
            xaxis: {
                categories: Array.from({length: 24}, (_, i) => i + ':00'),
            }
        };
        var timeChart = new ApexCharts(document.querySelector("#time-chart"), timeOptions);
        timeChart.render();
    });
</script>
@endpush
