<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginRecord extends Model
{
    protected $fillable = ['id', 'userid', 'ip', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
