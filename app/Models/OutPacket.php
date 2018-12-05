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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
