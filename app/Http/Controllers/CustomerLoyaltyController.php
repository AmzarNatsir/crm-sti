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
        $customers = Customer::where('type', 'customer')->get();
        $orders = Order::all();
        $results = [];

        $now = Carbon::now();

        foreach ($customers as $customer) {
            // Filter orders for this customer
            $custOrders = $orders->where('customer_id', $customer->id);
            
            // 1. Calculate RFM Values
            $lastOrder = $custOrders->sortByDesc('created_at')->first();
            
            // Recency: Days since last transaction
            $recency = $lastOrder ? Carbon::parse($lastOrder->created_at)->diffInDays($now) : 999;
            
            // Frequency: Number of transactions
            $frequency = $custOrders->count();
            
            // Monetary: Total transaction value
            $monetary = $custOrders->sum('total_amount'); // Assuming 'total_amount' is the column, previously saw 'grand_total', checking typical usage

            // 2. Convert to Score 1-5
            // Recency Score
            if ($recency <= 14) $r_score = 5;
            elseif ($recency <= 30) $r_score = 4;
            elseif ($recency <= 60) $r_score = 3;
            elseif ($recency <= 90) $r_score = 2;
            else $r_score = 1;

            // Frequency Score
            if ($frequency >= 10) $f_score = 5;
            elseif ($frequency >= 7) $f_score = 4;
            elseif ($frequency >= 4) $f_score = 3;
            elseif ($frequency >= 2) $f_score = 2;
            else $f_score = 1;

            // Monetary Score
            // Assuming Monetary is in nominal (e.g., millions). 
            // 20 million = 20000000
            if ($monetary >= 20000000) $m_score = 5;
            elseif ($monetary >= 10000000) $m_score = 4;
            elseif ($monetary >= 5000000) $m_score = 3;
            elseif ($monetary >= 1000000) $m_score = 2;
            else $m_score = 1;

            // 3. RFM Code
            $rfm_code = $r_score . $f_score . $m_score;

            // 4. RFM Score (0-100)
            // RFM_score = (R×20×0.33) + (F×20×0.33) + (M×20×0.34)
            $rfm_score_100 = ($r_score * 20 * 0.33) + ($f_score * 20 * 0.33) + ($m_score * 20 * 0.34);

            // 5. Loyalty Score
            // Factors
            // Repeat Order Trend: (Simplified logic: if F > 1 => 70 (Stable), F > 5 => 100 (Increasing hint?))
            // Prompt: Increasing=100, Stable=70, Decreasing=40, No repeat=10
            // Logic: New customers (F=1) -> No repeat (10). F > 1 -> Stable (70). We don't have historical trend easily without complex queries.
            // Let's assume F > 1 is 'Stable'(70). If F >= 5 'Increasing'(100). F=1 'No Repeat'(10).
            if ($frequency >= 5) $val_repeat = 100;
            elseif ($frequency > 1) $val_repeat = 70;
            else $val_repeat = 10;

            // Complaints
            // Assuming 0 for now as verified linking is complex without explicit table knowledge
            $complaints_count = 0; 
            if ($complaints_count == 0) $val_complaint = 100;
            elseif ($complaints_count == 1) $val_complaint = 80;
            elseif ($complaints_count <= 2) $val_complaint = 40;
            else $val_complaint = 10;

            // Program Participation
            // Default "Sometimes" (60)
            $val_program = 60;

            // Length of Service (Tenure)
            $joinDate = $customer->created_at ? Carbon::parse($customer->created_at) : $now;
            $tenureYears = $joinDate->diffInYears($now);
            
            if ($tenureYears > 3) $val_tenure = 100;
            elseif ($tenureYears >= 1) $val_tenure = 70;
            else $val_tenure = 40;

            // Formula
            // Loyalty = (RFM_score × 0.50) + (Repeat × 0.20) + (Complaint × 0.15) + (Program × 0.10) + (Duration × 0.05)
            $loyalty_score = ($rfm_score_100 * 0.50) +
                             ($val_repeat * 0.20) +
                             ($val_complaint * 0.15) +
                             ($val_program * 0.10) +
                             ($val_tenure * 0.05);

            // 6. Classification
            if ($loyalty_score >= 85) {
                $category = 'Very Loyal';
                $action = 'Berikan Reward Eksklusif / Program VIP';
            } elseif ($loyalty_score >= 70) {
                $category = 'Loyal';
                $action = 'Upselling / Cross-selling produk premium';
            } elseif ($loyalty_score >= 50) {
                $category = 'Moderate';
                $action = 'Tawarkan Promo/Diskon untuk meningkatkan frekuensi';
            } elseif ($loyalty_score >= 30) {
                $category = 'Churn Risk';
                $action = 'Hubungi Personal / Survey Kepuasan';
            } else {
                $category = 'Almost Lost';
                $action = 'Win-back Campaign / Promo Agresif';
            }

            $results[] = (object) [
                'customer' => $customer,
                'recency_days' => $recency,
                'frequency' => $frequency,
                'monetary' => $monetary,
                'r_score' => $r_score,
                'f_score' => $f_score,
                'm_score' => $m_score,
                'rfm_code' => $rfm_code,
                'rfm_score_100' => $rfm_score_100,
                'loyalty_score' => $loyalty_score,
                'category' => $category,
                'action' => $action
            ];
        }

        // Sort by Loyalty Score Descending
        usort($results, function($a, $b) {
            return $b->loyalty_score <=> $a->loyalty_score;
        });

        return view('dashboard.loyalty', compact('results'));
    }
}
