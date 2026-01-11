<?php

namespace App\Http\Controllers;

use App\Models\RefCompign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RefCompignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ref_compign.index');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $data = RefCompign::query()->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="d-flex align-items-center gap-2">';
                $btn .= '<a href="'.route('ref-compign.edit', $row->id).'" class="btn btn-sm btn-primary"><i class="ti ti-edit"></i></a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="ti ti-trash"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('status', function($row){
                $checked = $row->status == 'active' ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" data-id="'.$row->id.'" '.$checked.'>
                        </div>';
            })
            ->editColumn('target_revenue', function($row) {
                return number_format($row->target_revenue, 2);
            })
            ->editColumn('badget', function($row) {
                return number_format($row->badget, 2);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ref_compign.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_sales' => 'nullable|integer',
            'target_revenue' => 'nullable|numeric',
            'badget' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['uuid'] = (string) Str::uuid();

        RefCompign::create($data);

        return redirect()->route('ref-compign.index')->with('success', 'Campaign created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $compign = RefCompign::findOrFail($id);
        return view('ref_compign.edit', compact('compign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_sales' => 'nullable|integer',
            'target_revenue' => 'nullable|numeric',
            'badget' => 'nullable|numeric',
        ]);

        $compign = RefCompign::findOrFail($id);
        $compign->update($request->all());

        return redirect()->route('ref-compign.index')->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $compign = RefCompign::findOrFail($id);
        $compign->delete();

        return response()->json(['success' => 'Campaign deleted successfully.']);
    }

    /**
     * Update campaign status via Ajax.
     */
    public function updateStatus(Request $request)
    {
        $compign = RefCompign::findOrFail($request->id);
        $compign->status = $request->status;
        $compign->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }
}
