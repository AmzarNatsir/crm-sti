<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected static function booted()
    {
        static::creating(function ($activity) {
            $activity->uid = (string) \Illuminate\Support\Str::uuid();
        });
    }

    protected $fillable = [
        'uid',
        'customer_id',
        'lead_id',
        'user_id',
        'type',
        'notes',
        'follow_up_date',
        'status'
    ];

    protected $casts = [
        'follow_up_date' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
