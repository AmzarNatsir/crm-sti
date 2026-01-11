<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'uid',
        'customer_id',
        'user_id',
        'invoice_no',
        'invoice_date',
        'total_amount',
        'payment_method_id',
        'compaign_id',
        'payment_status',
        'invoice_discount', 
        'delivery_status',
    ];

    protected $casts = [
        'invoice_date' => 'date'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethodModel::class, 'payment_method_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItemsModel::class, 'order_id');
    }

    public function campaign()
    {
        return $this->belongsTo(RefCompign::class, 'compaign_id');
    }
}
