<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefCommodity extends Model
{
    protected $table = 'ref_commodity';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'season',
        'fertillization_in_season',
    ];
}
