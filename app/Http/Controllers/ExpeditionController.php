<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expedition;
use Ramsey\Uuid\Uuid;

class ExpeditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('expedition.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expedition.add');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expedition = Expedition::findOrFail($id);
        return view('expedition.edit', compact('expedition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
        ]);

        Expedition::create([
            'uid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'contact_person' => $request->contact_person,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Expedition created successfully.']);
        }

        return redirect()->route('expedition.index')->with('success', 'Expedition created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expedition = Expedition::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:20',
        ]);

        $expedition->update([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'contact_person' => $request->contact_person,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Expedition updated successfully.']);
        }

        return redirect()->route('expedition.index')->with('success', 'Expedition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expedition = Expedition::findOrFail($id);
        $expedition->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => 'Expedition deleted successfully.']);
        }

        return redirect()->route('expedition.index')->with('success', 'Expedition deleted successfully.');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $expeditions = Expedition::select(['id', 'name', 'address', 'email', 'phone_number', 'contact_person', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'data' => $expeditions->map(function ($expedition, $index) {
                return [
                    'nom' => $index + 1,
                    'id' => $expedition->id,
                    'name' => $expedition->name,
                    'address' => $expedition->address,
                    'email' => $expedition->email ?? '-',
                    'phone_number' => $expedition->phone_number,
                    'contact_person' => $expedition->contact_person ?? '-',
                    'created_at' => $expedition->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
