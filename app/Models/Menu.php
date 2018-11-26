<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['id', 'name', 'pid', 'key', 'url', 'sort'];

    public $timestamps = false;

    public function parent()
    {
        return $this->hasOne(Menu::class, 'id', 'pid');
    }

    public function users()
    {
        return $this->belongsToMany(AdminUser::class, 'user_menus', 'menu_id', 'user_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'pid', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->pid === null) {
                $model->pid = 0;
            }
        });
        static::updating(function ($model) {
            if ($model->pid === null) {
                $model->pid = 0;
            }
        });
    }
}
