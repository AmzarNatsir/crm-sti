<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerVisitRankingExport implements FromArray, WithColumnWidths, WithStyles
{
    protected Collection $customers;
    protected array $boldRows = [];
    protected array $visitLabelRows = [];
    protected array $subHeaderRows = [];

    public function __construct(Collection $customers)
    {
        $this->customers = $customers;
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['No', 'Customer', 'Type', 'Total Visit', 'Last Visit'];
        $this->boldRows[] = count($rows);

        $no = 1;

        foreach ($this->customers as $customer) {
            $visits = $customer->surveys->sortBy('created_at')->values();
            $lastVisit = optional($visits->last())->created_at;

            $rows[] = [
                $no++,
                $customer->name,
                ucfirst($customer->type),
                $visits->count(),
                $lastVisit ? $lastVisit->format('d M Y H:i') : '-',
            ];
            $this->boldRows[] = count($rows);

            foreach ($visits as $index => $survey) {
                $rows[] = ['Visit ' . ($index + 1) . ' (' . optional($survey->created_at)->format('d M Y H:i') . ')'];
                $this->visitLabelRows[] = count($rows);

                $penutup = $survey->penutup;

                $rows[] = ['Komitmen Tindak Lanjut', 'Oleh Siapa', 'Kapan'];
                $this->subHeaderRows[] = count($rows);
                $rows[] = [
                    $penutup->komitmenTindakLanjut_Apa ?? '-',
                    $penutup->komitmenTindakLanjut_OlehSiapa ?? '-',
                    $penutup ? $this->formatDateTime($penutup->komitmenTindakLanjut_KapanTanggal, $penutup->komitmenTindakLanjut_KapanJam) : '-',
                ];

                $rows[] = ['Jadwal Followup', 'Kanal Followup'];
                $this->subHeaderRows[] = count($rows);
                $rows[] = [
                    $penutup ? $this->formatDateTime($penutup->jadwalFollowup_Tanggal, $penutup->jadwalFollowup_Jam) : '-',
                    $penutup->jadwalFollowup_Kanal ?? '-',
                ];
            }

            $rows[] = [null];
        }

        return $rows;
    }

    protected function formatDateTime(?string $date, ?string $time): string
    {
        $formattedDate = $date ? Carbon::parse($date)->format('d M Y') : '';
        $parts = array_filter([$formattedDate, $time]);

        return $parts ? implode(' ', $parts) : '-';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [];

        foreach ($this->boldRows as $row) {
            $styles[$row] = ['font' => ['bold' => true]];
        }

        foreach ($this->visitLabelRows as $row) {
            $styles[$row] = ['font' => ['italic' => true]];
        }

        foreach ($this->subHeaderRows as $row) {
            $styles[$row] = ['font' => ['bold' => true, 'size' => 9]];
        }

        return $styles;
    }
}
