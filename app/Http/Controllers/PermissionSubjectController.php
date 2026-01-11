<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionSubject;
use Illuminate\Support\Str;

class PermissionSubjectController extends Controller
{
    public function index()
    {
        return view('permission-subjects.index');
    }

    public function create()
    {
        return view('permission-subjects.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permission_subjects,name',
        ]);

        PermissionSubject::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Subject created successfully.']);
        }

        return redirect()->route('permission-subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(string $id)
    {
        $subject = PermissionSubject::findOrFail($id);
        return view('permission-subjects.edit', compact('subject'));
    }

    public function update(Request $request, string $id)
    {
        $subject = PermissionSubject::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permission_subjects,name,' . $id,
        ]);

        $subject->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Subject updated successfully.']);
        }

        return redirect()->route('permission-subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(string $id)
    {
        $subject = PermissionSubject::findOrFail($id);
        $subject->delete();
        if (request()->ajax()) {
            return response()->json(['success' => 'Subject deleted successfully.']);
        }

        return redirect()->route('permission-subjects.index')->with('success', 'Subject deleted successfully.');
    }

    public function datatables()
    {
        $subjects = PermissionSubject::select(['id', 'name', 'created_at'])->get();
        return response()->json([
            'data' => $subjects->map(function ($subject, $index) {
                return [
                    'nom' => $index + 1,
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'created_at' => $subject->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
