<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    use HasFactory;

    protected $table = 'expedition';

    protected $fillable = [
        'uid',
        'name',
        'address',
        'email',
        'phone_number',
        'contact_person',
    ];
}
