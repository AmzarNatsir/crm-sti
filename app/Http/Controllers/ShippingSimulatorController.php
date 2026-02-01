<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\ParamWilayah;
use App\Models\KoefMedan;

class ShippingSimulatorController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'province_id' => 'required',
            'customer_type' => 'required|in:Retail,Reseller',
            'nilai_faktur' => 'nullable|numeric',
            'w' => 'nullable|numeric',
            'cap' => 'nullable|numeric',
            'subsidi' => 'nullable|numeric',
            'kr' => 'nullable|numeric',
            'kw' => 'nullable|numeric',
            'kp' => 'nullable|numeric',
            'd' => 'nullable|numeric',
            't' => 'nullable|numeric',
            'kode_medan' => 'nullable|string',
        ]);

        $provinceId = $request->province_id;
        $customerType = $request->customer_type;

        // Fetch Regional Parameters
        $param = ParamWilayah::where('province_id', $provinceId)->first();
        
        $ckm_z = $param ? $param->ckm : 0;
        $ct_z = $param ? $param->ct : 0;
        $tarif_min = $param ? $param->tarif_min : 0;
        $alpha_max_percent = 0;
        if ($param) {
            $alpha_max_percent = ($customerType === 'Retail') ? $param->alpha_max_retail : $param->alpha_max_reseller;
        }

        // Fetch Medan Coefficient
        $koefMedan = KoefMedan::where('kode_medan', $request->kode_medan)->first();
        $km_medan = $koefMedan ? $koefMedan->km : 0;

        // Inputs
        $w = $request->w ?? 0;
        $cap = $request->cap ?? 1; // avoid division by zero
        $nilai_faktur = $request->nilai_faktur ?? 0;
        $subsidi_percent = $request->subsidi ?? 0;
        $kr = $request->kr ?? 0;
        $kw = $request->kw ?? 0;
        $kp = $request->kp ?? 0;
        $d = $request->d ?? 0;
        $t = $request->t ?? 0;
        
        // Karm Mapping (VLOOKUP JENISARMADA in Excel)
        $armadaKarmMap = [
            'Pickup' => 1.00,
            'Engkel' => 0,
            'Truk_6_Roda' => 1.20,
            'Tronton' => 1.10
        ];
        $karm = $armadaKarmMap[$request->jenis_armada] ?? 0;

        // Calculations
        $fm = $cap > 0 ? ($w / $cap) : 0;
        $alpha_max = $alpha_max_percent; // Assuming it's already decimal as per last task
        $ongkir_maks = $nilai_faktur * $alpha_max;

        // Formula: ONGKIR_RIIL = ( (D * ckm_z) + (T * ct_z) ) * km_medan * fm + kr + kw + kp + karm
        $ongkir_riil = (($d * $ckm_z) + ($t * $ct_z)) * $km_medan * $fm + $kr + $kw + $kp + $karm;
        
        $ongkir_tagih = ceil($ongkir_riil);
        $setelah_subsidi = $ongkir_tagih * (1 - ($subsidi_percent / 100));
        $ongkir_final = max($setelah_subsidi, $tarif_min);

        return response()->json([
            'ckm_z' => $ckm_z,
            'ct_z' => $ct_z,
            'tarif_min' => $tarif_min,
            'alpha_max' => $alpha_max,
            'ongkir_maks' => $ongkir_maks,
            'fm' => $fm,
            'km_medan' => $km_medan,
            'ongkir_riil' => $ongkir_riil,
            'ongkir_tagih' => $ongkir_tagih,
            'setelah_subsidi' => $setelah_subsidi,
            'ongkir_final' => $ongkir_final,
            'karm' => $karm,
        ]);
    }
}
