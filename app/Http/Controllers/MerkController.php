<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MerkModel;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MerkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('common_merk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('common_merk.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:common_merk,name',
        ]);

        MerkModel::create([
            'uid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Merk created successfully.']);
        }

        return redirect()->route('common-merk.index')->with('success', 'Merk created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $merk = MerkModel::findOrFail($id);
        return view('common_merk.edit', compact('merk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $merk = MerkModel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:common_merk,name,' . $id,
        ]);

        $merk->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Merk updated successfully.']);
        }

        return redirect()->route('common-merk.index')->with('success', 'Merk updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $merk = MerkModel::findOrFail($id);
        $merk->delete();
        if (request()->ajax()) {
            return response()->json(['success' => 'Merk deleted successfully.']);
        }

        return redirect()->route('common-merk.index')->with('success', 'Merk deleted successfully.');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $merks = MerkModel::select(['id', 'name', 'created_at'])->get();
        return response()->json([
            'data' => $merks->map(function ($merk, $index) {
                return [
                    'nom' => $index + 1,
                    'id' => $merk->id,
                    'name' => $merk->name,
                    'created_at' => $merk->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
