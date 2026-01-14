<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\RefCommodity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CustomerImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $errors = [];
    protected $validRows = [];
    protected $invalidRows = [];
    protected $processedCount = 0;
    protected $totalRows = 0;

    public function collection(Collection $rows)
    {
        $this->totalRows = $rows->count();
        $rowNumber = $this->headingRow() + 1; // Start after heading row

        foreach ($rows as $index => $row) {
            $rowNumber++;
            
            // Skip empty rows
            if ($this->isEmptyRow($row)) {
                continue;
            }

            // Validate the row
            $validationResult = $this->validateRow($row, $rowNumber);
            
            if ($validationResult['valid']) {
                $this->validRows[] = [
                    'row_number' => $rowNumber,
                    'data' => $row->toArray()
                ];
            } else {
                $this->invalidRows[] = [
                    'row_number' => $rowNumber,
                    'data' => $row->toArray(),
                    'errors' => $validationResult['errors']
                ];
            }
        }
    }

    public function import()
    {
        $imported = 0;
        $failed = 0;

        foreach ($this->validRows as $validRow) {
            try {
                $data = $this->prepareData($validRow['data']);
                Customer::create($data);
                $imported++;
                $this->processedCount++;
            } catch (\Exception $e) {
                $failed++;
                $this->errors[] = [
                    'row' => $validRow['row_number'],
                    'message' => $e->getMessage()
                ];
            }
        }

        return [
            'imported' => $imported,
            'failed' => $failed,
            'errors' => $this->errors
        ];
    }

    protected function validateRow($row, $rowNumber)
    {
        $errors = [];
        
        // Check mandatory fields
        if (empty($row['name'])) {
            $errors[] = 'Name is required';
        }
        
        if (empty($row['identity_no'])) {
            $errors[] = 'Identity No is required';
        } else {
            // Check for duplicate identity_no in database
            $exists = Customer::where('identity_no', $row['identity_no'])->exists();
            if ($exists) {
                $errors[] = 'Identity No already exists in database';
            }
        }
        
        if (empty($row['phone'])) {
            $errors[] = 'Phone is required';
        }
        
        if (empty($row['address'])) {
            $errors[] = 'Address is required';
        }

        // Validate email format if provided
        if (!empty($row['email'])) {
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }
        }

        // Validate date_of_birth format if provided
        if (!empty($row['date_of_birth'])) {
            try {
                $date = \Carbon\Carbon::parse($row['date_of_birth']);
            } catch (\Exception $e) {
                $errors[] = 'Invalid date format for date_of_birth (use YYYY-MM-DD)';
            }
        }

        // Validate commodity_id if provided
        if (!empty($row['commodity_id'])) {
            $commodityExists = RefCommodity::where('id', $row['commodity_id'])->exists();
            if (!$commodityExists) {
                $errors[] = 'Commodity ID does not exist';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    protected function prepareData($row)
    {
        return [
            'uid' => uniqid(),
            'type' => !empty($row['type']) ? $row['type'] : 'customer',
            'commodity_id' => !empty($row['commodity_id']) ? $row['commodity_id'] : null,
            'name' => $row['name'],
            'identity_no' => $row['identity_no'],
            'date_of_birth' => !empty($row['date_of_birth']) ? \Carbon\Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
            'company_name' => $row['company_name'] ?? null,
            'phone' => $row['phone'],
            'email' => $row['email'] ?? null,
            'address' => $row['address'],
            'village' => $row['village'] ?? null,
            'village_code' => $row['village_code'] ?? null,
            'sub_district' => $row['sub_district'] ?? null,
            'sub_district_code' => $row['sub_district_code'] ?? null,
            'district' => $row['district'] ?? null,
            'district_code' => $row['district_code'] ?? null,
            'province' => $row['province'] ?? null,
            'province_code' => $row['province_code'] ?? null,
            'point_coordinate' => $row['point_coordinate'] ?? null,
            'industry' => $row['industry'] ?? null,
            'source' => $row['source'] ?? null,
            'created_by' => Auth::id(),
            'status' => 'active'
        ];
    }

    protected function isEmptyRow($row)
    {
        // Check if all important fields are empty
        return empty($row['name']) && 
               empty($row['identity_no']) && 
               empty($row['phone']) && 
               empty($row['address']);
    }

    public function getValidRows()
    {
        return $this->validRows;
    }

    public function getInvalidRows()
    {
        return $this->invalidRows;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    public function getTotalValidRows()
    {
        return count($this->validRows);
    }

    public function headingRow(): int
    {
        return 4; // Headers are on row 4 (after title, instructions, and empty row)
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }
}
