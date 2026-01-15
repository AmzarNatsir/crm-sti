<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'uid',
        'employee_number',
        'identitiy_number',
        'name',
        'place_of_birth',
        'birth_date',
        'last_education',
        'gender',
        'email',
        'phone',
        'address',
        'positionId',
        'hire_date',
        'join_date',
        'status',
        'salary',
        'photo',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'positionId');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
}
