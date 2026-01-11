<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        return view('approvals.index');
    }

    public function datatables(Request $request)
    {
        $approvals = Approval::with([
            'requester',
            'approvable' => function($query) {
                // Morph with relations
                $query->with(['order.customer', 'personnel']);
            }
        ])
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc');

        return datatables()->of($approvals)
            ->addIndexColumn()
            ->addColumn('requester_name', function ($approval) {
                return $approval->requester->name ?? '-';
            })
            ->addColumn('created_at', function ($approval) {
                return $approval->created_at->format('d M Y H:i');
            })
            ->addColumn('details', function ($approval) {
                if ($approval->approvable_type === 'App\Models\DeliverySchedule') {
                    $schedule = $approval->approvable;
                    $order = $schedule->order;
                    
                    if (!$order) return "Delivery Schedule Details Missing";

                    $staff = $schedule->personnel->pluck('name')->join(', ');
                    if (empty($staff)) {
                        $staff = $schedule->employee->name ?? 'None';
                    }

                    $html = "<strong>Invoice:</strong> {$order->invoice_no}<br>";
                    $html .= "<strong>Customer:</strong> " . ($order->customer->name ?? 'N/A') . "<br>";
                    $html .= "<strong>Delivery Date:</strong> " . date('d M Y', strtotime($schedule->delivery_date)) . "<br>";
                    $html .= "<strong>Staff:</strong> {$staff}";

                    return $html;
                }
                return '-';
            })
            ->addColumn('action', function ($approval) {
                $btn = '<button onclick="approve(' . $approval->id . ')" class="btn btn-success btn-sm me-1" title="Approve"><i class="ti ti-check"></i></button>';
                // $btn .= '<button onclick="reject(' . $approval->id . ')" class="btn btn-danger btn-sm" title="Reject"><i class="ti ti-x"></i></button>';
                return $btn;
            })
            ->rawColumns(['action', 'details'])
            ->make(true);
    }

    public function action(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $approval = Approval::findOrFail($id);
            
            if ($approval->status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Approval is already processed.'], 400);
            }

            if ($request->action === 'approve') {
                $approval->status = 'approved';
                $approval->approver_id = auth()->id();
                $approval->notes = $request->notes;
                $approval->save();

                // Logic based on approvable type
                if ($approval->approvable_type === 'App\Models\DeliverySchedule') {
                    $schedule = $approval->approvable;
                    $schedule->update(['status' => 'approved']);
                    
                    Activity::create([
                        'customer_id' => $schedule->order->customer_id,
                        'user_id' => auth()->id(),
                        'type' => 'schedule_delivery_approved',
                        'status' => 'Approved',
                        'notes' => "Delivery schedule approved for invoice {$schedule->order->invoice_no}. Note: " . ($request->notes ?? '-')
                    ]);
                }

            } else {
                $approval->status = 'rejected';
                $approval->approver_id = auth()->id();
                $approval->notes = $request->notes;
                $approval->save();

                 if ($approval->approvable_type === 'App\Models\DeliverySchedule') {
                     $schedule = $approval->approvable;
                     // Keep as submitted but log rejection
                     Activity::create([
                        'customer_id' => $schedule->order->customer_id,
                        'user_id' => auth()->id(),
                        'type' => 'schedule_delivery_rejected',
                        'status' => 'Rejected',
                        'notes' => "Delivery schedule rejected for invoice {$schedule->order->invoice_no}. Reason: " . ($request->notes ?? '-')
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
