<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'uid',
        'name',
        'type_id',
        'merk_id',
        'category',
        'price',
        'margin',
        'is_active',
        'image'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uid = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeModel::class, 'type_id');
    }

    public function merk()
    {
        return $this->belongsTo(MerkModel::class, 'merk_id');
    }
}
