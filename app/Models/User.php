<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['id', 'name', 'password', 'walletid', 'last_time', 'status', 'created_at', 'updated_at'];
}
