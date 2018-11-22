<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InPacket extends Model
{
    public function user(){
        return $this->hasOne(User::class,'id','userid');
    }
}
