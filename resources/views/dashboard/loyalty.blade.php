@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Customer Loyalty Dashboard (RFM)</h4>
                <p class="text-muted mb-0">Segmentation based on Recency, Frequency, Monetary & Loyalty Score</p>
            </div>
            <!-- <div>
                 <a href="#" class="btn btn-primary" onclick="window.print()"><i class="ti ti-printer me-2"></i>Print Report</a>
            </div> -->
            <div>
                <button class="btn btn-secondary btn-sm" onclick="filterCategory('')">Reset Filter</button>
            </div>
        </div>

        <!-- Metric Summary -->
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm border-left-Success" style="cursor: pointer" onclick="filterCategory('Very Loyal')">
                    <div class="card-body">
                        <h6 class="text-muted">Very Loyal (>85)</h6>
                        <h3 class="fw-bold text-success">{{ collect($results)->where('category', 'Very Loyal')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-left-info" style="cursor: pointer" onclick="filterCategory('Loyal')">
                    <div class="card-body">
                        <h6 class="text-muted">Loyal (70-84)</h6>
                        <h3 class="fw-bold text-info">{{ collect($results)->where('category', 'Loyal')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-left-warning" style="cursor: pointer" onclick="filterCategory('Churn Risk')">
                    <div class="card-body">
                        <h6 class="text-muted">Churn Risk (30-49)</h6>
                        <h3 class="fw-bold text-warning">{{ collect($results)->where('category', 'Churn Risk')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-left-danger" style="cursor: pointer" onclick="filterCategory('Almost Lost')">
                    <div class="card-body">
                        <h6 class="text-muted">Almost Lost (<30)</h6>
                        <h3 class="fw-bold text-danger">{{ collect($results)->where('category', 'Almost Lost')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Customer Segmentation Analysis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="loyaltyTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Customer</th>
                                <th title="Recency (Days)">R (Days)</th>
                                <th title="Frequency (Count)">F (Count)</th>
                                <th title="Monetary (Total)">M (Rp)</th>
                                <th>RFM Code</th>
                                <th>RFM Score</th>
                                <th>Loyalty Score</th>
                                <th>Category</th>
                                <th>Recommended Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $row)
                            <tr>
                                <td>
                                    <strong>{{ $row->customer->name }}</strong><br>
                                    <small class="text-muted">{{ $row->customer->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    {{ $row->recency_days }} days
                                    <span class="badge bg-light text-dark border ms-1">{{ $row->r_score }}</span>
                                </td>
                                <td>
                                    {{ $row->frequency }}x
                                    <span class="badge bg-light text-dark border ms-1">{{ $row->f_score }}</span>
                                </td>
                                <td>
                                    {{ number_format($row->monetary, 0, ',', '.') }}
                                    <span class="badge bg-light text-dark border ms-1">{{ $row->m_score }}</span>
                                </td>
                                <td><span class="badge bg-secondary">{{ $row->rfm_code }}</span></td>
                                <td>{{ number_format($row->rfm_score_100, 1) }}</td>
                                <td class="fw-bold">{{ number_format($row->loyalty_score, 1) }}</td>
                                <td>
                                    @if($row->category == 'Very Loyal')
                                        <span class="badge bg-success">Very Loyal</span>
                                    @elseif($row->category == 'Loyal')
                                        <span class="badge bg-info">Loyal</span>
                                    @elseif($row->category == 'Moderate')
                                        <span class="badge bg-secondary">Moderate</span>
                                    @elseif($row->category == 'Churn Risk')
                                        <span class="badge bg-warning">Churn Risk</span>
                                    @else
                                        <span class="badge bg-danger">Almost Lost</span>
                                    @endif
                                </td>
                                <td class="text-sm text-wrap" style="max-width: 250px;">
                                    {{ $row->action }}
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

<script>
    function filterCategory(category) {
        // Assuming current layout uses DataTables, we access the instance
        // If datatable is initialized automatically via class .datatable
        var table = $('.datatable').DataTable();
         
        // Search in column index 7 (Category)
        // If category is empty, we search for empty string to reset (or regex for all)
        if (category) {
            // Using regex false, smart true. 
            // Precise match might be needed if "Loyal" matches "Very Loyal", so use regex borders if needed, 
            // but "Very Loyal" vs "Loyal" -> Searching "Loyal" might match both.
            // Let's use exact match regex
             var regex = '^' + category + '$';
            table.column(7).search(regex, true, false).draw();
        } else {
             table.column(7).search('').draw();
        }
    }
</script>
@endsection
