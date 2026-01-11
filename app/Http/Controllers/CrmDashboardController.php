<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\RefCompign;
use App\Models\RefCommodity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CrmDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Stats
        $totalCustomers = Customer::where('type', 'customer')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();

        // Avg CLTV (Customer Lifetime Value) = Total Revenue / Total Customers
        $totalRevenue = Order::sum('total_amount');
        $avgCLTV = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
        
        // Avg ARPU (Average Revenue Per User - per month)
        // For simplicity, let's take revenue for the current month / customers who ordered this month
        $currentMonthOrders = Order::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        $currentMonthRevenue = $currentMonthOrders->sum('total_amount');
        $currentMonthActiveCustomers = $currentMonthOrders->distinct('customer_id')->count();
        $avgARPU = $currentMonthActiveCustomers > 0 ? $currentMonthRevenue / $currentMonthActiveCustomers : 0;

        // Avg Churn Rate (Mockup logic: customers who haven't ordered in last 90 days vs total)
        $ninetyDaysAgo = Carbon::now()->subDays(90);
        $activeIn90Days = Order::where('created_at', '>=', $ninetyDaysAgo)->distinct('customer_id')->count();
        $churnedCount = max(0, $totalCustomers - $activeIn90Days);
        $churnRate = $totalCustomers > 0 ? ($churnedCount / $totalCustomers) * 100 : 0;

        // 2. ROI - Campaign List
        $campaigns = RefCompign::orderByDesc('roi')->limit(5)->get();

        // 3. Commodity Distribution
        $commodityDistribution = Customer::join('ref_commodity', 'customers.commodity_id', '=', 'ref_commodity.id')
            ->select('ref_commodity.name', DB::raw('count(customers.id) as count'))
            ->groupBy('ref_commodity.name')
            ->get();

        // 4. Customer Area Mapping - Top 5 by Regency (District in our model)
        $topRegencies = Customer::select('district as name', DB::raw('count(id) as count'))
            ->whereNotNull('district')
            ->groupBy('district')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // 5. Customer Area Mapping - Top 5 by Province
        $topProvinces = Customer::select('province as name', DB::raw('count(id) as count'))
            ->whereNotNull('province')
            ->groupBy('province')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('dashboard.crm', compact(
            'totalCustomers',
            'avgCLTV',
            'avgARPU',
            'churnRate',
            'campaigns',
            'commodityDistribution',
            'topRegencies',
            'topProvinces'
        ));
    }
}
