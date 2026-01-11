<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerkModel extends Model
{
    protected $table = 'common_merk';
    protected $fillable = [
        'uid',
        'name',
        'slug',
    ];
}
