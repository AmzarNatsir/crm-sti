<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RefCompign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class SalesDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 1. KPI Stats
        $totalRevenue = Order::sum('total_amount');
        $totalOrders = Order::count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalCampaigns = RefCompign::count();

        // New Metrics: Monthly Sales
        $monthlyRevenue = Order::whereBetween('invoice_date', [$startOfMonth, $endOfMonth])->sum('total_amount');

        // New Metrics: Deliveries
        $deliveryStats = \App\Models\DeliverySchedule::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('COUNT(id) as total_deliveries'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_deliveries')
            )
            ->first();

        // Completed Items (Total qty from completed delivery schedules this month)
        $completedItems = \App\Models\DeliverySchedule::join('orders', 'delivery_schedules.order_id', '=', 'orders.id')
            ->join('orders_items', 'orders.id', '=', 'orders_items.order_id')
            ->where('delivery_schedules.status', 'completed')
            ->whereBetween('delivery_schedules.arrival_date', [$startOfMonth, $endOfMonth])
            ->sum('orders_items.qty');

        // Delivery SLA (Avg days from scheduled delivery to actual arrival)
        $completedSchedules = \App\Models\DeliverySchedule::where('status', 'completed')
            ->whereNotNull('delivery_date')
            ->whereNotNull('arrival_date')
            ->get();

        $totalSlaDays = 0;
        $slaCount = $completedSchedules->count();
        foreach ($completedSchedules as $schedule) {
            $delivery = Carbon::parse($schedule->delivery_date);
            $arrival = Carbon::parse($schedule->arrival_date);
            $totalSlaDays += $delivery->diffInDays($arrival, false);
        }
        $avgSla = $slaCount > 0 ? round($totalSlaDays / $slaCount, 1) : 0;

        // New Metric: Today's Total Sales
        $todaySales = Order::whereDate('invoice_date', Carbon::today())->sum('total_amount');

        // New Metrics: YoY Sales Comparison
        $currentYear = $now->year;
        $previousYear = $currentYear - 1;

        $currentYearMonthly = Order::select(
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereYear('invoice_date', $currentYear)
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $previousYearMonthly = Order::select(
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereYear('invoice_date', $previousYear)
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $currentYearData = [];
        $previousYearData = [];
        for ($m = 1; $m <= 12; $m++) {
            $currentYearData[] = $currentYearMonthly[$m] ?? 0;
            $previousYearData[] = $previousYearMonthly[$m] ?? 0;
        }

        // 2. Sales Trend (Daily/Monthly)
        $salesTrend = Order::select(
                DB::raw('DATE_FORMAT(invoice_date, "%b %Y") as month'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('MIN(invoice_date) as sort_date')
            )
            ->groupBy('month')
            ->orderBy('sort_date', 'asc')
            ->get();

        // 3. Sales by Salesperson (User) - Top 10
        $salespersonPerformance = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(orders.total_amount) as total_revenue'), DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('users.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // 4. Sales by Campaign
        $campaignPerformance = Order::join('ref_compign', 'orders.compaign_id', '=', 'ref_compign.id')
            ->select('ref_compign.name', DB::raw('SUM(orders.total_amount) as revenue'))
            ->groupBy('ref_compign.name')
            ->orderByDesc('revenue')
            ->get();

        // 5. Payment Status Breakdown
        $paymentStatus = Order::select('payment_status', DB::raw('COUNT(id) as count'))
            ->groupBy('payment_status')
            ->get();

        // 6. Recent Sales
        $recentSales = Order::with(['customer', 'sales'])
            ->orderByDesc('invoice_date')
            ->limit(5)
            ->get();

        return view('sales.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'totalCampaigns',
            'monthlyRevenue',
            'deliveryStats',
            'completedItems',
            'avgSla',
            'todaySales',
            'currentYearData',
            'previousYearData',
            'currentYear',
            'previousYear',
            'salesTrend',
            'salespersonPerformance',
            'campaignPerformance',
            'paymentStatus',
            'recentSales'
        ));
    }
}
