<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $orders;
    protected $startDate;
    protected $endDate;
    private $rowCount = 0;

    public function __construct($orders, $startDate = null, $endDate = null)
    {
        $this->orders = $orders;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        $periode = 'All Time';
        if ($this->startDate && $this->endDate) {
            $periode = $this->startDate . ' - ' . $this->endDate;
        } elseif ($this->startDate) {
            $periode = 'Since ' . $this->startDate;
        } elseif ($this->endDate) {
            $periode = 'Until ' . $this->endDate;
        }

        return [
            ['Report', ': Sales'],
            ['Periode', ': ' . $periode],
            [''], // Empty row
            [
                'No',
                'Tgl. Invoice',
                'No. invoice',
                'Customer',
                'Metode Pembayaran',
                'Compaign',
                'Total Invoice',
                'Diskon'
            ]
        ];
    }

    public function map($order): array
    {
        $this->rowCount++;
        return [
            $this->rowCount,
            $order->invoice_date->format('Y-m-d'),
            $order->invoice_no,
            $order->customer->name ?? 'N/A',
            $order->paymentMethod->name ?? 'N/A',
            $order->campaign->name ?? 'N/A',
            (float) $order->total_amount,
            (float) $order->invoice_discount
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 20,
            'G' => 15,
            'H' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header rows
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
            4    => ['font' => ['bold' => true]],
        ];
    }
}
