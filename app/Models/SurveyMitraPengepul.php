<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyMitraPengepul extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_mitra_pengepul';

    protected $fillable = [
        'uid',
        'surveyId',
        'profil_NamaUsaha',
        'profil_KomoditasUtama',
        'profil_WilayahJangkauan',
        'profil_Musiman',
        'kebutuhan_KonsistensiPasokan',
        'kebutuhan_Kualitas',
        'kebutuhan_DukunganBudidaya',
        'modelKerjasama_SkemaKemitraan',
        'modelKerjasama_KeterlibatanProgram',
        'modelKerjasama_DukunganLogistikEdukasi',
        'potensiIntegrasiDataPanen',
        'komitmenAwal_PertemuanSelanjutnya',
        'komitmenAwal_DataYangDibutuhkan',
        'komitmenAwal_PicTeknis',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
