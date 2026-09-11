<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerVisitReportController extends Controller
{
    public function index()
    {
        $surveyors = User::select('id', 'name')->orderBy('name')->get();
        return view('reports.customer_visit_ranking', compact('surveyors'));
    }

    protected function visitFilter(Request $request): \Closure
    {
        return function ($query) use ($request) {
            $query->when($request->filled('start_date'), function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->filled('end_date'), function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    $query->where('status', $request->status);
                })
                ->when($request->filled('surveyor'), function ($query) use ($request) {
                    $query->where('userId', $request->surveyor);
                });
        };
    }

    public function datatables(Request $request)
    {
        $visitFilter = $this->visitFilter($request);

        $query = Customer::query()
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->whereHas('surveys', $visitFilter)
            ->withCount(['surveys as visit_count' => $visitFilter])
            ->withMax(['surveys as last_visit_at' => $visitFilter], 'created_at')
            ->orderByDesc('visit_count')
            ->orderBy('name');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('last_visit_at', function ($customer) {
                return $customer->last_visit_at
                    ? Carbon::parse($customer->last_visit_at)->format('d M Y H:i')
                    : '-';
            })
            ->addColumn('action', function ($customer) {
                return '<button type="button" class="btn btn-icon btn-sm btn-primary btn-view-visits" data-id="' . $customer->id . '" data-name="' . e($customer->name) . '" title="Detail Kunjungan"><i class="ti ti-eye"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportExcel(Request $request)
    {
        $visitFilter = $this->visitFilter($request);

        $customers = Customer::query()
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->whereHas('surveys', $visitFilter)
            ->withCount(['surveys as visit_count' => $visitFilter])
            ->with(['surveys' => function ($query) use ($visitFilter) {
                $visitFilter($query);
                $query->with('penutup')->orderBy('created_at');
            }])
            ->orderByDesc('visit_count')
            ->orderBy('name')
            ->get();

        $filename = 'customer_visit_ranking_' . date('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CustomerVisitRankingExport($customers), $filename);
    }

    public function visits(Customer $customer)
    {
        $visits = $customer->surveys()
            ->with(['user', 'followupUser', 'penutup'])
            ->orderBy('created_at')
            ->get();

        $data = $visits->values()->map(function ($survey, $index) {
            $penutup = $survey->penutup;

            return [
                'no' => $index + 1,
                'uid' => $survey->uid,
                'visit_date' => optional($survey->created_at)->format('d M Y H:i'),
                'status' => $survey->status ?? 'open',
                'jenis_kontak' => $survey->jenisKontak,
                'jabatan' => $survey->jabatan,
                'no_wa' => $survey->noWa,
                'komoditas' => $survey->komoditasUtama,
                'luas_lahan' => $survey->luasLahan,
                'sistem_irigasi' => $survey->sistemIrigasi,
                'alamat' => $survey->alamatLahanUsaha,
                'surveyor' => $survey->user->name ?? '-',
                'followup_user' => $survey->followupUser->name ?? null,
                'evidence' => $survey->evidenceKunjungan ? asset($survey->evidenceKunjungan) : null,
                'penutup' => $penutup ? [
                    'ringkasan_kebutuhan_solusi' => $penutup->ringkasanKebutuhanSolusi,
                    'komitmen_apa' => $penutup->komitmenTindakLanjut_Apa,
                    'komitmen_oleh_siapa' => $penutup->komitmenTindakLanjut_OlehSiapa,
                    'komitmen_kapan_tanggal' => $penutup->komitmenTindakLanjut_KapanTanggal ? Carbon::parse($penutup->komitmenTindakLanjut_KapanTanggal)->format('d M Y') : null,
                    'komitmen_kapan_jam' => $penutup->komitmenTindakLanjut_KapanJam,
                    'followup_tanggal' => $penutup->jadwalFollowup_Tanggal ? Carbon::parse($penutup->jadwalFollowup_Tanggal)->format('d M Y') : null,
                    'followup_jam' => $penutup->jadwalFollowup_Jam,
                    'followup_kanal' => $penutup->jadwalFollowup_Kanal,
                    'dokumentasi' => $penutup->dokumentasi ? asset($penutup->dokumentasi) : null,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'customer_name' => $customer->name,
            'total_visits' => $data->count(),
            'visits' => $data,
        ]);
    }
}
