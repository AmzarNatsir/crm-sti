<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'common_payment_method';
    protected $fillable = [
        'uid',
        'name',
        'slug',
    ];
}
