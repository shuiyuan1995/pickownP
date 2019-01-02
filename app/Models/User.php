<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $userData)
 */
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
}
