<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'customer_id',
        'assigned_to',
        'status',
        'score',
        'estimated_value',
        'expected_close_date',
        'lost_reason'
    ];

    protected $casts = [
        'expected_close_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
