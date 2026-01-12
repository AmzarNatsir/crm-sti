<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Customer;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

use Illuminate\Support\Str;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Activity::distinct()->pluck('type');
        return view('activities.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Process DataTables ajax request.
     */
    public function datatables(Request $request)
    {
        if ($request->ajax()) {
            $data = Activity::with(['customer', 'user'])->select('activities.*');

            // Apply Filters
            if ($request->has('type') && $request->type != '') {
                $data->where('type', $request->type);
            }

            if ($request->has('start_date') && $request->start_date != '') {
                $data->whereDate('created_at', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
            }

            if ($request->has('end_date') && $request->end_date != '') {
                $data->whereDate('created_at', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
            }

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer_name', function($row){
                        return $row->customer ? $row->customer->name : '-';
                    })
                    ->addColumn('user_name', function($row){
                        return $row->user ? $row->user->name : '-';
                    })
                    ->editColumn('follow_up_date', function($row){
                        return $row->follow_up_date ? \Carbon\Carbon::parse($row->follow_up_date)->format('d M Y') : '-';
                    })
                    ->editColumn('created_at', function($row){
                        return \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i');
                    })
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('activities.edit', $row->id).'" class="btn btn-primary btn-sm me-1"><i class="ti ti-edit"></i> Edit</a>';
                        $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteActivity"><i class="ti ti-trash"></i> Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        Activity::create([
            'uid' => (string) Str::uuid(),
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json(['success'=>'Activity saved successfully.']);
        }

        return redirect()->route('activities.index')->with('success', 'Activity saved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $activity = Activity::with('customer')->findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update([
            'customer_id' => $request->customer_id,
            'type' => $request->type,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json(['success'=>'Activity updated successfully.']);
        }

        return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Activity::findOrFail($id)->delete();
        return response()->json(['success'=>'Activity deleted successfully.']);
    }

    public function getCustomers(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;
            $data = Customer::where('name', 'LIKE', "%$search%")
                    ->select('id', 'name')
                    ->limit(20)
                    ->get();
        } else {
             $data = Customer::select('id', 'name')->limit(20)->get();
        }
        return response()->json($data);
    }
}
