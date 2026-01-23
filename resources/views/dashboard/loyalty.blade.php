@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Customer Loyalty Dashboard (RFM)</h4>
                <p class="text-muted mb-0">Segmentation based on Recency, Frequency, & Monetary Scores</p>
            </div>
            <div>
                <button class="btn btn-secondary btn-sm" onclick="filterCategory('')">Reset Filter</button>
            </div>
        </div>

        <!-- Custom Styles for Summary Cards -->
        <style>
            .summary-card {
                position: relative;
                overflow: hidden;
                border: none;
                border-radius: 12px;
                background-color: #fff; /* Ensure white background */
                transition: transform 0.2s;
            }
            .summary-card:hover {
                transform: translateY(-5px);
            }
            .summary-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 60px;
                height: 60px;
                border-top-left-radius: 12px; /* Match card radius */
                background: inherit; /* Fallback */
            }
            /* Corner Accents */
            .card-accent-success::before {
                background: linear-gradient(135deg, #28a745 50%, transparent 50%);
            }
            .card-accent-info::before {
                background: linear-gradient(135deg, #17a2b8 50%, transparent 50%);
            }
            .card-accent-warning::before {
                background: linear-gradient(135deg, #ffc107 50%, transparent 50%);
            }
            .card-accent-primary::before {
                background: linear-gradient(135deg, #007bff 50%, transparent 50%);
            }
            .card-accent-danger::before {
                background: linear-gradient(135deg, #dc3545 50%, transparent 50%);
            }
            
            .icon-circle {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
            }
            .icon-circle.success { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
            .icon-circle.info { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }
            .icon-circle.warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
            .icon-circle.primary { background-color: rgba(0, 123, 255, 0.1); color: #007bff; }
            .icon-circle.danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        </style>

        <!-- Metric Summary -->
        <div class="row">
            <!-- Champions -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm summary-card card-accent-success h-100" style="cursor: pointer" onclick="filterCategory('Champions')">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-muted fw-medium">Champions</span>
                            <h2 class="mb-0 fw-bold mt-2">{{ collect($results)->where('category', 'Champions')->count() }}</h2>
                            <small class="text-success"><i class="ti ti-arrow-up"></i> High Value</small>
                        </div>
                        <div class="icon-circle success">
                            <i class="ti ti-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loyal -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm summary-card card-accent-info h-100" style="cursor: pointer" onclick="filterCategory('Loyal')">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-muted fw-medium">Loyal</span>
                            <h2 class="mb-0 fw-bold mt-2">{{ collect($results)->where('category', 'Loyal')->count() }}</h2>
                            <small class="text-info"><i class="ti ti-activity"></i> Active</small>
                        </div>
                        <div class="icon-circle info">
                            <i class="ti ti-thumb-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Big Spenders -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm summary-card card-accent-warning h-100" style="cursor: pointer" onclick="filterCategory('Big Spenders')">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-muted fw-medium">Big Spenders</span>
                            <h2 class="mb-0 fw-bold mt-2">{{ collect($results)->where('category', 'Big Spenders')->count() }}</h2>
                            <small class="text-warning"><i class="ti ti-coin"></i> High Revenue</small>
                        </div>
                        <div class="icon-circle warning">
                            <i class="ti ti-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Potential Loyal -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm summary-card card-accent-primary h-100" style="cursor: pointer" onclick="filterCategory('Potential Loyal')">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-muted fw-medium">Potential Loyal</span>
                            <h2 class="mb-0 fw-bold mt-2">{{ collect($results)->where('category', 'Potential Loyal')->count() }}</h2>
                            <small class="text-primary"><i class="ti ti-trending-up"></i> Growing</small>
                        </div>
                        <div class="icon-circle primary">
                            <i class="ti ti-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promising -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm summary-card card-accent-danger h-100" style="cursor: pointer" onclick="filterCategory('Promising')">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-muted fw-medium">Promising</span>
                            <h2 class="mb-0 fw-bold mt-2">{{ collect($results)->where('category', 'Promising')->count() }}</h2>
                            <small class="text-danger"><i class="ti ti-alert-circle"></i> Needs Attention</small>
                        </div>
                        <div class="icon-circle danger">
                            <i class="ti ti-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- At Risk -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm summary-card card-accent-danger h-100" style="cursor: pointer" onclick="filterCategory('At Risk')">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-muted fw-medium">At Risk</span>
                            <h2 class="mb-0 fw-bold mt-2">{{ collect($results)->where('category', 'At Risk')->count() }}</h2>
                            <small class="text-danger"><i class="ti ti-arrow-down"></i> Low Engagement</small>
                        </div>
                        <div class="icon-circle danger">
                            <i class="ti ti-alert-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Customer Segmentation Data</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="loyaltyTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Customer_ID</th>
                                <th>Komoditas</th>
                                <th>Last_Purchase_Date</th>
                                <th>Orders_12mo</th>
                                <th>Spend_12mo</th>
                                <th>Recency_Days</th>
                                <th>Frequency</th>
                                <th>Monetary</th>
                                <th>R_Score</th>
                                <th>F_Score</th>
                                <th>M_Score</th>
                                <th>RFM_Code</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $row)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $row->customer_id }}</span>
                                    <br><small class="text-muted">{{ $row->customer_name }}</small>
                                </td>
                                <td>{{ $row->komoditas }}</td>
                                <td>{{ $row->last_purchase_date }}</td>
                                <td>{{ $row->orders_12mo }}</td>
                                <td>{{ number_format($row->spend_12mo, 0, ',', '.') }}</td>
                                <td>{{ $row->recency_days }}</td>
                                <td>{{ $row->frequency }}</td>
                                <td>{{ number_format($row->monetary, 0, ',', '.') }}</td>
                                <td>{{ $row->r_score }}</td>
                                <td>{{ $row->f_score }}</td>
                                <td>{{ $row->m_score }}</td>
                                <td>{{ $row->rfm_code }}</td>
                                <td>
                                    @if($row->category == 'Champions')
                                        <span class="badge bg-success">Champions</span>
                                    @elseif($row->category == 'Loyal')
                                        <span class="badge bg-info">Loyal</span>
                                    @elseif($row->category == 'Big Spenders')
                                        <span class="badge bg-warning">Big Spenders</span>
                                    @elseif($row->category == 'Potential Loyal')
                                        <span class="badge bg-primary">Potential Loyal</span>
                                    @elseif($row->category == 'Promising')
                                        <span class="badge bg-secondary">Promising</span>
                                    @else
                                        <span class="badge bg-danger">At Risk</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RFM Reference Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">RFM Analysis Reference</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped custom-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-white">Segment</th>
                                <th class="text-white">R Score (Recency)</th>
                                <th class="text-white">F Score (Frequency)</th>
                                <th class="text-white">M Score (Monetary)</th>
                                <th class="text-white">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-success">Champions</td>
                                <td>>= 3</td>
                                <td>>= 3</td>
                                <td>>= 3</td>
                                <td>Pelanggan paling berharga: pembelanja baru, sering, dan pembelanja tinggi</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-info">Loyal</td>
                                <td>>= 3</td>
                                <td>>= 3</td>
                                <td>< 3 or any</td>
                                <td>Pelanggan yang sering aktif dan dapat diandalkan</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-warning">Big Spenders</td>
                                <td>>= 3</td>
                                <td>any</td>
                                <td>>= 3</td>
                                <td>Pembeli yang aktif baru-baru ini, bernilai tinggi — mungkin tidak sering membeli</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Potential Loyal</td>
                                <td>< 3</td>
                                <td>>= 3 or</td>
                                <td>>= 3</td>
                                <td>Tidak baru-baru ini, tetapi sering digunakan untuk berbelanja atau membeli — dapat diaktifkan kembali</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-secondary">Promising</td>
                                <td>>= 3</td>
                                <td>< 3</td>
                                <td>< 3</td>
                                <td>Baru saja diakuisisi — tahap awal loyalitas, peliharalah mereka</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-danger">At Risk</td>
                                <td>< 3</td>
                                <td>< 3</td>
                                <td>< 3</td>
                                <td>Dingin, tidak aktif, bernilai rendah — pertimbangkan strategi menang-kembali atau keluar</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function filterCategory(category) {
        // Assuming current layout uses DataTables, we access the instance
        // If datatable is initialized automatically via class .datatable
        var table = $('.datatable').DataTable();
         
        // Search in column index 12 (Category)
        // If category is empty, we search for empty string to reset (or regex for all)
        if (category) {
            // Using regex false, smart true. 
            // Precise match might be needed if "Loyal" matches "Very Loyal", so use regex borders if needed, 
            // but "Very Loyal" vs "Loyal" -> Searching "Loyal" might match both.
            // Let's use exact match regex
             var regex = '^' + category + '$';
            table.column(12).search(regex, true, false).draw();
        } else {
             table.column(12).search('').draw();
        }
    }
</script>
@endsection
