<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SalesDeliveryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function collection()
    {
        return $this->schedules;
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Customer',
            'Delivery Date',
            'Arrival Date',
            'Personnel',
            'Status'
        ];
    }

    public function map($s): array
    {
        return [
            $s->order->invoice_no ?? 'N/A',
            $s->order->customer->name ?? 'N/A',
            Carbon::parse($s->delivery_date)->format('Y-m-d'),
            $s->arrival_date ? Carbon::parse($s->arrival_date)->format('Y-m-d') : '-',
            $s->employee->name ?? 'N/A',
            ucfirst($s->status)
        ];
    }
}
