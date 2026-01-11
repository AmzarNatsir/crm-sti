<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyTokoPengecer extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_toko_pengecer';

    protected $fillable = [
        'uid',
        'surveyId',
        'profil_NamaToko',
        'profil_Alamat',
        'profil_KanalPenjualan',
        'profil_VolumePenjualanBulanan',
        'profil_MerekYangDijual',
        'kebutuhanKetertarikan_ProdukSti',
        'kebutuhanKetertarikan_Margin',
        'kebutuhanKetertarikan_SyaratPembayaran',
        'kebutuhanKetertarikan_DukunganPromosi',
        'kesediaanProgram_DisplayMateri',
        'kesediaanProgram_StokAwal',
        'kesediaanProgram_DemoPlot',
        'kesediaanProgram_ProgramPoin',
        'rencanaKerjasama_POAwal',
        'rencanaKerjasama_POAwal_Estimasi',
        'rencanaKerjasama_JadwalPelatihan',
        'rencanaKerjasama_TargetTigaBulan',
        'memberGetMember_Nama',
        'memberGetMember_Kontak',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
