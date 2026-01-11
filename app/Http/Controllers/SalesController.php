<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItemsModel;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PaymentMethodModel;
use App\Models\RefCompign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        $payment_methods = PaymentMethodModel::all();
        return view('sales.index', compact('customers', 'payment_methods'));
    }

    public function create()
    {
        $customers = Customer::whereIn('type', ['customer', 'prospect'])->orderBy('created_at', 'desc')->get();
        $products = Product::where('is_active', 1)->get();
        $payment_methods = PaymentMethodModel::all();
        $campaigns = RefCompign::where('status', 'active')->get();
        
        return view('sales.create', compact('customers', 'products', 'payment_methods', 'campaigns'));
    }

    public function store(Request $request)
    {
        // Sanitize input
        if ($request->has('items')) {
            $items = $request->items;
            foreach ($items as $key => $item) {
                if (isset($item['price'])) {
                    $items[$key]['price'] = str_replace(['.', ','], ['', '.'], $item['price']);
                }
            }
            $request->merge(['items' => $items]);
        }
        
        if ($request->has('invoice_discount')) {
            $request->merge(['invoice_discount' => str_replace(['.', ','], ['', '.'], $request->invoice_discount)]);
        }

        $request->validate([
            'customer_id' => 'required',
            'invoice_no' => 'required|string|max:50',
            'invoice_date' => 'required|string',
            'payment_method_id' => 'required',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'uid' => (string) Str::uuid(),
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'invoice_no' => $request->invoice_no,
                'invoice_date' => Carbon::parse($request->invoice_date)->format('Y-m-d'),
                'total_amount' => $request->total_amount,
                'payment_method_id' => $request->payment_method_id,
                'payment_status' => $request->payment_status ?? 'paid',
                'compaign_id' => $request->compaign_id,
                'invoice_discount' => $request->invoice_discount ?? 0,
            ]);

            foreach ($request->items as $item) {
                OrderItemsModel::create([
                    'uid' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);
            }
            
            // New logic: Change customer type from prospect to customer if they make a purchase
            $customer = Customer::find($request->customer_id);
            if ($customer && $customer->type === 'prospect') {
                $customer->update(['type' => 'customer']);
                 $customer->update(['created_at' => now()]);
                 $customer->update(['updated_at' => now()]);
                // Save activity type promote_to_customer type completed
                \App\Models\Activity::create([
                    'customer_id' => $customer->id,
                    'user_id' => auth()->id(),
                    'type' => 'promote_to_customer',
                    'status' => 'Completed',
                    'notes' => 'Customer promoted from prospect due to purchase.'
                ]);
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sales Store Error: ' . $e->getMessage());
            return back()->with('error', 'Error creating order: ' . $e->getMessage())->withInput();
        }
    }

    public function datatables(Request $request)
    {
        $query = Order::with(['customer', 'paymentMethod']);

        // Filters
        if ($request->start_date) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->payment_method_id) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'data' => $orders->map(function ($order, $index) {
                return [
                    'nom' => $index + 1,
                    'invoice_no' => $order->invoice_no,
                    'customer' => $order->customer->name ?? 'N/A',
                    'date' => Carbon::parse($order->invoice_date)->format('d M Y'),
                    'payment_method' => $order->paymentMethod->name ?? 'N/A',
                    'total' => number_format($order->total_amount, 2),
                    'status' => $order->payment_status,
                    'delivery_status' => $order->delivery_status ?? 'open',
                    'action' => '<a href="' . route('sales.show', $order->id) . '" class="btn btn-sm btn-info"><i class="ti ti-eye"></i></a>'
                ];
            })
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'paymentMethod', 'items.product'])->findOrFail($id);
        return view('sales.show', compact('order'));
    }
}
