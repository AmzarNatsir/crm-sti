<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'type',
        'commodity_id',
        'name',
        'identity_no',
        'date_of_birth',
        'company_name',
        'phone',
        'email',
        'address',
        'industry',
        'source',
        'created_by',
        'village',
        'village_code',
        'sub_district',
        'sub_district_code',
        'district',
        'district_code',
        'province',
        'province_code',
        'point_coordinate',
        'photo_profile',
        'contact_id',
        'followup_user_id',
        'status'
    ];


    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'contact_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function commodity()
    {
        return $this->belongsTo(RefCommodity::class, 'commodity_id');
    }

    public function lead()
    {
        return $this->hasOne(Lead::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function provinceRelation()
    {
        return $this->belongsTo(Province::class, 'province_code', 'id');
    }

    public function regencyRelation()
    {
        return $this->belongsTo(Regency::class, 'district_code', 'id');
    }

    public function districtRelation()
    {
        return $this->belongsTo(District::class, 'sub_district_code', 'id');
    }

    public function villageRelation()
    {
        return $this->belongsTo(Village::class, 'village_code', 'id');
    }

    public function followupUser()
    {
        return $this->belongsTo(User::class, 'followup_user_id');
    }
}
