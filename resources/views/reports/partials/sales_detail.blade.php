<!-- Header -->
<div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
    <img src="{{URL::asset('build/img/logo_app.svg')}}" class="invoice-light-logo" width="150" alt="logo">
    <span class="badge bg-info-subtle text-info-emphasis fs-14"> #{{ $order->invoice_no }}</span>
</div>

<!-- Details -->
<div class="row pb-3 border-bottom mb-4">
    <div class="col-lg-6">
        <h5 class="mb-2 fs-16 fw-bold"> Invoice Information </h5>
        <table class="table table-borderless table-sm">
            <tr>
                <td style="width: 140px;">Invoice No</td>
                <td>: <span class="text-dark fw-medium">{{ $order->invoice_no }}</span></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>: <span class="text-dark fw-medium">{{ $order->invoice_date->format('d M Y') }}</span></td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>: <span class="text-dark fw-medium">{{ $order->paymentMethod->name ?? 'N/A' }}</span></td>
            </tr>
            @if($order->compaign_id)
            <tr>
                <td>Campaign</td>
                <td>: <span class="text-dark fw-medium">{{ $order->campaign->name ?? 'N/A' }}</span></td>
            </tr>
            @endif
            <tr>
                <td>Status</td>
                <td>: <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">{{ strtoupper($order->payment_status) }}</span></td>
            </tr>
        </table>
    </div>
    <div class="col-lg-6 text-lg-end">
        <h5 class="mb-2 fs-16 fw-bold"> Bill To </h5>
        <p class="text-dark fw-bold mb-1"> {{ $order->customer->name ?? 'N/A' }} </p>
        <p class="text-body mb-1"> {{ $order->customer->company_name ?? '' }} </p>
        <p class="text-body mb-0"> {{ $order->customer->email ?? '' }} </p>
    </div>
</div>

<!-- Items -->
<div class="mb-4">
    <h6 class="mb-3 fs-16 fw-bold"> Order Items </h6>
    <div class="table-responsive">
        <table class="table table-nowrap border">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Product</th>
                    <th class="text-center">Type</th>
                    <th class="text-end">Price</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                    <td class="text-center">{{ $item->price_type ?? '-' }}</td>
                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Summary -->
<div class="row pb-3 mb-3 border-bottom">
    <div class="col-lg-7">
    </div>
    <div class="col-lg-5">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fs-14 fw-medium text-body">Subtotal</h6>
            <h6 class="fs-14 fw-semibold text-dark">{{ number_format($order->total_amount + $order->invoice_discount, 0, ',', '.') }}</h6>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
            <h6 class="fs-14 fw-medium text-body">Discount</h6>
            <h6 class="fs-14 fw-semibold text-danger">-{{ number_format($order->invoice_discount, 0, ',', '.') }}</h6>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="fs-18 fw-bold">Total</h6>
            <h6 class="fs-18 fw-bold text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}</h6>
        </div>
    </div>
</div>
