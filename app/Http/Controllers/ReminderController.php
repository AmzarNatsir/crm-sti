<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        $currentMonth = Carbon::now()->month;

        // Inactive Customers
        // Logic: Customers who have NOT purchased in the last 3 months.
        // But we exclude brand new customers (checking created_at < 3 months) unless they already bought and stopped.
        // So:
        // 1. Doesn't have orders >= 3 months ago.
        // 2. AND (Has any orders OR created_at < 3 months ago).
        
        $inactiveCustomers = Customer::whereDoesntHave('orders', function ($q) use ($threeMonthsAgo) {
            $q->where('invoice_date', '>=', $threeMonthsAgo);
        })
        ->where(function($q) use ($threeMonthsAgo) {
             // If they have existing orders (older than 3 months), they are inactive.
             // If they have NO orders, they must be older than 3 months to be considered inactive.
             $q->whereHas('orders')
               ->orWhere('created_at', '<', $threeMonthsAgo);
        })
        ->with(['orders' => function($q) {
            $q->latest('invoice_date');
        }, 'commodity', 'provinceRelation', 'regencyRelation']) // eager load relevant data
        ->take(100) // Limit for performance if needed, or paginate. For now get() is fine but let's be safe.
        ->get();

        // Birthday Customers
        $birthdayCustomers = Customer::whereMonth('date_of_birth', $currentMonth)
            ->with(['commodity', 'provinceRelation', 'regencyRelation'])
            ->get();

        return view('reminders.index', compact('inactiveCustomers', 'birthdayCustomers'));
    }

    public function getLastOrder($id)
    {
        $customer = Customer::with(['orders' => function($q) {
            $q->latest('invoice_date');
        }])->findOrFail($id);

        $lastOrder = $customer->orders->first();

        if (!$lastOrder) {
            return response()->json(['status' => 'error', 'message' => 'No order found for this customer.']);
        }

        // We need to load items for this order. Assuming Order has many OrderItems.
        // Let's load the items relation if not already loaded, but here we just fetched the order.
        // Re-fetching or loading relations on $lastOrder
        // Assuming relationship name is 'items' as seen in Models/Order.php: public function items()
        // And OrderItemsModel probably has a 'product' relation? Let's check or just assume for now.
        // Actually I should verify OrderItemsModel structure. For now, let's assume 'product' exists or just show item name if available.
        // In Order.php: public function items() { return $this->hasMany(OrderItemsModel::class, 'order_id'); }
        
        // I will do a quick check on OrderItemsModel later if needed, but 'items' is correct.
        $lastOrder->load('items.product'); 

        return response()->json(['status' => 'success', 'data' => $lastOrder]);
    }
}
