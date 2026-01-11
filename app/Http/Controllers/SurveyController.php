<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Models\SurveyBagianUmum;
use App\Models\SurveyKetuaPoktan;
use App\Models\SurveyMitraPengepul;
use App\Models\SurveyPelangganSTI;
use App\Models\SurveyPenutupRingkasan;
use App\Models\SurveyPenyelesaianMasalah;
use App\Models\SurveyProspekPetani;
use App\Models\SurveyStatistikKonteksPertanian;
use App\Models\SurveyTokoPengecer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SurveyController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::select('id', 'name')->orderBy('name', 'asc')->get();
        $commodities = \App\Models\RefCommodity::all();
        return view('surveys.index', compact('users', 'commodities'));
    }

    public function datatables(Request $request)
    {
        $query = SurveyBagianUmum::with(['contact', 'user'])
            ->select('survey_bagian_umum.*')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('jenisKontak')) {
            $query->where('jenisKontak', $request->jenisKontak);
        }

        if ($request->filled('namaLengkap')) {
            $query->where('namaLengkap', 'like', '%' . $request->namaLengkap . '%');
        }

        if ($request->filled('noIdentity')) {
            $query->where('noIdentity', 'like', '%' . $request->noIdentity . '%');
        }

        if ($request->filled('noWa')) {
            $query->where('noWa', 'like', '%' . $request->noWa . '%');
        }

        if ($request->filled('userId')) {
            $query->where('userId', $request->userId);
        }

        if ($request->filled('commodity')) {
            // Check if input is likely an ID or a name
            if (is_numeric($request->commodity)) {
                $commodity = \App\Models\RefCommodity::find($request->commodity);
                if ($commodity) {
                    $query->where('komoditasUtama', 'like', '%' . $commodity->name . '%');
                }
            } else {
                $query->where('komoditasUtama', 'like', '%' . $request->commodity . '%');
            }
        }

        return DataTables::of($query)
            ->addColumn('surveyor', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('status', function ($row) {
                $status = $row->status ?? 'open';
                $badgeClass = match($status) {
                    'completed' => 'bg-success',
                    'in-progress' => 'bg-warning',
                    default => 'bg-secondary',
                };
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $isCompleted = ($row->status === 'completed');
                
                $btn = '<div class="d-flex align-items-center justify-content-end gap-2">';
                
                // Only show Edit and Repeat buttons if not completed
                if (!$isCompleted) {
                    $btn .= '<a href="' . route('surveys.create', ['survey_uid' => $row->uid, 'step' => 1]) . '" class="btn btn-icon btn-sm btn-warning" title="Edit"><i class="ti ti-edit"></i></a>';
                }
                
                // Preview button always visible
                $btn .= '<button type="button" class="btn btn-icon btn-sm btn-primary btn-preview-survey" data-uid="' . $row->uid . '" title="Preview"><i class="ti ti-eye"></i></button>';
                
                // Only show Repeat button if not completed
                if (!$isCompleted) {
                    $btn .= '<a href="' . route('surveys.repeat', $row->uid) . '" class="btn btn-icon btn-sm btn-info" title="Repeat"><i class="ti ti-refresh"></i></a>';
                }
                
                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $commodities = \App\Models\RefCommodity::all();
        $prefill = null;
        if (request('prefill_contact_id')) {
            $prefill = Contacts::find(request('prefill_contact_id'));
        }
        return view('surveys.create', compact('commodities', 'prefill'));
    }

    public function show($uid)
    {
        $survey = SurveyBagianUmum::with(['prospekPetani', 'pelangganSTI', 'tokoPengecer', 'mitraPengepul', 'ketuaPoktan', 'penyelesaianMasalah', 'statistik', 'penutup'])
            ->where('uid', $uid)
            ->firstOrFail();
        return view('surveys.show', compact('survey'));
    }

    public function repeat($uid)
    {
        $survey = SurveyBagianUmum::where('uid', $uid)->firstOrFail();
        // Just redirect to create with prefill contact ID
        return redirect()->route('surveys.create', ['prefill_contact_id' => $survey->contact_id]);
    }

    public function store(Request $request)
    {
        $step = (int) $request->input('step', 1);
        $surveyUid = $request->input('survey_uid');

        try {
            DB::beginTransaction();

            if ($step === 1) {
                // Step 1: General Information
                $validated = $request->validate([
                    'jenisKontak' => 'required|string',
                    'namaLengkap' => 'required|string|max:255',
                    'noIdentity' => 'required|string|max:50',
                    'jabatan' => 'required|string',
                    'noWa' => 'required|string',
                    'noAlternatif' => 'nullable|string',
                    'alamatLahanUsaha' => 'required|string',
                    'desa' => 'required|string',
                    'desaKode' => 'required|string',
                    'kecamatan' => 'required|string',
                    'kecamatanKode' => 'required|string',
                    'kabupaten' => 'required|string',
                    'kabupatenKode' => 'required|string',
                    'provinsi' => 'required|string',
                    'provinsiKode' => 'required|string',
                    'komoditasUtama' => 'required|string',
                    'komoditasUtamaLainnya' => 'nullable|string',
                    'luasLahan' => 'required|string',
                    'solusi_CaraPakai' => 'nullable|string',
                    'solusi_Hasil' => 'nullable|numeric|min:0|max:10',
                    'solusi_AlasanPuasTidak' => 'nullable|string',
                    'sistemIrigasi' => 'nullable|string',
                    'sistemIrigasiLainnya' => 'nullable|string',
                    'musimTanamTanggal' => 'nullable|date',
                    'musimTanamPerkiraanPanen' => 'nullable|date',
                    'musimTanamTahapPertumbuhan' => 'nullable|string',
                    'sumberMengenalSti' => 'nullable|string',
                    'sumberMengenalStiLainnya' => 'nullable|string',
                    'persetujuanPerekamanPanggilan' => 'nullable|boolean',
                    'persetujuanPengolahanData' => 'nullable|boolean',
                    'evidenceKunjungan' => 'nullable|image|max:2048',
                ]);

                // Handle File Upload
                if ($request->hasFile('evidenceKunjungan')) {
                    $file = $request->file('evidenceKunjungan');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads/surveys', $filename, 'public');
                    $validated['evidenceKunjungan'] = 'storage/' . $path;
                }

                // Boolean Conversions
                $validated['persetujuanPerekamanPanggilan'] = $request->has('persetujuanPerekamanPanggilan');
                $validated['persetujuanPengolahanData'] = $request->has('persetujuanPengolahanData');

                // Sync with Contacts
                $contact = Contacts::where('noWa', $validated['noWa'])->first();
                if ($contact) {
                    $contact->update($validated);
                } else {
                    $contact = Contacts::create(array_merge($validated, ['uid' => (string) Str::uuid()]));
                }

                $survey = SurveyBagianUmum::updateOrCreate(
                    ['uid' => $surveyUid ?: (string) Str::uuid()],
                    array_merge($validated, [
                        'contact_id' => $contact->id,
                        'userId' => auth()->id()
                    ])
                );

                DB::commit();
                $survey->load(['prospekPetani', 'pelangganSTI', 'tokoPengecer', 'mitraPengepul', 'ketuaPoktan', 'penyelesaianMasalah', 'statistik', 'penutup']);

                return response()->json([
                    'success' => true,
                    'survey_id' => $survey->id,
                    'survey_uid' => $survey->uid,
                    'jenisKontak' => $survey->jenisKontak,
                    'data' => $survey,
                    'message' => 'Step 1 saved successfully.'
                ]);
            } else {
                // For Steps 2-5, we MUST have a valid survey_uid
                if (!$surveyUid) {
                    throw new \Exception("Survey UID is required for step $step");
                }

                $survey = SurveyBagianUmum::where('uid', $surveyUid)->firstOrFail();

                if ($step === 2) {
                $jenisKontak = $survey->jenisKontak;
                $data = $request->except(['step', 'survey_uid', '_token']);
                $data['uid'] = $data['uid'] ?? (string) Str::uuid();
                $data['surveyId'] = $survey->id;

                switch ($jenisKontak) {
                    case 'Farmer Prospect':
                        if ($request->hasFile('dokumentasi_Photo')) {
                            $file = $request->file('dokumentasi_Photo');
                            $filename = time() . '_prospect_' . $file->getClientOriginalName();
                            $path = $file->storeAs('uploads/surveys', $filename, 'public');
                            $data['dokumentasi_Photo'] = 'storage/' . $path;
                        }
                        $data['uid'] = (string) Str::uuid();
                        SurveyProspekPetani::updateOrCreate(['surveyId' => $survey->id], $data);
                        break;
                    case 'STI Customer':
                        $checkboxes = ['butuhPendampingan', 'minatIkutLanjutProgramReward', 'memberGetMember'];
                        foreach ($checkboxes as $field) {
                            $data[$field] = $request->has($field) ? true : false;
                        }
                        if ($request->hasFile('masalahYangMuncul_Photo')) {
                            $file = $request->file('masalahYangMuncul_Photo');
                            $filename = time() . '_sti_' . $file->getClientOriginalName();
                            $path = $file->storeAs('uploads/surveys', $filename, 'public');
                            $data['masalahYangMuncul_Photo'] = 'storage/' . $path;
                        }
                        $data['uid'] = (string) Str::uuid();
                        SurveyPelangganSTI::updateOrCreate(['surveyId' => $survey->id], $data);
                        break;
                    case 'Shop/Retailer':
                        $checkboxes = ['rencanaKerjasama_POAwal'];
                        foreach ($checkboxes as $field) {
                            $data[$field] = $request->has($field) ? true : false;
                        }
                        $data['uid'] = (string) Str::uuid();
                        SurveyTokoPengecer::updateOrCreate(['surveyId' => $survey->id], $data);
                        break;
                    case 'Partner/Collector':
                        $checkboxes = ['profil_Musiman', 'potensiIntegrasiDataPanen'];
                        foreach ($checkboxes as $field) {
                            $data[$field] = $request->has($field) ? true : false;
                        }
                        $data['uid'] = (string) Str::uuid();
                        SurveyMitraPengepul::updateOrCreate(['surveyId' => $survey->id], $data);
                        break;
                    case 'Farmer Group Head':
                        // No specific checkboxes requiring boolean conversion identified in request,
                        // but logic remains consistent.
                        $data['uid'] = (string) Str::uuid();
                        SurveyKetuaPoktan::updateOrCreate(['surveyId' => $survey->id], $data);
                        break;
                }
            } elseif ($step == 3) {
                SurveyPenyelesaianMasalah::updateOrCreate(
                    ['surveyId' => $survey->id],
                    array_merge($request->except(['step', 'survey_uid', '_token']), ['uid' => (string) Str::uuid()])
                );
            } elseif ($step == 4) {
                // Agricultural Statistics
                $data = $request->only([
                    'curahHujan',
                    'kejadianEkstrem',
                    'tanggal',
                    'harga_TrenHargaPupukBenihPestisida',
                    'harga_HargaJualHasilPanen',
                    'perubahanPraktikBudidaya_VarietasBaru',
                    'perubahanPraktikBudidaya_PerubahanTeknik',
                    'perubahanPraktikBudidaya_PenggunaanMesin',
                    'sumberInformasiPetani_Media',
                    'sumberInformasiPetani_TokohLokal',
                    'sumberInformasiPetani_Penyuluh',
                ]);
                $data['uid'] = (string) Str::uuid();
                SurveyStatistikKonteksPertanian::updateOrCreate(['surveyId' => $survey->id], $data);
            } elseif ($step == 5) {
                // Closing & Summary
                $data = $request->only([
                    'ringkasanKebutuhanSolusi',
                    'komitmenTindakLanjut_Apa',
                    'komitmenTindakLanjut_OlehSiapa',
                    'komitmenTindakLanjut_KapanTanggal',
                    'komitmenTindakLanjut_KapanJam',
                    'jadwalFollowup_Tanggal',
                    'jadwalFollowup_Jam',
                    'jadwalFollowup_Kanal',
                ]);

                // Handle File Upload for Documentation
                if ($request->hasFile('dokumentasi')) {
                    $file = $request->file('dokumentasi');
                    $filename = time() . '_doc_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads/surveys', $filename, 'public');
                    $data['dokumentasi'] = 'storage/' . $path;
                }

                $data['uid'] = (string) Str::uuid();
                SurveyPenutupRingkasan::updateOrCreate(['surveyId' => $survey->id], $data);
            }


            DB::commit();
            $survey->load(['prospekPetani', 'pelangganSTI', 'tokoPengecer', 'mitraPengepul', 'ketuaPoktan', 'penyelesaianMasalah', 'statistik', 'penutup']);

            return response()->json([
                'success' => true,
                'message' => "Step $step saved successfully.",
                'data' => $survey
            ]);
        }
        } catch (\Exception $e) {
            DB::rollBack();
            $dbName = DB::connection()->getDatabaseName();
            $debugInfo = " db: $dbName, Step: " . $request->input('step') . ", UID: " . $request->input('survey_uid');
            return response()->json(['success' => false, 'message' => $e->getMessage() . $debugInfo], 500);
        }
    }

    public function getDetails($uid)
    {
        $survey = SurveyBagianUmum::with(['prospekPetani', 'pelangganSTI', 'tokoPengecer', 'mitraPengepul', 'ketuaPoktan', 'penyelesaianMasalah', 'statistik', 'penutup', 'contact', 'followupUser'])
            ->where('uid', $uid)
            ->firstOrFail();

        // Check if already promoted
        $isPromoted = false;
        if ($survey->contact_id) {
            $isPromoted = \App\Models\Customer::where('contact_id', $survey->contact_id)->exists();
        }

        return response()->json([
            'success' => true,
            'data' => $survey,
            'is_promoted' => $isPromoted
        ]);
    }

    public function promoteToProspect(Request $request, $uid)
    {
        try {
            DB::beginTransaction();
            $survey = SurveyBagianUmum::with('contact')->where('uid', $uid)->firstOrFail();
            
            // Validate that follow-up user has been assigned
            if (!$survey->followup_user_id) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please assign a follow-up user before promoting to prospect.'
                ], 422);
            }
            
            $contact = $survey->contact;

            if (!$contact) {
                return response()->json(['success' => false, 'message' => 'Contact not found for this survey.'], 404);
            }

            // Check if already exists
            $exists = \App\Models\Customer::where('contact_id', $contact->id)->first();
            if ($exists) {
                return response()->json(['success' => true, 'message' => 'Contact already promoted.', 'data' => $exists]);
            }

            // Map commodity
            $commodityId = null;
            if ($survey->komoditasUtama) {
                $comp = \App\Models\RefCommodity::where('name', 'like', '%' . $survey->komoditasUtama . '%')->first();
                if ($comp) {
                    $commodityId = $comp->id;
                }
            }

            $customer = \App\Models\Customer::create([
                'uid' => Str::uuid()->toString(),
                'type' => 'prospect',
                'name' => $contact->namaLengkap,
                'noIdentity' => $contact->noIdentity,
                'tglLahir' => $contact->tglLahir,
                'phone' => $contact->noWa,
                'address' => $contact->alamatLahanUsaha,
                'village' => $contact->desa,
                'village_code' => $contact->desaKode,
                'sub_district' => $contact->kecamatan,
                'sub_district_code' => $contact->kecamatanKode,
                'district' => $contact->kabupaten,
                'district_code' => $contact->kabupatenKode,
                'province' => $contact->provinsi,
                'province_code' => $contact->provinsiKode,
                'point_coordinate' => $contact->titikKoordinat,
                'photo_profile' => $contact->photoProfile,
                'contact_id' => $contact->id,
                'commodity_id' => $commodityId,
                'created_by' => auth()->id(),
                'status' => 'followup',
                'followup_user_id' => $survey->followup_user_id
            ]);


            $survey->update([
                'status' => 'completed'
            ]);

            // Notify Current User
            auth()->user()->notify(new \App\Notifications\SurveyPromoted($customer, $survey));

            // Notify Assigned Follow-up User (if different)
            if ($survey->followup_user_id && $survey->followup_user_id !== auth()->id()) {
                $followupUser = \App\Models\User::find($survey->followup_user_id);
                if ($followupUser) {
                    $followupUser->notify(new \App\Notifications\SurveyPromoted($customer, $survey));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contact promoted to prospect successfully.',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function assignFollowupUser(Request $request, $uid)
    {
        try {
            $request->validate([
                'followup_user_id' => 'required|exists:users,id'
            ]);

            $survey = SurveyBagianUmum::where('uid', $uid)->firstOrFail();
            $survey->update([
                'followup_user_id' => $request->followup_user_id
            ]);
            
            // Notify the assigned user
            $user = \App\Models\User::find($request->followup_user_id);
            if ($user) {
                $user->notify(new \App\Notifications\FollowupAssigned($survey));
            }

            // Load the updated relationship
            $survey->load('followupUser');

            return response()->json([
                'success' => true,
                'message' => 'Follow-up user assigned successfully.',
                'data' => [
                    'followup_user_id' => $survey->followup_user_id,
                    'followup_user_name' => $survey->followupUser ? $survey->followupUser->name : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
