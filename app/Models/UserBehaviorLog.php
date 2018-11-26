<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBehaviorLog extends Model
{
    protected $fillable = ['id', 'userid', 'type', 'msg', 'created_at', 'updated_at'];

    public $typeArr = [1 => '发红包', 2 => '抢红包', 3=>'中招',4=>'类型5',6=>'类型6'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
