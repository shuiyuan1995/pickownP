<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdManagments extends Model
{
    //
    protected $table = 'ad_managments';

    public function adposition()
    {
        return $this->belongsTo(AdPositions::class, 'ad_id', 'id');
    }
}
