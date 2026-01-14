<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResources;
use App\Models\Customer;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Exports\CustomerTemplateExport;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commodities = \App\Models\RefCommodity::all();
        return view('customers.index', compact('commodities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commodities = \App\Models\RefCommodity::all();
        $provinces = Province::orderBy('name')->get();
        return view('customers.add', compact('commodities', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'identity_no' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'type' => 'required|string',
            'commodity_id' => 'nullable|exists:ref_commodity,id',
            'village' => 'nullable|string',
            'village_code' => 'nullable|string',
            'sub_district' => 'nullable|string',
            'sub_district_code' => 'nullable|string',
            'district' => 'nullable|string',
            'district_code' => 'nullable|string',
            'province' => 'nullable|string',
            'province_code' => 'nullable|string',
            'point_coordinate' => 'nullable|string',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        try {
            $data = $request->except(['photo_profile']);
            $data['uid'] = uniqid();
            $data['created_by'] = auth()->id();

            if ($request->hasFile('photo_profile')) {
                $path = $request->file('photo_profile')->store('customers', 'public');
                $data['photo_profile'] = $path;
            }

            Customer::create($data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Customer created successfully.']);
            }

            return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error creating customer: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $commodities = \App\Models\RefCommodity::all();
        $provinces = Province::orderBy('name')->get();
        // relation mapping: province_code (customer) -> province_id (regency table)
        // district_code (customer) -> id (regency table)
        // sub_district_code (customer) -> id (district table)
        // village_code (customer) -> id (village table)
        
        $regencies = $customer->province_code ? Regency::where('province_id', $customer->province_code)->orderBy('name')->get() : [];
        $districts = $customer->district_code ? District::where('regency_id', $customer->district_code)->orderBy('name')->get() : [];
        $villages = $customer->sub_district_code ? Village::where('district_id', $customer->sub_district_code)->orderBy('name')->get() : [];

        return view('customers.edit', compact('customer', 'commodities', 'provinces', 'regencies', 'districts', 'villages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'identity_no' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'type' => 'required|string',
            'commodity_id' => 'nullable|exists:ref_commodity,id',
            'village' => 'nullable|string',
            'village_code' => 'nullable|string',
            'sub_district' => 'nullable|string',
            'sub_district_code' => 'nullable|string',
            'district' => 'nullable|string',
            'district_code' => 'nullable|string',
            'province' => 'nullable|string',
            'province_code' => 'nullable|string',
            'point_coordinate' => 'nullable|string',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        try {
            $customer = Customer::findOrFail($id);
            $data = $request->except(['_token', '_method', 'photo_profile']);

            if ($request->hasFile('photo_profile')) {
                // Delete old file if exists
                if ($customer->photo_profile) {
                    Storage::disk('public')->delete($customer->photo_profile);
                }

                $path = $request->file('photo_profile')->store('customers', 'public');
                $data['photo_profile'] = $path;
            }

            $customer->update($data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Customer updated successfully.']);
            }

            return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        if ($customer->photo_profile) {
            Storage::disk('public')->delete($customer->photo_profile);
        }
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function datatables(Request $request)
    {
        $query = Customer::query()
            ->with(['creator', 'commodity'])
            ->where('type', 'customer');

        if ($request->filled('commodity_id')) {
            $query->where('commodity_id', $request->commodity_id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('identity_no')) {
            $query->where('identity_no', 'like', '%' . $request->identity_no . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        $query->orderBy('created_at', 'desc')
            ->select([
                'id',
                'uid',
                'type',
                'commodity_id',
                'name',
                'identity_no',
                'date_of_birth',
                'company_name',
                'phone',
                'email',
                'address',
                'village',
                'village_code',
                'sub_district',
                'sub_district_code',
                'district',
                'district_code',
                'province',
                'province_code',
                'point_coordinate',
                'photo_profile',
                'created_at',
            ]);

        return DataTables::of($query)
            ->addColumn('company', fn ($c) => $c->company_name)
            ->addColumn('commodity_name', fn ($c) => $c->commodity ? $c->commodity->name : '-')
            ->editColumn('created_at', fn ($c) => $c->created_at->format('Y-m-d'))
            ->make(true);
    }
    public function summary($id)
    {
        $customer = Customer::findOrFail($id);

        // 1. KPI Stats for this customer
        $totalOrders = \App\Models\Order::where('customer_id', $id)->count();
        $totalSpend = \App\Models\Order::where('customer_id', $id)->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalSpend / $totalOrders : 0;

        // 2. Frequently Purchased Products (Top 5)
        $topProducts = \App\Models\OrderItemsModel::join('orders', 'orders_items.order_id', '=', 'orders.id')
            ->select('orders_items.product_id', \DB::raw('SUM(orders_items.qty) as total_qty'))
            ->where('orders.customer_id', $id)
            ->with('product')
            ->groupBy('orders_items.product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 3. Favorite Brands (Top 5)
        $topBrands = \App\Models\OrderItemsModel::join('orders', 'orders_items.order_id', '=', 'orders.id')
            ->join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('common_merk', 'products.merk_id', '=', 'common_merk.id')
            ->select('common_merk.name', \DB::raw('SUM(orders_items.qty) as total_qty'))
            ->where('orders.customer_id', $id)
            ->groupBy('common_merk.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 4. Payment Method Breakdown
        $paymentMethods = \App\Models\Order::join('common_payment_method', 'orders.payment_method_id', '=', 'common_payment_method.id')
            ->select('common_payment_method.name', \DB::raw('COUNT(orders.id) as count'))
            ->where('orders.customer_id', $id)
            ->groupBy('common_payment_method.name')
            ->get();

        // 5. Shopping Time (Hourly)
        $hourlyStats = \App\Models\Order::select(\DB::raw('HOUR(created_at) as hour'), \DB::raw('COUNT(id) as count'))
            ->where('customer_id', $id)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();
            
        $shoppingTime = array_fill(0, 24, 0);
        foreach ($hourlyStats as $hour => $count) {
            $shoppingTime[$hour] = $count;
        }

        return view('customers.summary', compact(
            'customer',
            'totalOrders',
            'totalSpend',
            'averageOrderValue',
            'topProducts',
            'topBrands',
            'paymentMethods',
            'shoppingTime'
        ));
    }

    /**
     * Download Excel template for customer import
     */
    public function downloadTemplate()
    {
        $fileName = 'customer_import_template_' . Carbon::now()->format('Ymd') . '.xlsx';
        return Excel::download(new CustomerTemplateExport, $fileName);
    }

    /**
     * Preview imported data with validation
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120' // 5MB max
        ]);

        try {
            $file = $request->file('file');
            
            // Create import instance
            $import = new CustomerImport();
            
            // Process the file
            Excel::import($import, $file);
            
            // Get validation results
            $validRows = $import->getValidRows();
            $invalidRows = $import->getInvalidRows();
            
            // Prepare preview data (limit to first 100 rows for performance)
            $previewData = array_slice($validRows, 0, 100);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_rows' => count($validRows) + count($invalidRows),
                    'valid_count' => count($validRows),
                    'invalid_count' => count($invalidRows),
                    'preview' => $previewData,
                    'invalid_rows' => $invalidRows,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process and import valid customer data
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120'
        ]);

        try {
            $file = $request->file('file');
            
            // Create import instance
            $import = new CustomerImport();
            
            // First, validate all rows
            Excel::import($import, $file);
            
            // Get valid rows count for progress tracking
            $totalValid = $import->getTotalValidRows();
            
            if ($totalValid === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid rows to import'
                ], 400);
            }
            
            // Process the import
            $result = $import->import();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_valid' => $totalValid,
                    'imported' => $result['imported'],
                    'failed' => $result['failed'],
                    'errors' => $result['errors']
                ],
                'message' => "Import selesai. Berhasil: {$result['imported']} data, Gagal: {$result['failed']} data"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing data: ' . $e->getMessage()
            ], 500);
        }
    }
}
