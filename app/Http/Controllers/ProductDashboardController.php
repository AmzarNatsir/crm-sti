<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItemsModel;
use App\Models\Product;
use App\Models\TypeModel;
use App\Models\MerkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Stats
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalStockValue = \App\Models\ProductPrice::where('type', 'CS')->sum('price'); // Simple sum of CS prices as proxy for value
        $totalUnitsSold = OrderItemsModel::sum('qty');

        // 2. Revenue by Product (Top 10)
        $topRevenueProducts = OrderItemsModel::select('product_id', DB::raw('SUM(subtotal) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // 3. Sales by Category (Type)
        $categorySales = OrderItemsModel::join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('common_type', 'products.type_id', '=', 'common_type.id')
            ->select('common_type.name', DB::raw('SUM(orders_items.subtotal) as revenue'))
            ->groupBy('common_type.name')
            ->orderByDesc('revenue')
            ->get();

        // 4. Sales by Brand (Merk)
        $brandSales = OrderItemsModel::join('products', 'orders_items.product_id', '=', 'products.id')
            ->join('common_merk', 'products.merk_id', '=', 'common_merk.id')
            ->select('common_merk.name', DB::raw('SUM(orders_items.subtotal) as revenue'))
            ->groupBy('common_merk.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // 5. Monthly Sales Trend (Last 12 Months)
        $monthlyTrend = OrderItemsModel::select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
                DB::raw('SUM(subtotal) as revenue'),
                DB::raw('COUNT(id) as orders'),
                DB::raw('MIN(created_at) as sort_date')
            )
            ->groupBy('month')
            ->orderBy('sort_date', 'asc')
            ->get();

        // 6. Top Moving Products (by Quantity)
        $topMovingProducts = OrderItemsModel::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return view('products.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalStockValue',
            'totalUnitsSold',
            'topRevenueProducts',
            'categorySales',
            'brandSales',
            'monthlyTrend',
            'topMovingProducts'
        ));
    }
}
