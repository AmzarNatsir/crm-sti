<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeModel;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('common_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('common_type.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:common_type,name',
        ]);

        TypeModel::create([
            'uid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5), // Ensure uniqueness
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Type created successfully.']);
        }

        return redirect()->route('common-type.index')->with('success', 'Type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type = TypeModel::findOrFail($id);
        return view('common_type.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $type = TypeModel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:common_type,name,' . $id,
        ]);

        $type->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5), // Update slug or keep distinct
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Type updated successfully.']);
        }

        return redirect()->route('common-type.index')->with('success', 'Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = TypeModel::findOrFail($id);
        $type->delete();
        if (request()->ajax()) {
            return response()->json(['success' => 'Type deleted successfully.']);
        }

        return redirect()->route('common-type.index')->with('success', 'Type deleted successfully.');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $types = TypeModel::select(['id', 'name', 'created_at'])->get();
        return response()->json([
            'data' => $types->map(function ($type, $index) {
                return [
                    'nom' => $index + 1,
                    'id' => $type->id,
                    'name' => $type->name,
                    'created_at' => $type->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
