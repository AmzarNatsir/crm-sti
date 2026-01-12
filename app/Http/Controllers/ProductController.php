<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\TypeModel;
use App\Models\MerkModel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);
        $count = Product::count();
        return view('products.index', compact('products', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TypeModel::all();
        $merks = MerkModel::all();
        return view('products.add', compact('types', 'merks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sanitize input
        if ($request->has('margin')) {
            $margin = str_replace(['.', ','], ['', '.'], $request->margin);
            $request->merge(['margin' => $margin === '' ? null : $margin]);
        }

        // Handle Price types
        foreach (['price_cs', 'price_r1', 'price_r2'] as $pType) {
            if ($request->has($pType)) {
                $request->merge([$pType => str_replace(['.', ','], ['', '.'], $request->$pType)]);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'type_id' => 'required|exists:common_type,id',
            'merk_id' => 'required|exists:common_merk,id',
            'category' => 'nullable|string|max:255',
            'price_cs' => 'required|numeric',
            'price_r1' => 'required|numeric',
            'price_r2' => 'required|numeric',
            'margin' => 'nullable|numeric',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048'
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);

        // Save price types
        foreach (['CS' => 'price_cs', 'R1' => 'price_r1', 'R2' => 'price_r2'] as $type => $field) {
            if ($request->filled($field)) {
                $product->prices()->create([
                    'type' => $type,
                    'price' => $request->$field
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product created successfully.']);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('prices')->findOrFail($id);
        $types = TypeModel::all();
        $merks = MerkModel::all();
        
        // Map prices for easier access in view
        $prices = $product->prices->pluck('price', 'type')->toArray();
        $product->price_cs = $prices['CS'] ?? null;
        $product->price_r1 = $prices['R1'] ?? null;
        $product->price_r2 = $prices['R2'] ?? null;

        return view('products.edit', compact('product', 'types', 'merks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Sanitize input before validation
        if ($request->has('margin')) {
            $margin = str_replace(['.', ','], ['', '.'], $request->margin);
            $request->merge(['margin' => $margin === '' ? null : $margin]);
        }

        // Handle Price types
        foreach (['price_cs', 'price_r1', 'price_r2'] as $pType) {
            if ($request->has($pType)) {
                $request->merge([$pType => str_replace(['.', ','], ['', '.'], $request->$pType)]);
            }
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'type_id' => 'required|exists:common_type,id',
            'merk_id' => 'required|exists:common_merk,id',
            'category' => 'nullable|string|max:255',
            'price_cs' => 'required|numeric',
            'price_r1' => 'required|numeric',
            'price_r2' => 'required|numeric',
            'margin' => 'nullable|numeric',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048'
        ]);

        try {
            $data = $request->except(['_token', '_method', 'image']);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product->update($data);

            // Update price types
            foreach (['CS' => 'price_cs', 'R1' => 'price_r1', 'R2' => 'price_r2'] as $type => $field) {
                if ($request->filled($field)) {
                    $product->prices()->updateOrCreate(
                        ['type' => $type],
                        ['price' => $request->$field]
                    );
                } else {
                    $product->prices()->where('type', $type)->delete();
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
            }

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $products = Product::with(['type', 'merk', 'prices'])->get();
        return response()->json([
            'data' => $products->map(function ($product) {
                // Get prices for display (e.g., CS as primary or all)
                $prices = $product->prices->pluck('price', 'type')->toArray();
                $price_display = "CS: " . number_format($prices['CS'] ?? 0) . 
                                " | R1: " . number_format($prices['R1'] ?? 0) . 
                                " | R2: " . number_format($prices['R2'] ?? 0);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'category' => $product->type ? $product->type->name : $product->category,
                    'merk' => $product->merk ? $product->merk->name : '-',
                    'price' => $price_display,
                    'margin' => number_format($product->margin ?? 0, 1, ',', '.'),
                    'status' => $product->is_active ? 'Active' : 'Inactive',
                    'created_at' => $product->created_at ? $product->created_at->format('d M Y') : '-'
                ];
            })
        ]);
    }
}
