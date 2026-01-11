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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminSurveyController extends Controller
{
    public function create()
    {
        $commodities = \App\Models\RefCommodity::all();
        $users = User::select('id', 'name')->orderBy('name', 'asc')->get();
        $prefill = null;
        if (request('prefill_contact_id')) {
            $prefill = Contacts::find(request('prefill_contact_id'));
        }
        return view('admin-surveys.create', compact('commodities', 'users', 'prefill'));
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
                    'userId' => 'required|exists:users,id',
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
                        'contact_id' => $contact->id
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

                    if ($request->hasFile('dokumentasi')) {
                        $file = $request->file('dokumentasi');
                        $filename = time() . '_doc_' . $file->getClientOriginalName();
                        $path = $file->storeAs('uploads/surveys', $filename, 'public');
                        $data['documentation'] = 'storage/' . $path;
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
