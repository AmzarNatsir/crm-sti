<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employees.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = Position::orderBy('name')->get();
        return view('employees.add', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number',
            'identitiy_number' => 'required|string|max:50|unique:employees,identitiy_number',
            'name' => 'required|string|max:100',
            'email' => [
                'required', 'email', 'max:100',
                'unique:employees,email',
                'unique:users,email'
            ],
            'phone' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:100',
            'last_education' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'address' => 'nullable|string|max:200',
            'positionId' => 'nullable|exists:common_position,id',
            'hire_date' => 'nullable|date',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,resigned',
            'salary' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        try {
            $data = $request->except(['photo']);
            $data['uid'] = (string) Str::uuid();

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('employees', 'public');
                $data['photo'] = $path;
            }

            Employee::create($data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Employee created successfully.']);
            }

            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $positions = Position::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number,' . $id,
            'identitiy_number' => 'required|string|max:50|unique:employees,identitiy_number,' . $id,
            'name' => 'required|string|max:100',
            'email' => [
                'required', 'email', 'max:100',
                'unique:employees,email,' . $id,
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($employee->user ? $employee->user->id : 0),
            ],
            'phone' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:100',
            'last_education' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'address' => 'nullable|string|max:200',
            'positionId' => 'nullable|exists:common_position,id',
            'hire_date' => 'nullable|date',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,resigned',
            'salary' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        try {
            $data = $request->except(['_token', '_method', 'photo']);

            if ($request->hasFile('photo')) {
                // Delete old file if exists
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }

                $path = $request->file('photo')->store('employees', 'public');
                $data['photo'] = $path;
            }

            $employee->update($data);

            // Sync with User if exists
            if ($employee->user) {
                $employee->user->update([
                    'email' => $employee->email,
                    'name' => $employee->name,
                ]);
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Employee updated successfully.']);
            }

            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        $employee->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Employee deleted successfully.']);
        }

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    /**
     * Datatables for employees.
     */
    public function datatables(Request $request)
    {
        $query = Employee::query()
            ->with(['position'])
            ->select('employees.*');

        return DataTables::of($query)
            ->addColumn('position_name', function ($row) {
                return $row->position ? $row->position->name : '-';
            })
            ->editColumn('salary', function ($row) {
                return number_format($row->salary, 0, ',', '.');
            })
            ->editColumn('status', function ($row) {
                $class = 'badge-soft-success';
                if ($row->status == 'inactive') $class = 'badge-soft-warning';
                if ($row->status == 'resigned') $class = 'badge-soft-danger';
                return '<span class="badge ' . $class . '">' . ucfirst($row->status) . '</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
