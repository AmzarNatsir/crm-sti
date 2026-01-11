<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyPenutupRingkasan extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_penutup_ringkasan';

    protected $fillable = [
        'uid',
        'surveyId',
        'ringkasanKebutuhanSolusi',
        'komitmenTindakLanjut_Apa',
        'komitmenTindakLanjut_OlehSiapa',
        'komitmenTindakLanjut_KapanTanggal',
        'komitmenTindakLanjut_KapanJam',
        'jadwalFollowup_Tanggal',
        'jadwalFollowup_Jam',
        'jadwalFollowup_Kanal',
        'dokumentasi',
    ];

    public function surveyBagianUmum()
    {
        return $this->belongsTo(SurveyBagianUmum::class, 'surveyId');
    }

}
