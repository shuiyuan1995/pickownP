<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePartition extends Model
{
    protected $fillable = ['id', 'name', 'sum', 'up', 'down', 'count', 'status', 'created_at', 'updated_at', 'number'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->sum = $model->sum * 10000;
            $model->up = $model->up * 100;
            $model->down = $model->down * 100;
        });
        static::updating(function ($model) {
            $model->sum = $model->sum * 10000;
            $model->up = $model->up * 100;
            $model->down = $model->down * 100;
        });
    }
}
