<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParamWilayah extends Model
{
    protected $table = 'ref_param_wilayah';

    protected $fillable = [
        'zona',
        'province_id',
        'ckm',
        'ct',
        'tarif_min',
        'alpha_max_retail',
        'alpha_max_reseller',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
