<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyStatistikKonteksPertanian extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_statistik_konteks_pertanian';

    protected $fillable = [
        'uid',
        'surveyId',
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
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }
}
