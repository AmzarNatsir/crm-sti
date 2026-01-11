<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\PermissionSubject;
use App\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $count = Permission::count();
        return view('permissions.index', compact('count'));
    }

    public function create()
    {
        $roles = Role::all();
        $subjects = PermissionSubject::all();
        return view('permissions.add', compact('roles', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'guard_name' => 'required|string|max:255',
            'subject_id' => 'nullable|exists:permission_subjects,id',
            'roles' => 'array'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'subject_id' => $request->subject_id
        ]);

        if ($request->has('roles')) {
            $permission->syncRoles($request->roles);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        $roles = Role::all();
        $subjects = PermissionSubject::all();
        return view('permissions.edit', compact('permission', 'roles', 'subjects'));
    }

    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'guard_name' => 'required|string|max:255',
            'subject_id' => 'nullable|exists:permission_subjects,id',
            'roles' => 'array'
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'subject_id' => $request->subject_id
        ]);

        if ($request->has('roles')) {
            $permission->syncRoles($request->roles);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['success' => true]);
    }

    public function datatables()
    {
        $permissions = Permission::with(['roles', 'subject'])
            ->select(['id', 'name', 'guard_name', 'subject_id', 'created_at'])
            ->orderBy('subject_id')
            ->get();
        return response()->json([
            'data' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                    'subject' => $permission->subject ? $permission->subject->name : '-',
                    'roles' => $permission->roles->pluck('name')->toArray(),
                    'created' => $permission->created_at->format('d M Y, h:i a')
                ];
            })
        ]);
    }
}
