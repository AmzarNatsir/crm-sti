<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class CustomerTemplateExport implements WithHeadings, WithColumnWidths, WithStyles
{
    public function headings(): array
    {
        return [
            [
                'CUSTOMER IMPORT TEMPLATE',
            ],
            [
                'Instructions: Fill in the data below. Fields marked with * are mandatory.',
            ],
            [], // Empty row
            [
                'type',
                'commodity_id',
                'name *',
                'identity_no *',
                'date_of_birth',
                'company_name',
                'phone *',
                'email',
                'address *',
                'village',
                'village_code',
                'sub_district',
                'sub_district_code',
                'district',
                'district_code',
                'province',
                'province_code',
                'point_coordinate',
                'industry',
                'source'
            ],
            [
                'customer',
                '1',
                'John Doe',
                '3201234567890123',
                '1990-01-15',
                'PT Example Corp',
                '081234567890',
                'john.doe@example.com',
                'Jl. Example No. 123',
                'Kelurahan Example',
                '3201011001',
                'Kecamatan Example',
                '3201011',
                'Kabupaten Example',
                '3201',
                'Jawa Barat',
                '32',
                '-6.200000,106.816666',
                'Technology',
                'Website'
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // type
            'B' => 12,  // commodity_id
            'C' => 20,  // name
            'D' => 18,  // identity_no
            'E' => 15,  // date_of_birth
            'F' => 20,  // company_name
            'G' => 15,  // phone
            'H' => 25,  // email
            'I' => 30,  // address
            'J' => 18,  // village
            'K' => 15,  // village_code
            'L' => 18,  // sub_district
            'M' => 15,  // sub_district_code
            'N' => 18,  // district
            'O' => 15,  // district_code
            'P' => 15,  // province
            'Q' => 15,  // province_code
            'R' => 20,  // point_coordinate
            'S' => 15,  // industry
            'T' => 15,  // source
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the title row
        $sheet->mergeCells('A1:T1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Style the instruction row
        $sheet->mergeCells('A2:T2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF3F4F6');
        
        // Style the header row (row 4)
        $sheet->getStyle('A4:T4')->getFont()->setBold(true);
        $sheet->getStyle('A4:T4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF3B82F6');
        $sheet->getStyle('A4:T4')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        
        // Style the sample data row
        $sheet->getStyle('A5:T5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDBEAFE');
        
        // Enable text wrapping for all cells
        $sheet->getStyle('A1:T1000')->getAlignment()->setWrapText(true);
        
        // Freeze the header row
        $sheet->freezePane('A5');
        
        return [];
    }
}
