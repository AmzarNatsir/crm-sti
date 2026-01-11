<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'guard_name', 'subject_id'];

    public function subject()
    {
        return $this->belongsTo(PermissionSubject::class, 'subject_id');
    }
}
