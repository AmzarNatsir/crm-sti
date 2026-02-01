<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoefMedan extends Model
{
    protected $table = 'ref_koef_medan';
    protected $fillable = [
        'kode_medan',
        'description',
        'km',
    ];
}
