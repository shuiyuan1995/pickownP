<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InPacket extends Model
{
    protected $fillable = [
        'id',
        'outid',
        'userid',
        'income_sum',
        'is_chailei',
        'blocknumber',
        'eosid',
        'is_reward',
        'reward_type',
        'reward_sum',
        'created_at',
        'updated_at'
    ];

    public $statusArr = [1 => '正常', 2 => '异常'];

    public $is_chailei_arr = [1 => '未踩雷', 2 => '踩雷'];

    public $is_reward_arr = [1 => '未中奖', 2 => '中奖'];

    public $rewardTypeArr = ['无','对子', '三条', '最小奖', '整数', '顺子', '炸弹', '最大奖'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
