<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'id',
        'name',
        'publickey',
        'walletid',
        'invite',
        'addr',
        'status',
        'created_at',
        'updated_at'
    ];
    public $statusArr = [1 => '正常', 2 => '冻结'];

    public function getOutPackets() {
        return $this->hasMany(OutPacket::class,'userid','id');
    }
    public function issus_user(){
        return $this->hasOne(User::class,'id','issus_userid');
    }
    public function income_user(){
        return $this->hasOne(User::class,'id','income_userid');
    }
}
