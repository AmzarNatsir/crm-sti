<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PositionController extends Controller
{
    public function index()
    {
        return view('common_position.index');
    }

    public function create()
    {
        return view('common_position.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:common_position,name',
        ]);

        Position::create([
            'uid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Position created successfully.']);
        }

        return redirect()->route('common-position.index')->with('success', 'Position created successfully.');
    }

    public function edit(string $id)
    {
        $position = Position::findOrFail($id);
        return view('common_position.edit', compact('position'));
    }

    public function update(Request $request, string $id)
    {
        $position = Position::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:common_position,name,' . $id,
        ]);

        $position->update([
            'name' => $request->name,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Position updated successfully.']);
        }

        return redirect()->route('common-position.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);
        $position->delete();
        if (request()->ajax()) {
            return response()->json(['success' => 'Position deleted successfully.']);
        }

        return redirect()->route('common-position.index')->with('success', 'Position deleted successfully.');
    }

    public function datatables()
    {
        $positions = Position::select(['id', 'name', 'created_at'])->get();
        return response()->json([
            'data' => $positions->map(function ($position, $index) {
                return [
                    'nom' => $index + 1,
                    'id' => $position->id,
                    'name' => $position->name,
                    'created_at' => $position->created_at->format('d M Y')
                ];
            })
        ]);
    }

}
