<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParamWilayah;
use App\Models\Province;

class ParamWilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('param-wilayah.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('param-wilayah.add', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        // Strip commas for numeric fields
        foreach (['ckm', 'ct', 'tarif_min'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = str_replace(',', '', $data[$field]);
            }
        }

        $request->merge($data);

        $request->validate([
            'zona' => 'required|string|max:50',
            'province_id' => 'required|exists:provinces,id',
            'ckm' => 'nullable|numeric',
            'ct' => 'nullable|numeric',
            'tarif_min' => 'nullable|numeric',
            'alpha_max_retail' => 'nullable|numeric',
            'alpha_max_reseller' => 'nullable|numeric',
        ]);

        ParamWilayah::create($data);

        return response()->json(['success' => 'Regional Parameter created successfully.']);
    }

    /**
     * Show the specified resource for editing.
     */
    public function edit(string $id)
    {
        $paramWilayah = ParamWilayah::findOrFail($id);
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('param-wilayah.edit', compact('paramWilayah', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paramWilayah = ParamWilayah::findOrFail($id);
        $data = $request->all();

        // Strip commas for numeric fields
        foreach (['ckm', 'ct', 'tarif_min'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = str_replace(',', '', $data[$field]);
            }
        }

        $request->merge($data);

        $request->validate([
            'zona' => 'required|string|max:50',
            'province_id' => 'required|exists:provinces,id',
            'ckm' => 'nullable|numeric',
            'ct' => 'nullable|numeric',
            'tarif_min' => 'nullable|numeric',
            'alpha_max_retail' => 'nullable|numeric',
            'alpha_max_reseller' => 'nullable|numeric',
        ]);

        $paramWilayah->update($data);

        return response()->json(['success' => 'Regional Parameter updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paramWilayah = ParamWilayah::findOrFail($id);
        $paramWilayah->delete();

        return response()->json(['success' => 'Regional Parameter deleted successfully.']);
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $params = ParamWilayah::with('province')
            ->select('ref_param_wilayah.*')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $params->map(function ($param, $index) {
                return [
                    'no' => $index + 1,
                    'id' => $param->id,
                    'zona' => $param->zona,
                    'province_name' => $param->province->name ?? '-',
                    'ckm' => number_format($param->ckm, 0),
                    'ct' => number_format($param->ct, 0),
                    'tarif_min' => number_format($param->tarif_min, 0),
                    'alpha_max_retail' => $param->alpha_max_retail,
                    'alpha_max_reseller' => $param->alpha_max_reseller,
                    'created_at' => $param->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
