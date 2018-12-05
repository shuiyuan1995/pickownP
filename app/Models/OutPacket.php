<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutPacket extends Model
{
    protected $fillable = [
        'id',
        'userid',
        'issus_sum',
        'count',
        'eosid',
        'blocknumber',
        'tail_number',
        'status',
        'created_at',
        'updated_at'
    ];
    public $statusArr = [1 => '未抢完', 2 => '已抢完'];
    public $indexArr = [
        '1.0000' => 0,
        '5.0000' => 1,
        '10.0000' => 2,
        '20.0000' => 3,
        '50.0000' => 4,
        '100.0000' => 5
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }

    public function inpacket()
    {
        return $this->hasMany(InPacket::class, 'outid', 'id');
    }

}
