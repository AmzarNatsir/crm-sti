<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerLoyaltyController extends Controller
{
    public function index()
    {
        $customers = Customer::with('commodity')->where('type', 'customer')->get();
        // Pre-load orders with items and products to avoid N+1 issues
        // We still need orders for RFM calculation
        
        $results = [];
        $now = Carbon::now();

        foreach ($customers as $customer) {
            // Get all orders for this customer to determine Last Purchase (Recency)
            $allOrders = Order::where('customer_id', $customer->id)
                ->where('delivery_status', '!=', 'cancelled')
                ->orderBy('created_at', 'desc')
                ->get();

            // 1. Recency & Last Purchase Date
            $lastOrder = $allOrders->first();
            $lastPurchaseDate = $lastOrder ? Carbon::parse($lastOrder->created_at)->format('Y-m-d') : '-';
            $recencyDays = $lastOrder ? Carbon::parse($lastOrder->created_at)->diffInDays($now) : 999;

            // 2. 12-Month Metrics (Orders_12mo, Spend_12mo)
            $oneYearAgo = $now->copy()->subYear();
            $orders12mo = $allOrders->filter(function ($order) use ($oneYearAgo) {
                return Carbon::parse($order->created_at)->gte($oneYearAgo);
            });

            $countOrders12mo = $orders12mo->count(); // Frequency (based on 12mo as per column name 'Orders_12mo')
            $spend12mo = $orders12mo->sum('total_amount'); // Monetary (based on 12mo)

            // 3. Determine Commodity (Komoditas) logic: From Customer Master Data
            $primaryCommodity = $customer->commodity ? $customer->commodity->name : '-';

            // 4. Calculate Scores (1-5)
            // Recency Score (Assuming lower recency days is better)
            if ($recencyDays <= 30) $r_score = 5;
            elseif ($recencyDays <= 60) $r_score = 4;
            elseif ($recencyDays <= 90) $r_score = 3;
            elseif ($recencyDays <= 180) $r_score = 2;
            else $r_score = 1;

            // Frequency Score (Based on Orders_12mo)
            if ($countOrders12mo >= 10) $f_score = 5;
            elseif ($countOrders12mo >= 7) $f_score = 4;
            elseif ($countOrders12mo >= 5) $f_score = 3; // Adjusted to ensure coverage
            elseif ($countOrders12mo >= 2) $f_score = 2;
            else $f_score = 1;

            // Monetary Score (Based on Spend_12mo)
            if ($spend12mo >= 20000000) $m_score = 5;
            elseif ($spend12mo >= 10000000) $m_score = 4;
            elseif ($spend12mo >= 5000000) $m_score = 3;
            elseif ($spend12mo >= 1000000) $m_score = 2;
            else $m_score = 1;

            // 5. Segmentation Logic (Based on Diagram)
            // Champions: Height R, F, M (>=3)
            // Loyal: High R and F (R>=3, F>=3)
            // Big Spenders: High R and M (R>=3, M>=3)
            // Promising: High R only (R>=3)
            // Potential Loyal: High F or M (R<3, F>=3 OR M>=3)
            // At Risk: Low on all (R<3, F<3, M<3)

            $category = 'At Risk'; // Default
            $rfm_code = 'R' . $r_score . 'F' . $f_score . 'M' . $m_score;

            if ($r_score >= 3 && $f_score >= 3 && $m_score >= 3) {
                $category = 'Champions';
            } elseif ($r_score >= 3 && $f_score >= 3) {
                $category = 'Loyal';
            } elseif ($r_score >= 3 && $m_score >= 3) {
                $category = 'Big Spenders';
            } elseif ($r_score >= 3) {
                $category = 'Promising';
            } elseif ($r_score < 3 && ($f_score >= 3 || $m_score >= 3)) {
                $category = 'Potential Loyal';
            } else {
                $category = 'At Risk';
            }

            $results[] = (object) [
                'customer_id' => 'CUST-' . str_pad($customer->id, 4, '0', STR_PAD_LEFT), // Generating ID format like image
                'customer_name' => $customer->name,
                'komoditas' => $primaryCommodity,
                'last_purchase_date' => $lastPurchaseDate,
                'orders_12mo' => $countOrders12mo,
                'spend_12mo' => $spend12mo,
                'recency_days' => $recencyDays,
                'frequency' => $countOrders12mo, // Same as orders_12mo
                'monetary' => $spend12mo, // Same as spend_12mo
                'r_score' => $r_score,
                'f_score' => $f_score,
                'm_score' => $m_score,
                'rfm_code' => $rfm_code,
                'category' => $category
            ];
        }

        // Sort by Category Priority
        $categoryPriority = [
            'Champions' => 6,
            'Loyal' => 5,
            'Big Spenders' => 4,
            'Potential Loyal' => 3,
            'Promising' => 2,
            'At Risk' => 1,
        ];

        usort($results, function($a, $b) use ($categoryPriority) {
            $scoreA = $categoryPriority[$a->category] ?? 0;
            $scoreB = $categoryPriority[$b->category] ?? 0;
            
            if ($scoreA === $scoreB) {
                // Secondary sort by Recency (lower is better/more recent)
                // Just to keep it deterministic within categories
                return $a->recency_days <=> $b->recency_days; 
            }
            
            return $scoreB <=> $scoreA; // Descending order of priority
        });

        return view('dashboard.loyalty', compact('results'));
    }
}
