<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProspectController extends Controller
{
    public function list()
    {
        $commodities = \App\Models\RefCommodity::all();
        return view('prospects.index', compact('commodities'));
    }

    public function datatables(Request $request)
    {
        $query = Customer::query()
            ->with(['creator', 'commodity', 'contact'])
            ->where('type', 'prospect');

        if ($request->filled('commodity_id')) {
            $query->where('commodity_id', $request->commodity_id);
        }
        if ($request->filled('contact_type')) {
            $query->whereHas('contact', function ($q) use ($request) {
                $q->where('jenisKontak', $request->contact_type);
            });
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('identity_no')) {
            $query->where('identity_no', 'like', '%' . $request->identity_no . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        return DataTables::of($query)
            ->addColumn('company', fn ($c) => $c->company_name)
            ->addColumn('commodity_name', fn ($c) => $c->commodity ? $c->commodity->name : '-')
            ->addColumn('contact_type', fn ($c) => $c->contact ? $c->contact->jenisKontak : '-')
            ->editColumn('status', function ($c) {
                return $c->status ?: 'Active';
            })
            ->editColumn('created_at', fn ($c) => $c->created_at ? $c->created_at->format('Y-m-d') : '-')
            ->make(true);
    }

    public function promote($id)
    {
        try {
            $prospect = Customer::where('id', $id)->where('type', 'prospect')->firstOrFail();
            $prospect->update(['type' => 'customer']);

            // Notify the current user
            // auth()->user()->notify(new \App\Notifications\ProspectPromoted($prospect));

            return response()->json([
                'success' => true,
                'message' => 'Prospect successfully promoted to Customer.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error promoting prospect: ' . $e->getMessage()
            ], 500);
        }
    }
}
