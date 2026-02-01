<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KoefMedan;

class KoefMedanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('koef-medan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('koef-medan.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_medan' => 'required|string|max:10',
            'description' => 'required|string|max:100',
            'km' => 'required|numeric',
        ]);

        KoefMedan::create($request->all());

        return response()->json(['success' => 'Medan Coefficient created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $koefMedan = KoefMedan::findOrFail($id);
        return view('koef-medan.edit', compact('koefMedan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $koefMedan = KoefMedan::findOrFail($id);

        $request->validate([
            'kode_medan' => 'required|string|max:10',
            'description' => 'required|string|max:100',
            'km' => 'required|numeric',
        ]);

        $koefMedan->update($request->all());

        return response()->json(['success' => 'Medan Coefficient updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $koefMedan = KoefMedan::findOrFail($id);
        $koefMedan->delete();

        return response()->json(['success' => 'Medan Coefficient deleted successfully.']);
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $data = KoefMedan::select(['id', 'kode_medan', 'description', 'km', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $data->map(function ($item, $index) {
                return [
                    'no' => $index + 1,
                    'id' => $item->id,
                    'kode_medan' => $item->kode_medan,
                    'description' => $item->description,
                    'km' => $item->km,
                    'created_at' => $item->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
