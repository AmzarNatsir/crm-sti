@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Customer Dashboard</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Analytics</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row">
                <div class="col-xl col-sm-6 d-flex">
                    <div class="card flex-fill cursor-pointer show-customer-list" data-filter="all" style="cursor: pointer;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-primary text-primary border border-primary">
                                    <i class="ti ti-users"></i>
                                </span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div>
                                <p class="mb-1">Total Customers</p>
                                <h4>{{ number_format($totalCustomers) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-sm-6 d-flex">
                    <div class="card flex-fill cursor-pointer show-customer-list" data-filter="new" style="cursor: pointer;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-info text-info border border-info">
                                    <i class="ti ti-user-plus"></i>
                                </span>
                                <span class="badge {{ $customerGrowth >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $customerGrowth >= 0 ? '+' : '' }}{{ number_format($customerGrowth, 1) }}%
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">New Customers (Month)</p>
                                <h4>{{ number_format($newCustomersCount) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-success text-success border border-success">
                                    <i class="ti ti-shopping-cart"></i>
                                </span>
                                <span class="badge bg-primary">Lifetime</span>
                            </div>
                            <div>
                                <p class="mb-1">Total Orders</p>
                                <h4>{{ number_format($totalOrders) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-sm-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="avatar avatar-md bg-transparent-danger text-danger border border-danger">
                                    <i class="ti ti-currency-dollar"></i>
                                </span>
                            </div>
                            <div>
                                <p class="mb-1">Total Revenue</p>
                                <h4>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-sm-6 d-flex">
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

            <div class="row">
                <!-- Frequently Purchased Products -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Top Products</h5>
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
                            <h5 class="card-title">Payment Methods</h5>
                        </div>
                        <div class="card-body">
                            <div id="payment-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Shopping Time Patterns -->
                <div class="col-xl-12 d-flex">
                    <div class="card flex-fill">
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
                <!-- Top Customers -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Top Spending Customers</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Customer</th>
                                            <th>Company</th>
                                            <th class="text-end">Total Spend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topCustomers as $order)
                                        <tr>
                                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $order->customer->company_name ?? 'N/A' }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($order->total_spend, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Favorite Brands -->
                <div class="col-xl-6 d-flex">
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
                                            <th class="text-end">Units Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topBrands as $brand)
                                        <tr>
                                            <td>{{ $brand->name }}</td>
                                            <td class="text-end fw-bold">{{ number_format($brand->total_qty) }}</td>
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

    <!-- Customer List Modal -->
    <div class="modal fade" id="customerListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="modal-title">Customer List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap mb-0 w-100" id="customer-dashboard-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Commodity</th>
                                    <th>Phone</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via DataTables -->
                            </tbody>
                        </table>
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
        let customerDataTable = null;

        // Customer List Preview Modal logic
        $('.show-customer-list').on('click', function() {
            const filter = $(this).data('filter');
            const title = filter === 'new' ? 'New Customers (This Month)' : 'Total Customers';
            $('#modal-title').text(title);
            
            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#customer-dashboard-table')) {
                customerDataTable.destroy();
                $('#customer-dashboard-table tbody').empty();
            }

            customerDataTable = $('#customer-dashboard-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('customers-dashboard.list') }}",
                    data: function(d) {
                        d.filter = filter;
                    }
                },
                columns: [
                    { 
                        data: null, 
                        name: 'no', 
                        orderable: false, 
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'name', name: 'name' },
                    { data: 'company_name', name: 'company_name' },
                    { data: 'commodity_name', name: 'commodity_name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'created_at', name: 'created_at' }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "_MENU_",
                    paginate: {
                        next: '<i class="ti ti-chevron-right"></i>',
                        previous: '<i class="ti ti-chevron-left"></i>'
                    }
                },
                order: [[5, 'desc']], // Order by created_at
                pageLength: 10
            });
            
            $('#customerListModal').modal('show');
        });
        // 1. Top Products Bar Chart
        var productOptions = {
            series: [{
                name: 'Quantity Sold',
                data: [
                    @foreach($topProducts as $item)
                        {{ $item->total_qty }},
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
            colors: ['#00E396']
        };
        var productChart = new ApexCharts(document.querySelector("#product-chart"), productOptions);
        productChart.render();

        // 2. Payment Method Pie Chart
        var paymentOptions = {
            series: [
                @foreach($paymentMethods as $method)
                    {{ $method->count }},
                @endforeach
            ],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: [
                @foreach($paymentMethods as $method)
                    '{{ $method->name }}',
                @endforeach
            ],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
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
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Orders by Hour',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: Array.from({length: 24}, (_, i) => i + ':00'),
            }
        };
        var timeChart = new ApexCharts(document.querySelector("#time-chart"), timeOptions);
        timeChart.render();
    });
</script>
@endpush
