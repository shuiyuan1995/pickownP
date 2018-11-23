<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
        'id',
        'email',
        'phone',
        'referral_code',
        'invite_id',
        'in_packet_eos',
        'out_packet_eos',
        'reward_eos',
        'punish_eos',
        'created_at',
        'updated_at'
    ];
}
