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
        'addr',
        'created_at',
        'updated_at'
    ];

    public $statusArr = [1 => '正常', 2 => '异常'];

    public $is_chailei_arr = [1 => '踩雷', 2 => '未踩雷'];

    public $is_reward_arr = [1 => '未中奖', 2 => '中奖'];

    public $rewardTypeArr = [0=>'无', 1=>'对子', 2=>'三条', 3=>'整数', 4=>'顺子', 5=>'炸弹'];

    public function out()
    {
        return $this->hasOne(OutPacket::class, 'id', 'outid');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
