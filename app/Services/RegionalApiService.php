<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RegionalApiService
{
    protected string $baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    public function getProvinces(): array
    {
        return Http::get("{$this->baseUrl}/provinces.json")->json() ?? [];
    }

    public function getRegencies(string $provinceId): array
    {
        return Http::get("{$this->baseUrl}/regencies/{$provinceId}.json")->json() ?? [];
    }

    public function getDistricts(string $regencyId): array
    {
        return Http::get("{$this->baseUrl}/districts/{$regencyId}.json")->json() ?? [];
    }

    public function getVillages(string $districtId): array
    {
        return Http::get("{$this->baseUrl}/villages/{$districtId}.json")->json() ?? [];
    }
}
