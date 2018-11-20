<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $fillable = ['id', 'name', 'guard_name', 'display_name', 'pid', 'created_at', 'updated_at'];

    public function parent()
    {
        return $this->hasOne(Permission::class,'id', 'pid');
    }

    public function children()
    {
        return $this->hasMany(Permission::class,'pid', 'id');
    }
}
