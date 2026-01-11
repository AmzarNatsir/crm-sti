<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyProspekPetani extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_prospek_petani';

    protected $fillable = [
        'uid',
        'surveyId',
        'tantanganUtamaSaatIni',
        'tantanganUtamaSaatIni_Lainnya',
        'dampakHasil_Penurunan',
        'dampakHasil_Area',
        'solusi_ProdukMerek',
        'solusi_Dosis',
        'solusi_CaraPakai',
        'solusi_Hasil',
        'solusi_AlasanPuasTidak',
        'rencanaTanamAnggaran_Budget',
        'rencanaTanamAnggaran_TargetHasil',
        'rencanaTanamAnggaran_BatasWaktuTanam',
        'perilakuPembelian_TokoLangganan',
        'perilakuPembelian_Pengepul',
        'perilakuPembelian_PengambilKeputusan',
        'minatProgramPembayaranPerpanen',
        'minatProgramPembayaranPerpanen_KisaranHasil',
        'minatProgramPembayaranPerpanen_FrekuensiPanen',
        'minatProgramPembayaranPerpanen_BuktiHasil',
        'minatProgramPembayaranPerpanen_PreferensiTenor',
        'minatProgramPembayaranPerpanen_Kesediaan',
        'minatProgramRewardMemberGetMember',
        'minatProgramRewardMemberGetMember_TopikReward',
        'kebutuhanPendampinganAgronomis',
        'kebutuhanPendampinganAgronomis_Topik',
        'kebutuhanPendampinganAgronomis_WaktuKunjungan',
        'kesiapanUjiCobaProdukSti',
        'kesiapanUjiCobaProdukSti_AlasanTidakBerminat',
        'komitmenAwal',
        'dokumentasi_Photo',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
