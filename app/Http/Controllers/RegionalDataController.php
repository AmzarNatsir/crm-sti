<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class RegionalDataController extends Controller
{
    public function index(Request $request)
    {
        $query = Province::query();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $provinces = $query->withCount('regencies')->paginate(10)->withQueryString();
        return view('regional.index', compact('provinces'));
    }

    public function regencies(Request $request, Province $province)
    {
        $query = $province->regencies();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $regencies = $query->withCount('districts')->paginate(10)->withQueryString();
        return view('regional.regencies', compact('province', 'regencies'));
    }

    public function districts(Request $request, Regency $regency)
    {
        $query = $regency->districts();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $districts = $query->withCount('villages')->paginate(10)->withQueryString();
        return view('regional.districts', compact('regency', 'districts'));
    }

    public function villages(Request $request, District $district)
    {
        $query = $district->villages();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $villages = $query->paginate(10)->withQueryString();
        return view('regional.villages', compact('district', 'villages'));
    }

    // JSON API endpoints for forms
    public function getProvinces()
    {
        $provinces = Province::orderBy('name')->get(['id', 'name']);
        return response()->json($provinces);
    }

    public function getRegenciesByProvince($provinceId)
    {
        $regencies = Regency::where('province_id', $provinceId)->orderBy('name')->get(['id', 'name']);
        return response()->json($regencies);
    }

    public function getDistrictsByRegency($regencyId)
    {
        $districts = District::where('regency_id', $regencyId)->orderBy('name')->get(['id', 'name']);
        return response()->json($districts);
    }

    public function getVillagesByDistrict($districtId)
    {
        $villages = Village::where('district_id', $districtId)->orderBy('name')->get(['id', 'name']);
        return response()->json($villages);
    }

    // Modern API methods (aliases or optimized for new routes)
    public function getRegencies(Province $province)
    {
        return response()->json($province->regencies()->orderBy('name')->get(['id', 'name']));
    }

    public function getDistricts(Regency $regency)
    {
        return response()->json($regency->districts()->orderBy('name')->get(['id', 'name']));
    }

    public function getVillages(District $district)
    {
        return response()->json($district->villages()->orderBy('name')->get(['id', 'name']));
    }
}
