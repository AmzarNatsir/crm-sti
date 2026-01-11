<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyBagianUmum extends Model
{
    public static $snakeAttributes = false;
    protected $table = 'survey_bagian_umum';

    protected $fillable = [
        'uid',
        'jenisKontak',
        'namaLengkap',
        'noIdentity',
        'tglLahir',
        'jabatan',
        'noWa',
        'noAlternatif',
        'alamatLahanUsaha',
        'desa',
        'desaKode',
        'kecamatan',
        'kecamatanKode',
        'kabupaten',
        'kabupatenKode',
        'provinsi',
        'provinsiKode',
        'titikKoordinat',
        'komoditasUtama',
        'komoditasUtamaLainnya',
        'luasLahan',
        'sistemIrigasi',
        'sistemIrigasiLainnya',
        'musimTanamTanggal',
        'musimTanamPerkiraanPanen',
        'musimTanamTahapPertumbuhan',
        'sumberMengenalSti',
        'sumberMengenalStiLainnya',
        'persetujuanPerekamanPanggilan',
        'persetujuanPengolahanData',
        'evidenceKunjungan',
        'contact_id',
        'userId',
        'followup_user_id',
        'status',
    ];

    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'contact_id');
    }

    public function prospekPetani()
    {
        return $this->hasOne(SurveyProspekPetani::class, 'surveyId');
    }

    public function pelangganSTI()
    {
        return $this->hasOne(SurveyPelangganSTI::class, 'surveyId');
    }

    public function tokoPengecer()
    {
        return $this->hasOne(SurveyTokoPengecer::class, 'surveyId');
    }

    public function mitraPengepul()
    {
        return $this->hasOne(SurveyMitraPengepul::class, 'surveyId');
    }

    public function ketuaPoktan()
    {
        return $this->hasOne(SurveyKetuaPoktan::class, 'surveyId');
    }

    public function penyelesaianMasalah()
    {
        return $this->hasOne(SurveyPenyelesaianMasalah::class, 'surveyId');
    }

    public function statistik()
    {
        return $this->hasOne(SurveyStatistikKonteksPertanian::class, 'surveyId');
    }

    public function penutup()
    {
        return $this->hasOne(SurveyPenutupRingkasan::class, 'surveyId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function followupUser()
    {
        return $this->belongsTo(User::class, 'followup_user_id');
    }
}
