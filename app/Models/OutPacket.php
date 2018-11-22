<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutPacket extends Model
{
    public function game(){
        return $this->hasOne(GamePartition::class,'id','gameid');
    }
    public function user(){
        return $this->hasOne(User::class,'id','userid');
    }
}
