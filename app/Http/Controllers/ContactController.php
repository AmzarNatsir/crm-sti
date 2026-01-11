<?php

namespace App\Http\Controllers;

use App\Services\ContactApiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function list()
    {
        return view('contacts.index');
    }

    public function datatables(Request $request, ContactApiService $service)
    {
        // Logic to fetch and return contact data for datatables
        $apiResult = $service->getContacts();
        // Pastikan format array
        $contacts = $apiResult ?? [];



        return DataTables::collection($contacts)
            ->addIndexColumn()
            ->editColumn('company', fn ($c) => $c['company'] ?? '-')
            ->make(true);



    }
}
