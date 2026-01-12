<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index()
    {
        return view('reports.sales');
    }

    public function datatables(Request $request)
    {
        $query = Order::with(['customer', 'paymentMethod', 'campaign']);

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        return DataTables::of($query)
            ->addColumn('customer_name', fn($order) => $order->customer->name ?? 'N/A')
            ->addColumn('payment_method_name', fn($order) => $order->paymentMethod->name ?? 'N/A')
            ->addColumn('campaign_name', fn($order) => $order->campaign->name ?? 'N/A')
            ->editColumn('invoice_date', fn($order) => $order->invoice_date->format('d M Y'))
            ->editColumn('total_amount', fn($order) => number_format($order->total_amount, 2))
            ->editColumn('invoice_discount', fn($order) => number_format($order->invoice_discount, 2))
            ->addColumn('action', function($order) {
                return '<button type="button" class="btn btn-sm btn-info view-detail" data-id="' . $order->id . '"><i class="ti ti-eye"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'paymentMethod', 'campaign', 'items.product'])->findOrFail($id);
        return view('reports.partials.sales_detail', compact('order'));
    }

    public function exportExcel(Request $request)
    {
        $query = Order::with(['customer', 'paymentMethod', 'campaign']);

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        $orders = $query->orderBy('invoice_date', 'desc')->get();
        $filename = "sales_report_" . date('Ymd_His') . ".xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SalesExport($orders, $request->start_date, $request->end_date), 
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $query = Order::with(['customer', 'paymentMethod', 'campaign']);

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        $orders = $query->orderBy('invoice_date', 'desc')->get();
        $title = "Sales Report";
        $date_range = $request->start_date . ' - ' . $request->end_date;

        return view('reports.sales_pdf', compact('orders', 'title', 'date_range'));
    }
}
