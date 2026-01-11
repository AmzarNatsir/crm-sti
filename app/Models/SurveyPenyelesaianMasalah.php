<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyPenyelesaianMasalah extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_penyelesaian_masalah';

    protected $fillable = [
        'uid',
        'surveyId',
        'deskripsi_SejakKapan',
        'deskripsi_TahapanTanaman',
        'dampak_LuasAreaTerdampak',
        'dampak_EstimasiPotensiPenurunanHasil',
        'riwayatTindakan_ProdukSolusi',
        'riwayatTindakan_Dosis',
        'riwayatTindakan_Tanggal',
        'akarDugaan',
        'akarDugaan_Lainnya',
        'kebutuhanDukungan',
        'rencanaAksiDisepakati_PaketRekomendasi',
        'rencanaAksiDisepakati_Siapa',
        'slaPemantauan_Tanggal',
        'slaPemantauan_Jam',
        'statusTiket',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
