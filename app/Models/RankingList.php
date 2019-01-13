<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingList extends Model
{
    protected $fillable = [
        'id',
        'userid',
        'balance',
        'prize',
        'ranking',
        'created_at',
        'updated_at'
    ];
    public function user(){
        return $this->hasOne(User::class,'id','userid');
    }
}
