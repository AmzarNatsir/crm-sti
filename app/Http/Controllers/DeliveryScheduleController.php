<?php

namespace App\Http\Controllers;

use App\Models\DeliverySchedule;
use App\Models\Order;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeliveryScheduleController extends Controller
{
    public function index()
    {
        $employees = \App\Models\Employee::where('status', 'active')->orderBy('name')->get();
        $events = DeliverySchedule::with(['order.customer', 'personnel', 'employee'])
            ->get()
            ->map(function ($schedule) {
                $statusText = strtoupper($schedule->status);
                // Fallback to single employee if no personnel relation (migrations/compat)
                if ($schedule->personnel->count() > 0) {
                    $employeeNames = $schedule->personnel->pluck('name')->join(', ');
                } else {
                    $employeeNames = $schedule->employee->name ?? 'No Staff';
                }
                
                $color = '#3b82f6'; // Default Blue (Submitted/Open)
                if ($schedule->status === 'approved') {
                    $color = '#ef4444'; // Red
                } elseif ($schedule->status === 'completed') {
                    $color = '#10b981'; // Green
                }

                $editable = $schedule->status === 'submitted'; // Only editable if submitted

                return [
                    'id' => $schedule->id,
                    'title' => "[{$statusText}] {$employeeNames}\n" . ($schedule->order->customer->name ?? 'N/A') . ' - ' . $schedule->order->invoice_no,
                    'start' => $schedule->delivery_date,
                    'allDay' => true,
                    'color' => $color,
                    'editable' => $editable,
                    'extendedProps' => [
                        'status' => $schedule->status,
                        'personnel' => $employeeNames,
                        'personnel_ids' => $schedule->personnel->pluck('id')->toArray(),
                        'invoice_no' => $schedule->order->invoice_no,
                        'customer' => $schedule->order->customer->name ?? 'N/A',
                        'total_amount' => number_format($schedule->order->total_amount, 2),
                        'total_items' => $schedule->order->items->sum('qty'),
                        'invoice_date' => $schedule->order->invoice_date->format('Y-m-d'),
                        'delivery_date' => Carbon::parse($schedule->delivery_date)->format('Y-m-d'), 
                        'display_delivery_date' => Carbon::parse($schedule->delivery_date)->format('d M Y'),
                        'arrival_date' => $schedule->arrival_date ? Carbon::parse($schedule->arrival_date)->format('Y-m-d') : '',
                    ]
                ];
            });

        return view('delivery-schedule.index', compact('events', 'employees'));
    }

    public function getInvoices(Request $request)
    {
        $query = Order::with(['customer', 'items'])
            ->where('delivery_status', 'open')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('delivery_schedules')
                    ->whereRaw('delivery_schedules.order_id = orders.id');
            });

        if ($request->filled('invoice_date')) {
            $query->whereDate('invoice_date', $request->invoice_date);
        }

        $invoices = $query->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'invoice_date' => $invoice->invoice_date->format('Y-m-d'),
                    'total_amount' => $invoice->total_amount,
                    'customer' => [
                        'name' => $invoice->customer->name ?? 'N/A'
                    ],
                    'total_items' => $invoice->items->sum('qty')
                ];
            });
        
        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_date' => 'required|date',
            'employee_id' => 'required|array', // Changed to array
            'employee_id.*' => 'exists:employees,id',
        ]);

        try {
            DB::beginTransaction();

            $request->validate([
                'order_id' => 'required',
                'delivery_date' => 'required|date',
                'employee_id' => 'required|array',
            ]);

            $order = Order::findOrFail($request->order_id);
            if (Carbon::parse($request->delivery_date)->lt($order->invoice_date->startOfDay())) {
                return response()->json(['success' => false, 'message' => 'Delivery date cannot be before invoice date (' . $order->invoice_date->format('d M Y') . ')'], 422);
            }

            $schedule = DeliverySchedule::create([
                'order_id' => $request->order_id,
                'delivery_date' => $request->delivery_date,
                'employee_id' => $request->employee_id[0] ?? null, 
                'user_id' => auth()->id(),
                'status' => 'submitted' // Initial status
            ]);

            $schedule->personnel()->attach($request->employee_id);
            
            // Create Approval Request
            $schedule->approval()->create([
                'category' => 'delivery_schedule',
                'status' => 'pending',
                'requester_id' => auth()->id(),
            ]);

            $order = Order::find($request->order_id);
            $employeeNames = \App\Models\Employee::whereIn('id', $request->employee_id)->pluck('name')->join(', ');
            
            Activity::create([
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'type' => 'schedule_delivery_open',
                'status' => 'Completed',
                'notes' => "Delivery scheduled for invoice {$order->invoice_no} on " . Carbon::parse($request->delivery_date)->format('d M Y') . ". Delivery Personnel: {$employeeNames}"
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $schedule = DeliverySchedule::findOrFail($id);
            $order = Order::find($schedule->order_id);

            // Logic for 'approved' status: Only allow setting arrival_date (Completion)
            if ($schedule->status === 'approved') {
                 if ($request->has('arrival_date') && !empty($request->arrival_date)) {
                    if (Carbon::parse($request->arrival_date)->lt($order->invoice_date->startOfDay())) {
                        return response()->json(['success' => false, 'message' => 'Arrival date cannot be before invoice date (' . $order->invoice_date->format('d M Y') . ')'], 422);
                    }

                    $schedule->update([
                        'arrival_date' => $request->arrival_date,
                        'status' => 'completed'
                    ]);
    
                    $order->update(['delivery_status' => 'completed']);
    
                    Activity::create([
                        'customer_id' => $order->customer_id,
                        'user_id' => auth()->id(),
                        'type' => 'schedule_delivery_completed',
                        'status' => 'Completed',
                        'notes' => "Delivery completed for invoice {$order->invoice_no}. Arrived on " . Carbon::parse($request->arrival_date)->format('d M Y')
                    ]);
                 } else {
                     // Attempting to modify other fields or dragging while approved
                     DB::rollBack();
                     return response()->json(['success' => false, 'message' => 'Approved schedule cannot be modified except for arrival date.'], 403);
                 }
            } elseif ($schedule->status === 'submitted') {
                // Allowed to modify everything if submitted
                if ($request->has('delivery_date') && !empty($request->delivery_date)) {
                    if (Carbon::parse($request->delivery_date)->lt($order->invoice_date->startOfDay())) {
                        return response()->json(['success' => false, 'message' => 'Delivery date cannot be before invoice date (' . $order->invoice_date->format('d M Y') . ')'], 422);
                    }
                    $schedule->update([
                        'delivery_date' => $request->delivery_date
                    ]);
                }
    
                if ($request->has('employee_id')) {
                    $schedule->personnel()->sync($request->employee_id);
                     if (count($request->employee_id) > 0) {
                         $schedule->update(['employee_id' => $request->employee_id[0]]);
                     }
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $schedule = DeliverySchedule::findOrFail($id);
        
        if ($schedule->status !== 'open') {
            return response()->json(['success' => false, 'message' => 'Only open schedules can be deleted.'], 403);
        }

        $schedule->delete();
        return response()->json(['success' => true]);
    }
}
