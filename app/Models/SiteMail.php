<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMail extends Model
{
    //
    const TYPE_NOTICE =1;
    const TYPE_INFORMATION =2;

    const STATUS_FALSE = 1;
    const STATUS_TRUE = 2;
    public static $type = [
        self::TYPE_NOTICE => '消息',
        self::TYPE_INFORMATION => '公告',
    ];
    public static $status = [
        self::STATUS_FALSE => '草稿',
        self::STATUS_TRUE => '发布',
    ];
    public function getTypeNameAttribute(){
        return self::$type[$this->type];
    }
    public function getStatusNameAttribute(){
        return self::$status[$this->status];
    }
    public function getUser(){
        return $this->hasOne(User::class, 'id', 'userid')->withDefault([
            'name' => '公告信息',
        ]);
    }
}
