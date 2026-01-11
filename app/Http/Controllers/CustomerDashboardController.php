<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItemsModel;
use App\Models\Customer;
use App\Models\Product;
use App\Models\MerkModel;
use App\Models\PaymentMethodModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Stats
        $totalCustomers = Customer::where('type', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // New Customers (Current Month)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;

        $newCustomersCount = Customer::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('type', 'customer')
            ->count();

        $prevMonthCount = Customer::whereYear('created_at', $lastMonthYear)
            ->whereMonth('created_at', $lastMonth)
            ->where('type', 'customer')
            ->count();

        $customerGrowth = $prevMonthCount > 0 
            ? (($newCustomersCount - $prevMonthCount) / $prevMonthCount) * 100 
            : ($newCustomersCount > 0 ? 100 : 0);

        // 2. Frequently Purchased Products (Top 5)
        $topProducts = OrderItemsModel::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 3. Favorite Brands (Top 5)
        $topBrands = OrderItemsModel::join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('common_merk', 'products.merk_id', '=', 'common_merk.id')
            ->select('common_merk.name', DB::raw('SUM(orders_items.qty) as total_qty'))
            ->groupBy('common_merk.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 4. Payment Method Breakdown
        $paymentMethods = Order::join('common_payment_method', 'orders.payment_method_id', '=', 'common_payment_method.id')
            ->select('common_payment_method.name', DB::raw('COUNT(orders.id) as count'))
            ->groupBy('common_payment_method.name')
            ->get();

        // 5. Shopping Time (Hourly)
        $hourlyStats = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(id) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();
            
        $shoppingTime = array_fill(0, 24, 0);
        foreach ($hourlyStats as $hour => $count) {
            $shoppingTime[$hour] = $count;
        }

        // 6. Top Customers (Highest Spend)
        $topCustomers = Order::select('customer_id', DB::raw('SUM(total_amount) as total_spend'))
            ->with('customer')
            ->groupBy('customer_id')
            ->orderByDesc('total_spend')
            ->limit(5)
            ->get();

        // 7. Shopping Frequency Distribution (Monthly)
        $monthlyFrequency = Order::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(id) as order_count'),
                DB::raw('COUNT(DISTINCT customer_id) as customer_count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('customers.dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'newCustomersCount',
            'customerGrowth',
            'topProducts',
            'topBrands',
            'paymentMethods',
            'shoppingTime',
            'topCustomers',
            'monthlyFrequency'
        ));
    }

    public function getCustomerList(Request $request)
    {
        $type = $request->query('filter', 'all');
        $query = Customer::where('type', 'customer')
            ->with(['commodity'])
            ->select('customers.*') // Good practice to specify columns
            ->orderBy('created_at', 'desc');

        if ($type === 'new') {
            $query->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month);
        }

        return DataTables::of($query)
            ->addColumn('commodity_name', function ($customer) {
                return $customer->commodity->name ?? '-';
            })
            ->editColumn('created_at', function ($customer) {
                return $customer->created_at->format('Y-m-d');
            })
            ->make(true);
    }
}
