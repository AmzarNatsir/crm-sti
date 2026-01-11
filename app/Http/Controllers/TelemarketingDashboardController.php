<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\SurveyBagianUmum;
use App\Models\SurveyProspekPetani;
use App\Models\SurveyPelangganSTI;
use App\Models\SurveyPenyelesaianMasalah;
use App\Models\SurveyTokoPengecer;
use App\Models\SurveyMitraPengepul;
use App\Models\SurveyKetuaPoktan;
use App\Models\SurveyPenutupRingkasan;
use Illuminate\Support\Facades\DB;

class TelemarketingDashboardController extends Controller
{
    public function index()
    {
        // Real statistics from SurveyBagianUmum and related tables
        $stats = [
            'survey_in' => SurveyBagianUmum::count(),
            'prospect_farmers' => SurveyBagianUmum::where('jenisKontak', 'Farmer Prospect')->count(),
            'active_customers' => SurveyBagianUmum::where('jenisKontak', 'STI Customer')->count(),
            'cases_tickets' => SurveyPenyelesaianMasalah::count(),
            'store_partners' => SurveyBagianUmum::where('jenisKontak', 'Shop/Retailer')->count(),
            'farmer_groups' => SurveyBagianUmum::where('jenisKontak', 'Farmer Group Head')->count(),
        ];

        // Decision Highlights (Aggregate findings)
        $topChallenge = SurveyProspekPetani::select('tantanganUtamaSaatIni', DB::raw('count(*) as count'))
            ->groupBy('tantanganUtamaSaatIni')
            ->orderBy('count', 'desc')
            ->first();
        
        $avgSatisfaction = SurveyPelangganSTI::avg('kepuasanTerhadapProdukLayanan_Nilai');
        
        $decisionHighlights = [
            $topChallenge ? "Tantangan dominan: " . $topChallenge->tantanganUtamaSaatIni : "Belum ada data tantangan dominan",
            "Rata-rata kepuasan pelanggan: " . number_format($avgSatisfaction, 1) . "/10",
            SurveyBagianUmum::where('komoditasUtama', 'Rice')->count() . " survey berasal dari petani Padi (Komoditas Utama)",
            "Total " . $stats['cases_tickets'] . " isu teknis dilaporkan dan butuh penyelesaian",
        ];

        // Prospect Insights (Latest 4)
        $prospectInsights = SurveyProspekPetani::latest()
            ->take(4)
            ->get()
            ->map(fn($p) => "Tantangan: " . Str::limit($p->tantanganUtamaSaatIni, 40) . " | Komitmen: " . $p->komitmenAwal)
            ->toArray();
        
        if (empty($prospectInsights)) {
            $prospectInsights = ['Belum ada data prospek'];
        }

        // Customer Insights (Latest 4)
        $customerInsights = SurveyPelangganSTI::latest()
            ->take(4)
            ->get()
            ->map(fn($c) => "Produk: " . $c->produkStiYangDigunakan_Nama . " | Kepuasan: " . $c->kepuasanTerhadapProdukLayanan_Nilai . "/10")
            ->toArray();
        
        if (empty($customerInsights)) {
            $customerInsights = ['Belum ada data pelanggan'];
        }

        // Problem Solving Insights (Latest 4)
        $problemSolving = SurveyPenyelesaianMasalah::latest()
            ->take(4)
            ->get()
            ->map(fn($s) => "Status: " . $s->statusTiket . " | Masalah: " . Str::limit($s->akarDugaan, 40))
            ->toArray();
        
        if (empty($problemSolving)) {
            $problemSolving = ['Belum ada data penyelesaian masalah'];
        }

        // Partner Insights (Static logic for now, or aggregated if needed)
        $partnerInsights = [
            'store' => SurveyTokoPengecer::latest()->take(3)->pluck('profil_NamaToko')->map(fn($n) => "Toko: " . $n)->toArray() ?: ['Belum ada data toko'],
            'collector' => SurveyMitraPengepul::latest()->take(3)->pluck('profil_NamaUsaha')->map(fn($n) => "Mitra: " . $n)->toArray() ?: ['Belum ada data pengepul'],
            'group' => SurveyKetuaPoktan::latest()->take(3)->pluck('profil_Nama')->map(fn($n) => "Kelompok: " . $n)->toArray() ?: ['Belum ada data kelompok'],
        ];

        // Real Follow-up Priority (Latest 5 commitments)
        $followUps = SurveyPenutupRingkasan::with('surveyBagianUmum')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($f) {
                return [
                    'segment' => $f->surveyBagianUmum->jenisKontak ?? 'General',
                    'issue' => Str::limit($f->ringkasanKebutuhanSolusi, 50),
                    'action' => $f->komitmenTindakLanjut_Apa,
                    'pic' => $f->komitmenTindakLanjut_OlehSiapa,
                    'deadline' => $f->komitmenTindakLanjut_KapanTanggal ? \Carbon\Carbon::parse($f->komitmenTindakLanjut_KapanTanggal)->diffForHumans() : 'N/A',
                ];
            })->toArray();

        return view('dashboard.telemarketing', compact(
            'stats',
            'decisionHighlights',
            'prospectInsights',
            'customerInsights',
            'problemSolving',
            'partnerInsights',
            'followUps'
        ));
    }
}
