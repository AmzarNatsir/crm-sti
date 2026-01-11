<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeModel extends Model
{
    protected $table = 'common_type';
    protected $fillable = [
        'uid',
        'name',
        'slug',
    ];
}
