<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethodModel;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('common_payment_method.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('common_payment_method.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:common_payment_method,name',
        ]);

        PaymentMethodModel::create([
            'uid' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Payment Method created successfully.']);
        }

        return redirect()->route('common-payment-method.index')->with('success', 'Payment Method created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment_method = PaymentMethodModel::findOrFail($id);
        return view('common_payment_method.edit', compact('payment_method'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment_method = PaymentMethodModel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:common_payment_method,name,' . $id,
        ]);

        $payment_method->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Payment Method updated successfully.']);
        }

        return redirect()->route('common-payment-method.index')->with('success', 'Payment Method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment_method = PaymentMethodModel::findOrFail($id);
        $payment_method->delete();
        if (request()->ajax()) {
            return response()->json(['success' => 'Payment Method deleted successfully.']);
        }

        return redirect()->route('common-payment-method.index')->with('success', 'Payment Method deleted successfully.');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $payment_methods = PaymentMethodModel::select(['id', 'name', 'created_at'])->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $payment_methods->map(function ($payment_method, $index) {
                return [
                    'nom' => $index + 1,
                    'id' => $payment_method->id,
                    'name' => $payment_method->name,
                    'created_at' => $payment_method->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
