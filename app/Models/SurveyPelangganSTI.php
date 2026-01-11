<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyPelangganSTI extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_pelanggan_sti';

    protected $fillable = [
        'uid',
        'surveyId',
        'produkStiYangDigunakan_Nama',
        'produkStiYangDigunakan_Batch',
        'produkStiYangDigunakan_TanggalApplikasi',
        'produkStiYangDigunakan_DosisCaraPakai',
        'perkembanganTanaman_Pertumbuhan',
        'perkembanganTanaman_HijauDaun',
        'perkembanganTanaman_Akar',
        'perkembanganTanaman_BungaPolongBuah',
        'kondisiCuaca',
        'kondisiCuaca_Catatan',
        'masalahYangMuncul_Jenis',
        'masalahYangMuncul_LuasTerdampak',
        'masalahYangMuncul_Keparahan',
        'masalahYangMuncul_Photo',
        'tindakanKorektif_Apa',
        'tindakanKorektif_Kapan',
        'tindakanKorektif_HasilAwal',
        'butuhPendampingan',
        'butuhPendampingan_Jadwal',
        'butuhPendampingan_Lokasi',
        'butuhPendampingan_Tujuan',
        'perkiraanHasil',
        'rencanaPanen',
        'kepuasanTerhadapProdukLayanan_Nilai',
        'kepuasanTerhadapProdukLayanan_Alasan',
        'minatIkutLanjutProgramReward',
        'memberGetMember',
        'memberGetMember_Referal',
        'nextStep_TindakLanjut',
        'nextStep_WaktuFollowup',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
