<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $table = 'contacts';

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
        'photoProfile',
    ];
}
