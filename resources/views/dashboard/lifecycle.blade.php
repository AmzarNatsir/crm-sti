@extends('layout.mainlayout')
@section('content')

<style>
    .segment-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .segment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .insight-badge {
        font-size: 1.2rem;
        margin-right: 8px;
    }
    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }
    .metric-label {
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
    }
</style>

<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-4">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Analisis Siklus Hidup Pelanggan</h4>
                <p class="text-muted mb-0">Customer Lifecycle Analysis Dashboard</p>
            </div>
        </div>

        <!-- A. Header KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <p class="metric-label mb-1">Total Customer</p>
                        <h3 class="metric-value text-primary">{{ number_format($totalCustomers) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <p class="metric-label mb-1">Customer Aktif</p>
                        <h3 class="metric-value text-success">{{ number_format($activeCustomers) }}</h3>
                        <small class="text-muted">60 hari terakhir</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <p class="metric-label mb-1">Total Revenue</p>
                        <h3 class="metric-value text-info">{{ number_format($totalRevenue / 1000000, 1) }}M</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <p class="metric-label mb-1">Churn Rate</p>
                        <h3 class="metric-value text-danger">{{ $churnRate }}%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <p class="metric-label mb-1">Avg CLV</p>
                        <h3 class="metric-value text-warning">{{ number_format($avgClv / 1000, 0) }}K</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <p class="metric-label mb-1">Avg Frequency</p>
                        <h3 class="metric-value text-secondary">{{ number_format($avgFrequency, 1) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- B. Lifecycle Overview Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📊 Lifecycle Overview</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-info">
                            <tr>
                                <th class="fw-bold">Segment</th>
                                <th class="fw-bold">% of Customers</th>
                                <th class="fw-bold">Avg. CLV</th>
                                <th class="fw-bold">Avg. Frequency</th>
                                <th class="fw-bold">Churn Risk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-light">
                                <td class="fw-bold">Acquisition</td>
                                <td>{{ $segmentMetrics['Acquisition']['percentage'] }}%</td>
                                <td>IDR {{ number_format($segmentMetrics['Acquisition']['avg_clv'] / 1000, 0) }}K</td>
                                <td>{{ number_format($segmentMetrics['Acquisition']['avg_frequency'], 0) }}x</td>
                                <td>Low</td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-bold">Activation</td>
                                <td>{{ $segmentMetrics['Activation']['percentage'] }}%</td>
                                <td>IDR {{ number_format($segmentMetrics['Activation']['avg_clv'] / 1000, 0) }}K</td>
                                <td>{{ number_format($segmentMetrics['Activation']['avg_frequency'], 0) }}x</td>
                                <td>Medium</td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-bold">Growth</td>
                                <td>{{ $segmentMetrics['Growth']['percentage'] }}%</td>
                                <td>IDR {{ number_format($segmentMetrics['Growth']['avg_clv'] / 1000000, 1) }}M</td>
                                <td>{{ number_format($segmentMetrics['Growth']['avg_frequency'], 0) }}x</td>
                                <td>Low</td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-bold">Loyalty</td>
                                <td>{{ $segmentMetrics['Loyalty']['percentage'] }}%</td>
                                <td>IDR {{ number_format($segmentMetrics['Loyalty']['avg_clv'] / 1000000, 1) }}M</td>
                                <td>{{ number_format($segmentMetrics['Loyalty']['avg_frequency'], 0) }}x</td>
                                <td>Very Low</td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-bold">At Risk/Churn</td>
                                <td>{{ $segmentMetrics['At Risk']['percentage'] }}%</td>
                                <td>IDR {{ number_format($segmentMetrics['At Risk']['avg_clv'] / 1000, 0) }}K</td>
                                <td>{{ number_format($segmentMetrics['At Risk']['avg_frequency'], 0) }}x</td>
                                <td>High</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- INSIGHT Section -->
                <div class="p-4 bg-light border-top">
                    <h6 class="fw-bold mb-3">INSIGHT:</h6>
                    <ul class="mb-0" style="line-height: 1.8;">
                        @php
                            $acquisitionPct = $segmentMetrics['Acquisition']['percentage'];
                            $activationPct = $segmentMetrics['Activation']['percentage'];
                            $growthPct = $segmentMetrics['Growth']['percentage'];
                            $atRiskPct = $segmentMetrics['At Risk']['percentage'];
                            
                            // Calculate conversion rate
                            $conversionRate = $acquisitionPct > 0 ? round(($activationPct / $acquisitionPct) * 100) : 0;
                            $stopRate = 100 - $conversionRate;
                        @endphp
                        
                        <li>Dari <strong>{{ $acquisitionPct }}%</strong> pelanggan yang diakuisisi, hanya <strong>{{ $activationPct }}%</strong> pelanggan yang sampai di tahap aktivasi, artinya <strong>{{ $stopRate }}%</strong> berhenti sebelum melakukan transaksi pertama mereka. ▶ Gunakan insentif orientasi untuk mempercepat pembelian ulang pertama.</li>
                        
                        <li>Pertumbuhan adalah tahap terkuat (<strong>{{ $growthPct }}%</strong>), menunjukkan kecocokan produk/pasar yang kuat setelah diaktifkan.</li>
                        
                        <li>CLV Tinggi di Segmen Loyalty, menunjukkan ada ruang untuk meningkatkan strategi retensi. ▶ Tawarkan produk terbatas/eksklusif.</li>
                        
                        <li><strong>{{ $atRiskPct }}%</strong> Pelanggan Berisiko, prediksi churn dan kampanye mesti dilakukan ▶ Picu kampanye win-back setelah 60 hari tidak aktif.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabs for Different Views -->
        <ul class="nav nav-tabs mb-3" id="lifecycleTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="segments-tab" data-bs-toggle="tab" data-bs-target="#segments" type="button">
                    📈 Per Segmen
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="recommendations-tab" data-bs-toggle="tab" data-bs-target="#recommendations" type="button">
                    💡 Rekomendasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button">
                    👥 Versi Sales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button">
                    📋 Decision Board
                </button>
            </li>
        </ul>

        <div class="tab-content" id="lifecycleTabContent">
            <!-- C. Per-Segment Panels -->
            <div class="tab-pane fade show active" id="segments" role="tabpanel">
                <div class="row g-3">
                    @foreach(['Acquisition' => 'primary', 'Activation' => 'info', 'Growth' => 'success', 'Loyalty' => 'warning', 'At Risk' => 'danger'] as $segment => $color)
                    <div class="col-md-6">
                        <div class="card segment-card border-{{ $color }} shadow-sm">
                            <div class="card-header bg-{{ $color }} text-white">
                                <h5 class="mb-0">{{ $segment }}</h5>
                                <small>{{ $segmentMetrics[$segment]['count'] }} customers ({{ $segmentMetrics[$segment]['percentage'] }}%)</small>
                            </div>
                            <div class="card-body">
                                <h6 class="text-muted">💡 Insight Utama</h6>
                                <p class="mb-3">
                                    @if($segment == 'Acquisition')
                                        Customer baru yang baru melakukan 1x pembelian. Fokus pada konversi ke repeat customer.
                                    @elseif($segment == 'Activation')
                                        Customer yang sudah 2x beli. Momentum penting untuk membangun kebiasaan.
                                    @elseif($segment == 'Growth')
                                        Customer aktif dengan potensi tinggi. Siap untuk upselling dan cross-selling.
                                    @elseif($segment == 'Loyalty')
                                        Customer setia dengan frekuensi tinggi. Aset berharga untuk referral.
                                    @else
                                        Customer yang tidak aktif >60 hari. Butuh win-back campaign segera.
                                    @endif
                                </p>

                                <h6 class="text-muted">⚠️ Masalah Terbesar</h6>
                                <p class="mb-3">
                                    @if($segment == 'Acquisition')
                                        Risiko tidak repeat purchase (60% churn risk)
                                    @elseif($segment == 'Activation')
                                        Belum terbentuk habit pembelian rutin
                                    @elseif($segment == 'Growth')
                                        Potensi plateau jika tidak di-nurture
                                    @elseif($segment == 'Loyalty')
                                        Kompetitor bisa menarik dengan offer lebih baik
                                    @else
                                        Sudah tidak engage, sulit untuk win-back
                                    @endif
                                </p>

                                <h6 class="text-muted">🎯 Peluang Bisnis</h6>
                                <p class="mb-3">
                                    @if($segment == 'Acquisition')
                                        Onboarding yang baik = repeat customer
                                    @elseif($segment == 'Activation')
                                        Loyalty program introduction
                                    @elseif($segment == 'Growth')
                                        Upsell premium products, volume deals
                                    @elseif($segment == 'Loyalty')
                                        Referral program, brand ambassadors
                                    @else
                                        Re-engagement dengan special offers
                                    @endif
                                </p>

                                <h6 class="text-muted">🛍️ Produk Dominan</h6>
                                @if($segmentMetrics[$segment]['count'] > 0)
                                    @php
                                        $allProducts = [];
                                        foreach($segmentMetrics[$segment]['customers'] as $cust) {
                                            foreach($cust['top_products'] as $prod => $qty) {
                                                if(!isset($allProducts[$prod])) $allProducts[$prod] = 0;
                                                $allProducts[$prod] += $qty;
                                            }
                                        }
                                        arsort($allProducts);
                                        $topProducts = array_slice($allProducts, 0, 3, true);
                                    @endphp
                                    <ul class="mb-0">
                                        @foreach($topProducts as $product => $qty)
                                        <li>{{ $product }} ({{ $qty }} unit)</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Tidak ada data</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- E. Action Recommendations -->
            <div class="tab-pane fade" id="recommendations" role="tabpanel">
                <div class="row g-3">
                    @foreach(['Acquisition', 'Activation', 'Growth', 'Loyalty', 'At Risk'] as $segment)
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">{{ $segment }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6 class="text-primary">📢 Marketing</h6>
                                        <p>{{ $recommendations[$segment]['marketing'] }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-success">💼 Sales</h6>
                                        <p>{{ $recommendations[$segment]['sales'] }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-warning">📦 Produk</h6>
                                        <p>{{ $recommendations[$segment]['product'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- F. Sales View -->
            <div class="tab-pane fade" id="sales" role="tabpanel">
                <div class="row g-3">
                    <!-- At Risk Customers -->
                    <div class="col-md-4">
                        <div class="card border-danger shadow-sm">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">🚨 At Risk - Butuh Retensi</h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @foreach($segmentMetrics['At Risk']['customers'] as $cust)
                                <div class="mb-3 pb-2 border-bottom">
                                    <strong>{{ $cust['customer']->name }}</strong><br>
                                    <small class="text-muted">
                                        Last order: {{ $cust['last_order_days'] }} hari lalu<br>
                                        CLV: Rp {{ number_format($cust['clv'], 0, ',', '.') }}<br>
                                        Phone: {{ $cust['customer']->phone }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Growth Customers -->
                    <div class="col-md-4">
                        <div class="card border-success shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">📈 Growth - Siap Upsell</h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @foreach($segmentMetrics['Growth']['customers'] as $cust)
                                <div class="mb-3 pb-2 border-bottom">
                                    <strong>{{ $cust['customer']->name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $cust['order_count'] }}x pembelian<br>
                                        CLV: Rp {{ number_format($cust['clv'], 0, ',', '.') }}<br>
                                        Phone: {{ $cust['customer']->phone }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Loyalty Customers -->
                    <div class="col-md-4">
                        <div class="card border-warning shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">⭐ Loyalty - Referral Ready</h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @foreach($segmentMetrics['Loyalty']['customers'] as $cust)
                                <div class="mb-3 pb-2 border-bottom">
                                    <strong>{{ $cust['customer']->name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $cust['order_count'] }}x pembelian<br>
                                        CLV: Rp {{ number_format($cust['clv'], 0, ',', '.') }}<br>
                                        Phone: {{ $cust['customer']->phone }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- G. Management Decision Board -->
            <div class="tab-pane fade" id="management" role="tabpanel">
                <!-- Funnel Visualization -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">🔄 Lifecycle Funnel</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @foreach(['Acquisition', 'Activation', 'Growth', 'Loyalty'] as $idx => $segment)
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <h3 class="text-primary">{{ $segmentMetrics[$segment]['count'] }}</h3>
                                    <p class="mb-0">{{ $segment }}</p>
                                    <small class="text-muted">{{ $segmentMetrics[$segment]['percentage'] }}%</small>
                                </div>
                                @if($idx < 3)
                                <i class="ti ti-arrow-right" style="font-size: 2rem;"></i>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Revenue by Segment -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">💰 Revenue per Segmen</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Segmen</th>
                                        <th>Total Revenue</th>
                                        <th>% dari Total</th>
                                        <th>Avg CLV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['Acquisition', 'Activation', 'Growth', 'Loyalty', 'At Risk'] as $segment)
                                    <tr>
                                        <td><strong>{{ $segment }}</strong></td>
                                        <td>Rp {{ number_format($segmentMetrics[$segment]['total_revenue'], 0, ',', '.') }}</td>
                                        <td>{{ $totalRevenue > 0 ? round(($segmentMetrics[$segment]['total_revenue'] / $totalRevenue) * 100, 1) : 0 }}%</td>
                                        <td>Rp {{ number_format($segmentMetrics[$segment]['avg_clv'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Decision Table -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">⚡ Decision Board</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Isu</th>
                                        <th>Dampak</th>
                                        <th>Rekomendasi</th>
                                        <th>Prioritas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($segmentMetrics['At Risk']['percentage'] > 30)
                                    <tr>
                                        <td>Churn Rate Tinggi ({{ $churnRate }}%)</td>
                                        <td class="text-danger">CRITICAL - Kehilangan revenue potensial</td>
                                        <td>Launch win-back campaign segera dengan special offers</td>
                                        <td><span class="badge bg-danger">URGENT</span></td>
                                    </tr>
                                    @endif

                                    @if($segmentMetrics['Acquisition']['count'] > $segmentMetrics['Activation']['count'] * 2)
                                    <tr>
                                        <td>Drop Acquisition → Activation</td>
                                        <td class="text-warning">HIGH - Konversi rendah</td>
                                        <td>Perbaiki onboarding flow, welcome series, first purchase incentive</td>
                                        <td><span class="badge bg-warning">HIGH</span></td>
                                    </tr>
                                    @endif

                                    @if($segmentMetrics['Growth']['count'] > 0)
                                    <tr>
                                        <td>Peluang Upselling di Growth</td>
                                        <td class="text-success">OPPORTUNITY - Revenue growth potential</td>
                                        <td>Targeted upsell campaign, premium product introduction</td>
                                        <td><span class="badge bg-success">MEDIUM</span></td>
                                    </tr>
                                    @endif

                                    @if($segmentMetrics['Loyalty']['count'] > 10)
                                    <tr>
                                        <td>Loyalty Base Kuat</td>
                                        <td class="text-info">POSITIVE - Referral potential</td>
                                        <td>Launch referral program, brand ambassador initiative</td>
                                        <td><span class="badge bg-info">MEDIUM</span></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.footer')
    @endcomponent
</div>

@endsection
