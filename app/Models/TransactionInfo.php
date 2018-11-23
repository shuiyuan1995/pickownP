<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionInfo extends Model
{
    protected $fillable = [
        'id',
        'issus_userid',
        'income_userid',
        'type',
        'status',
        'eos',
        'issus_count_sum',
        'msg',
        'created_at',
        'updated_at'
    ];

    protected $typeArr = [1 => '抢红包', 2 => '发红包'];
    protected $statusArr = [1 => '正常', 2 => '失败', 3 => '异常', 4 => '后台修复'];

    public function issus_user()
    {
        return $this->hasOne(User::class, 'id', 'issus_userid');
    }

    public function income_user()
    {
        return $this->hasOne(User::class, 'id', 'income_userid');
    }
}
