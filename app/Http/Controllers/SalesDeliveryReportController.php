<?php

namespace App\Http\Controllers;

use App\Models\DeliverySchedule;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class SalesDeliveryReportController extends Controller
{
    public function index()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('reports.delivery', compact('employees'));
    }

    public function datatables(Request $request)
    {
        $query = DeliverySchedule::with(['order.customer', 'employee']);

        if ($request->filled('start_date')) {
            $query->whereDate('delivery_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('delivery_date', '<=', $request->end_date);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        return DataTables::of($query)
            ->addColumn('invoice_no', fn($s) => $s->order->invoice_no ?? 'N/A')
            ->addColumn('customer_name', fn($s) => $s->order->customer->name ?? 'N/A')
            ->addColumn('employee_name', fn($s) => $s->employee->name ?? 'N/A')
            ->editColumn('delivery_date', fn($s) => Carbon::parse($s->delivery_date)->format('d M Y'))
            ->editColumn('arrival_date', fn($s) => $s->arrival_date ? Carbon::parse($s->arrival_date)->format('d M Y') : '-')
            ->editColumn('status', fn($s) => ucfirst($s->status))
            ->make(true);
    }

    public function exportExcel(Request $request)
    {
        $query = DeliverySchedule::with(['order.customer', 'employee']);

        if ($request->filled('start_date')) {
            $query->whereDate('delivery_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('delivery_date', '<=', $request->end_date);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $schedules = $query->orderBy('delivery_date', 'desc')->get();
        $filename = "delivery_report_" . date('Ymd_His') . ".xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SalesDeliveryExport($schedules), $filename);
    }

    public function exportPdf(Request $request)
    {
        $query = DeliverySchedule::with(['order.customer', 'employee']);

        if ($request->filled('start_date')) {
            $query->whereDate('delivery_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('delivery_date', '<=', $request->end_date);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $schedules = $query->orderBy('delivery_date', 'desc')->get();
        $title = "Sales Delivery Report";
        $date_range = ($request->start_date ?? 'All') . ' - ' . ($request->end_date ?? 'All');
        
        $employee_name = 'All Employees';
        if ($request->filled('employee_id')) {
            $employee = Employee::find($request->employee_id);
            if ($employee) $employee_name = $employee->name;
        }

        return view('reports.delivery_pdf', compact('schedules', 'title', 'date_range', 'employee_name'));
    }
}
