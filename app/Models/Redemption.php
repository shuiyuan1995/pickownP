<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    protected $fillable = [
        'id',
        'request_id',
        'userid',
        'sum',
        'coin_type',
        'request_time',
        'status',
        'created_at',
        'updated_at'
    ];
    public $statusArr = [
        1 => '赎回中',
        2 => '已赎回',
        3 => '失败'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
