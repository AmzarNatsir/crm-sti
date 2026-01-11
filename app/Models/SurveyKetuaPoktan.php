<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyKetuaPoktan extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_ketua_poktan';

    protected $fillable = [
        'uid',
        'surveyId',
        'profil_Nama',
        'profil_JumlahAnggota',
        'profil_TotalLuasTanam',
        'profil_KomoditasMayor',
        'agendaBudidaya_KalenderTanam',
        'agendaBudidaya_TantanganUmum',
        'agendaBudidaya_KegiatanKelompok',
        'ketertarikan_SosialisasiProduk',
        'ketertarikan_DemoPlot',
        'ketertarikan_ProgramPendampingan',
        'ketertarikan_SkemaPembelianKolektif',
        'syaratEkspektasi_TransparansiHarga',
        'syaratEkspektasi_DukunganTeknis',
        'syaratEkspektasi_RewardKelompok',
        'aksiAwal_JadwalSosialisasi',
        'aksiAwal_LahanDemo',
        'aksiAwal_Anggota',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
