<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
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
        if ($request->has('price')) {
            $request->merge(['price' => str_replace(['.', ','], ['', '.'], $request->price)]);
        }
        if ($request->has('margin')) {
            $margin = str_replace(['.', ','], ['', '.'], $request->margin);
            $request->merge(['margin' => $margin === '' ? null : $margin]);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'type_id' => 'required|exists:common_type,id',
            'merk_id' => 'required|exists:common_merk,id',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'margin' => 'nullable|numeric',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048'
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

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
        $product = Product::findOrFail($id);
        $types = TypeModel::all();
        $merks = MerkModel::all();
        return view('products.edit', compact('product', 'types', 'merks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Sanitize input before validation
        if ($request->has('price')) {
            $request->merge(['price' => str_replace(['.', ','], ['', '.'], $request->price)]);
        }
        if ($request->has('margin')) {
            $margin = str_replace(['.', ','], ['', '.'], $request->margin);
            $request->merge(['margin' => $margin === '' ? null : $margin]);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'type_id' => 'required|exists:common_type,id',
            'merk_id' => 'required|exists:common_merk,id',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric',
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
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Get data for DataTables.
     */
    public function datatables()
    {
        $products = Product::with(['type', 'merk'])->select(['id', 'name', 'type_id', 'merk_id', 'category', 'price', 'margin', 'is_active', 'image', 'created_at'])->get();
        return response()->json([
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'category' => $product->type ? $product->type->name : $product->category, // Fallback to category if type is null (migration phase)
                    'merk' => $product->merk ? $product->merk->name : '-',
                    'price' => number_format($product->price),
                    'margin' => $product->margin ?? 0,
                    'status' => $product->is_active ? 'Active' : 'Inactive',
                    'created_at' => $product->created_at->format('d M Y')
                ];
            })
        ]);
    }
}
