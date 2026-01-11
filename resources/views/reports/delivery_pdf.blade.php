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
        <p>Delivery Staff: {{ $employee_name }}</p>
        <p>Generated on: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Customer</th>
                <th>Delivery Date</th>
                <th>Arrival Date</th>
                <th>Personnel</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $s)
                <tr>
                    <td>{{ $s->order->invoice_no ?? 'N/A' }}</td>
                    <td>{{ $s->order->customer->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->delivery_date)->format('d M Y') }}</td>
                    <td>{{ $s->arrival_date ? \Carbon\Carbon::parse($s->arrival_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $s->employee->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($s->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        CRM PTTANI - Delivery Report
    </div>
</body>
</html>
