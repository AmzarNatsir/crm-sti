<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefCompign extends Model
{
    protected $table = 'ref_compign';
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'start_date',
        'end_date',
        'target_sales',
        'target_revenue',
        'target_segment',
        'target_area',
        'target_product',
        'channel',
        'target_promotion',
        'badget',
        'actual_sales',
        'actual_revenue',
        'roi',
        'notes',
        'status',
    ];
}
