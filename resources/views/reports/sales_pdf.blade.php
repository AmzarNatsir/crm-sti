<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Date Range: {{ $date_range }}</p>
        <p>Generated on: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl. Invoice</th>
                <th>No. invoice</th>
                <th>Customer</th>
                <th>Metode Pembayaran</th>
                <th>Campaign</th>
                <th>Total Invoice</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->invoice_date->format('d M Y') }}</td>
                    <td>{{ $order->invoice_no }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->paymentMethod->name ?? 'N/A' }}</td>
                    <td>{{ $order->campaign->name ?? 'N/A' }}</td>
                    <td>{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ number_format($order->invoice_discount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align: right;">Total</th>
                <th>{{ number_format($orders->sum('total_amount'), 2) }}</th>
                <th>{{ number_format($orders->sum('invoice_discount'), 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        CRM PTTANI - Sales Report
    </div>
</body>
</html>
