<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeliverySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'order_id',
        'delivery_date',
        'arrival_date',
        'user_id',
        'employee_id',
        'status'
    ];

    protected static function booted()
    {
        static::creating(function ($schedule) {
            $schedule->uid = (string) Str::uuid();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function personnel()
    {
        return $this->belongsToMany(Employee::class, 'delivery_schedule_personnel');
    }

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }
}
