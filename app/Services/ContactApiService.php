<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ContactApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.telemarketing_api.url');
    }

    public function getContacts(array $params = [])
    {
        $response = Http::get($this->baseUrl . '/contacts', $params);
        
        if ($response->failed()) {
            throw new \Exception('Failed to fetch contacts from API');
        }

        return $response->json();
    }
}
