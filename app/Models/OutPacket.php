<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutPacket extends Model
{
    protected $fillable = ['id','gameid','userid','seed_sum','number','surplus_sum','count','up','down','surplus_sum','status','created_at','updated_at'];
    public function game(){
        return $this->hasOne(GamePartition::class,'id','gameid');
    }
    public function user(){
        return $this->hasOne(User::class,'id','userid');
    }
}
