<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPositions extends Model
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_WORD  = 3;

    public static $statusMap = [
        self::STATUS_ENABLE => '是',
        self::STATUS_DISABLE => '否',
    ];
    public static $type = [
        self::TYPE_IMAGE => '图片',
        self::TYPE_VIDEO => '视频',
        self::TYPE_WORD  => '文字',
    ];
    protected $table = 'ad_positions';

    public function admanagment(){

        return $this->hasMany(AdManagments::class, 'ad_id', 'id');
    }
    public function getUseNameAttribute()
    {
        return self::$statusMap[$this->is_use];
    }
    public function getTypeNameAttribute()
    {
        return self::$type[$this->type];
    }
}
