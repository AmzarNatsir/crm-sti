<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

use function Symfony\Component\Clock\now;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('employee')->latest()->paginate(10);
        $count = User::count();
        $roles = \Spatie\Permission\Models\Role::all();
        $employees = Employee::doesntHave('user')->orderBy('name')->get();
        return view('users.index', compact('users', 'count', 'roles', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id|unique:users,employee_id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,name',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_in_form', 'add');
        }

        $user = User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'last_login_at' => now(),
        ]);

        if($request->has('roles')){
            $user->assignRole($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.delete', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        $employees = Employee::whereDoesntHave('user', function($q) use ($id) {
            $q->where('id', '!=', $id);
        })->orderBy('name')->get();
        return view('users.edit', compact('user', 'roles', 'employees'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        try {
            $rules = [
                'name' => 'required|string|max:255',
                'password' => ['nullable', 'string', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,name',
            ];

            // Conditional validation for employee_id
            if ($request->employee_id != $user->employee_id) {
                 $rules['employee_id'] = 'required|exists:employees,id|unique:users,employee_id';
            } else {
                 $rules['employee_id'] = 'required|exists:employees,id';
            }

            // Conditional validation for email
            if ($request->email != $user->email) {
                 $rules['email'] = 'required|string|email|max:255|unique:users,email';
            } else {
                 $rules['email'] = 'required|string|email|max:255';
            }

            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_in_form', 'edit')->with('edit_user_id', $id);
        }

        $updateData = [
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        if($request->has('roles')){
            $user->syncRoles($request->roles);
        }

        return redirect()->to(route('users.index') . '#offcanvas_add')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function datatables()
    {
        $users = User::with('employee')->get();
        // dd($users);
        return response()->json([
        'data' => $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'employee_name' => $user->employee ? $user->employee->name : '-',
                'employee_number' => $user->employee ? $user->employee->employee_number : '-',
                'roles' =>  $user->roles->pluck('name')->values(),
                'created' => $user->created_at->format('d M Y, h:i a'),
                'last_activity' => $user->last_login_formatted,
                'status' => $user->status,
            ];
        })
    ]);
    }
}
