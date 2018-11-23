<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InPacket extends Model
{
    protected $fillable = ['id','outid','userid','sum','packet_tail_number','is_win','status','created_at','updated_at'];
    public function user(){
        return $this->hasOne(User::class,'id','userid');
    }
}
