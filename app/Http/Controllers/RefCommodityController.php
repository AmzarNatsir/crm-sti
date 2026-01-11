<?php

namespace App\Http\Controllers;

use App\Models\RefCommodity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RefCommodityController extends Controller
{
    public function index()
    {
        return view('ref_commodity.index');
    }

    public function datatables()
    {
        $data = RefCommodity::query()->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="d-flex align-items-center gap-2">';
                $btn .= '<button type="button" class="btn btn-sm btn-primary open-edit" data-url="'.route('ref-commodity.edit', $row->id).'"><i class="ti ti-edit"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="ti ti-trash"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (request()->ajax()) {
            return view('ref_commodity.partials.form');
        }

        return view('ref_commodity.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255', Rule::unique('ref_commodity','name')],
            'description' => 'nullable|string',
            'season' => 'nullable|integer',
            'fertillization_in_season' => 'nullable|integer',
        ], [
            'name.unique' => 'Name already exists.',
            'season.integer' => 'Season must be a number.',
            'fertillization_in_season.integer' => 'Fertillization In Season must be a number.'
        ]);

        $data = $request->all();
        $data['uuid'] = (string) Str::uuid();

        RefCommodity::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => 'Commodity created successfully.']);
        }

        return redirect()->route('ref-commodity.index')->with('success', 'Commodity created successfully.');
    }

    public function edit($id)
    {
        $commodity = RefCommodity::findOrFail($id);
        if (request()->ajax()) {
            return view('ref_commodity.partials.form', compact('commodity'));
        }

        return view('ref_commodity.edit', compact('commodity'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required','string','max:255', Rule::unique('ref_commodity','name')->ignore($id)],
            'description' => 'nullable|string',
            'season' => 'nullable|integer',
            'fertillization_in_season' => 'nullable|integer',
        ], [
            'name.unique' => 'Name already exists.',
            'season.integer' => 'Season must be a number.',
            'fertillization_in_season.integer' => 'Fertillization In Season must be a number.'
        ]);

        $commodity = RefCommodity::findOrFail($id);
        $commodity->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => 'Commodity updated successfully.']);
        }

        return redirect()->route('ref-commodity.index')->with('success', 'Commodity updated successfully.');
    }

    public function destroy($id)
    {
        $commodity = RefCommodity::findOrFail($id);
        $commodity->delete();

        return response()->json(['success' => 'Commodity deleted successfully.']);
    }
}
