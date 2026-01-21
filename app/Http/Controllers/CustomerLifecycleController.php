<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerLifecycleController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $customers = Customer::where('type', 'customer')->with(['orders' => function($query) {
            $query->where('delivery_status', 'completed');
        }])->get();
        
        // Initialize segment data
        $segments = [
            'Acquisition' => ['customers' => [], 'total_revenue' => 0, 'total_frequency' => 0],
            'Activation' => ['customers' => [], 'total_revenue' => 0, 'total_frequency' => 0],
            'Growth' => ['customers' => [], 'total_revenue' => 0, 'total_frequency' => 0],
            'Loyalty' => ['customers' => [], 'total_revenue' => 0, 'total_frequency' => 0],
            'At Risk' => ['customers' => [], 'total_revenue' => 0, 'total_frequency' => 0],
        ];

        $totalCustomers = $customers->count();
        $activeCustomers = 0;
        $totalRevenue = 0;
        $churnedCustomers = 0;

        // Process each customer
        foreach ($customers as $customer) {
            $orders = $customer->orders;
            $orderCount = $orders->count();
            $clv = $orders->sum('total_amount');
            $lastOrder = $orders->sortByDesc('created_at')->first();
            $daysSinceLastOrder = $lastOrder ? Carbon::parse($lastOrder->created_at)->diffInDays($now) : 999;
            
            // Determine if active (transacted in last 60 days)
            $isActive = $daysSinceLastOrder <= 60;
            if ($isActive) {
                $activeCustomers++;
            }

            $totalRevenue += $clv;

            // Segment classification
            $segment = '';
            $churnRisk = 0;

            if ($daysSinceLastOrder > 60) {
                // At Risk/Churn: No transaction in last 60 days
                $segment = 'At Risk';
                $churnRisk = 90;
                $churnedCustomers++;
            } elseif ($orderCount == 1) {
                // Acquisition: 1 purchase
                $segment = 'Acquisition';
                $churnRisk = 60;
            } elseif ($orderCount == 2) {
                // Activation: 2 purchases
                $segment = 'Activation';
                $churnRisk = 40;
            } elseif ($orderCount >= 3 && $orderCount <= 7) {
                // Growth: 3-7 purchases
                $segment = 'Growth';
                $churnRisk = 25;
            } elseif ($orderCount > 7 && $isActive) {
                // Loyalty: >7 purchases AND active
                $segment = 'Loyalty';
                $churnRisk = 10;
            } else {
                // Fallback (shouldn't happen with current logic)
                $segment = 'At Risk';
                $churnRisk = 80;
            }

            // Add to segment
            $segments[$segment]['customers'][] = [
                'customer' => $customer,
                'order_count' => $orderCount,
                'clv' => $clv,
                'last_order_days' => $daysSinceLastOrder,
                'churn_risk' => $churnRisk,
                'top_products' => $this->getTopProducts($orders),
            ];
            $segments[$segment]['total_revenue'] += $clv;
            $segments[$segment]['total_frequency'] += $orderCount;
        }

        // Calculate segment metrics
        $segmentMetrics = [];
        foreach ($segments as $name => $data) {
            $count = count($data['customers']);
            $segmentMetrics[$name] = [
                'count' => $count,
                'percentage' => $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0,
                'avg_clv' => $count > 0 ? $data['total_revenue'] / $count : 0,
                'avg_frequency' => $count > 0 ? $data['total_frequency'] / $count : 0,
                'total_revenue' => $data['total_revenue'],
                'customers' => $data['customers'],
            ];
        }

        // Calculate KPIs
        $churnRate = $totalCustomers > 0 ? round(($churnedCustomers / $totalCustomers) * 100, 1) : 0;
        $avgClv = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
        $avgFrequency = $totalCustomers > 0 ? Order::where('delivery_status', 'completed')->count() / $totalCustomers : 0;

        // Generate insights
        $insights = $this->generateInsights($segmentMetrics, $totalCustomers);
        
        // Generate recommendations
        $recommendations = $this->generateRecommendations($segmentMetrics);

        return view('dashboard.lifecycle', compact(
            'segmentMetrics',
            'totalCustomers',
            'activeCustomers',
            'totalRevenue',
            'churnRate',
            'avgClv',
            'avgFrequency',
            'insights',
            'recommendations'
        ));
    }

    private function getTopProducts($orders)
    {
        $products = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $productName = $item->product->name ?? 'Unknown';
                if (!isset($products[$productName])) {
                    $products[$productName] = 0;
                }
                $products[$productName] += $item->qty;
            }
        }
        arsort($products);
        return array_slice($products, 0, 3, true);
    }

    private function generateInsights($segmentMetrics, $totalCustomers)
    {
        $insights = [];

        // Conversion drop analysis
        $acquisitionCount = $segmentMetrics['Acquisition']['count'];
        $activationCount = $segmentMetrics['Activation']['count'];
        if ($acquisitionCount > 0) {
            $conversionRate = round(($activationCount / $acquisitionCount) * 100, 1);
            if ($conversionRate < 30) {
                $insights[] = "⚠️ Drop signifikan dari Acquisition ke Activation ({$conversionRate}%). Perlu strategi onboarding lebih baik.";
            }
        }

        // Most valuable segment
        $maxRevenue = 0;
        $topSegment = '';
        foreach ($segmentMetrics as $name => $data) {
            if ($data['total_revenue'] > $maxRevenue) {
                $maxRevenue = $data['total_revenue'];
                $topSegment = $name;
            }
        }
        if ($topSegment) {
            $insights[] = "💰 Segmen {$topSegment} paling kontributif dengan total revenue Rp " . number_format($maxRevenue, 0, ',', '.');
        }

        // Churn risk
        $atRiskCount = $segmentMetrics['At Risk']['count'];
        $atRiskPercentage = $segmentMetrics['At Risk']['percentage'];
        if ($atRiskPercentage > 30) {
            $insights[] = "🚨 {$atRiskPercentage}% customer berisiko churn ({$atRiskCount} customer). Butuh program retensi segera!";
        }

        // Growth opportunity
        $growthCount = $segmentMetrics['Growth']['count'];
        if ($growthCount > 0) {
            $insights[] = "📈 {$growthCount} customer di segmen Growth siap untuk upselling dan cross-selling.";
        }

        return $insights;
    }

    private function generateRecommendations($segmentMetrics)
    {
        return [
            'Acquisition' => [
                'marketing' => 'Welcome email series, onboarding tutorial, first purchase discount',
                'sales' => 'Personal follow-up call, product recommendation based on first purchase',
                'product' => 'Highlight best-sellers, bundle deals untuk repeat purchase',
            ],
            'Activation' => [
                'marketing' => 'Loyalty program introduction, exclusive offers untuk pembelian ke-3',
                'sales' => 'Cross-sell produk komplementer, feedback survey',
                'product' => 'Product education content, usage tips',
            ],
            'Growth' => [
                'marketing' => 'VIP tier upgrade, referral program incentives',
                'sales' => 'Upsell premium products, volume discount offers',
                'product' => 'New product previews, beta testing invitation',
            ],
            'Loyalty' => [
                'marketing' => 'Brand ambassador program, exclusive events',
                'sales' => 'Dedicated account manager, custom solutions',
                'product' => 'Co-creation opportunities, early access to launches',
            ],
            'At Risk' => [
                'marketing' => 'Win-back campaign, special comeback offers',
                'sales' => 'Urgent outreach call, understand pain points',
                'product' => 'Survey untuk product improvement, alternative solutions',
            ],
        ];
    }
}
