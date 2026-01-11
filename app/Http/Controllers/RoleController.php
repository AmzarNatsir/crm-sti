<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionSubject;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(10);
        $count = Role::count();
        return view('roles.index', compact('roles', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::with('subject')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        return view('roles.add', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('roles.edit', $id);
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::with('subject')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        return view('roles.edit', compact('role', 'groupedPermissions'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    private function groupPermissions($permissions)
    {
        $grouped = [];
        foreach ($permissions as $permission) {
            $module = $permission->subject ? $permission->subject->name : 'Other';
            
            // Extract action from name (e.g. customers_view -> view)
            $parts = explode('_', $permission->name);
            $action = count($parts) > 1 ? end($parts) : $permission->name;

            $grouped[$module][$action] = $permission;
        }
        return $grouped;
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function datatables()
    {
        $roles = Role::select(['id', 'name', 'guard_name', 'created_at'])->with('permissions')->get();
        return response()->json([
            'data' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions' =>  $role->permissions->pluck('name')->values(),
                    'permissions_count' => $role->permissions->count(),
                    'created' => $role->created_at->format('d M Y, h:i a')
                ];
            })
        ]);
    }
}
