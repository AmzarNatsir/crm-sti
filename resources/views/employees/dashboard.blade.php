@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Employee Dashboard</h4>
                <p class="text-muted mb-0">Workforce Analytics & Demographic Summary</p>
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
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div>
                            <p class="mb-1 text-muted">Total Employees</p>
                            <h4 class="fw-bold">{{ number_format($totalEmployees) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Additional KPI placeholders if needed -->
        </div>

        <div class="row">
            <!-- Age Distribution (Bar) -->
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Age Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="age-chart"></div>
                    </div>
                </div>
            </div>

            <!-- Gender Distribution (Donut) -->
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Gender Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="gender-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Length of Service (Bar Horizontal) -->
            <div class="col-xl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Length of Service</h5>
                    </div>
                    <div class="card-body">
                        <div id="service-chart"></div>
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
        // 1. Age Distribution Chart
        var ageOptions = {
            series: [{
                name: 'Employees',
                data: [
                    @foreach($ageDistribution as $count)
                        {{ $count }},
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
                    columnWidth: '50%'
                }
            },
            xaxis: {
                categories: [
                    @foreach(array_keys($ageDistribution) as $label)
                        '{{ $label }}',
                    @endforeach
                ],
            },
            colors: ['#008FFB']
        };
        new ApexCharts(document.querySelector("#age-chart"), ageOptions).render();

        // 2. Gender Distribution Chart
        var genderOptions = {
            series: [
                {{ $genderDistribution['male'] ?? 0 }},
                {{ $genderDistribution['female'] ?? 0 }}
            ],
            labels: ['Male', 'Female'],
            chart: {
                type: 'donut',
                height: 350
            },
            legend: {
                position: 'bottom'
            },
            colors: ['#4C66E4', '#FF4560']
        };
        new ApexCharts(document.querySelector("#gender-chart"), genderOptions).render();

        // 3. Length of Service Chart
        var serviceOptions = {
            series: [{
                name: 'Employees',
                data: [
                    @foreach($serviceLength as $count)
                        {{ $count }},
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
                    @foreach(array_keys($serviceLength) as $label)
                        '{{ $label }}',
                    @endforeach
                ],
            },
            colors: ['#00E396']
        };
        new ApexCharts(document.querySelector("#service-chart"), serviceOptions).render();
    });
</script>
@endpush
