<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemsModel extends Model
{
    protected $table = 'orders_items';
    protected $fillable = [
        'uid',
        'order_id',
        'product_id',
        'price_type',
        'qty',
        'price',
        'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
