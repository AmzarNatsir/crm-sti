<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\Customer;
use Carbon\Carbon;

class FollowupController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // 1. Scheduled Follow-ups
        $scheduledFollowups = Activity::whereNotNull('follow_up_date')
            ->where('status', '!=', 'completed')
            ->whereDate('follow_up_date', '<=', $today->addDays(7))
            ->with(['customer', 'user'])
            ->orderBy('follow_up_date', 'asc')
            ->get();

        // 2. Prospects in Follow-up Status
        $followupProspects = Customer::where('type', 'prospect')
            ->where('status', 'followup')
            ->with(['creator', 'commodity'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // 3. Post-Purchase Follow-up
        $sevenDaysAgo = Carbon::today()->subDays(7);
        $tenDaysAgo = Carbon::today()->subDays(10);
        
        $postPurchaseCustomers = Customer::whereHas('orders', function($q) use ($sevenDaysAgo, $tenDaysAgo) {
            $q->whereBetween('invoice_date', [$tenDaysAgo, $sevenDaysAgo]);
        })
        ->whereDoesntHave('activities', function($q) use ($tenDaysAgo) {
             $q->where('created_at', '>=', $tenDaysAgo);
        })
        ->with(['orders' => function($q) {
            $q->latest('invoice_date');
        }, 'commodity'])
        ->get();

        $postPurchaseCustomers = $postPurchaseCustomers->filter(function($customer) use ($sevenDaysAgo) {
            $latestOrder = $customer->orders->sortByDesc('invoice_date')->first();
            return $latestOrder && $latestOrder->invoice_date <= $sevenDaysAgo;
        });

        return view('followups.index', compact('scheduledFollowups', 'followupProspects', 'postPurchaseCustomers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $customer = Customer::findOrFail($id);
            $oldStatus = $customer->status;
            $customer->status = $request->status;
            if( $request->status == 'customer') {
                $customer->type = 'customer';
            }
            $customer->save();

            // Log activity
            Activity::create([
                'customer_id' => $customer->id,
                'user_id' => auth()->id(),
                'type' => 'status_update',
                'notes' => 'Status changed from ' . $oldStatus . ' to ' . $request->status . '. Notes: ' . $request->notes,
                'status' => 'Completed',
                'follow_up_date' => now()
            ]);

            return redirect()->back()->with('success', 'Follow-up status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
}
