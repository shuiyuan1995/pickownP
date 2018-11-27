<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoReadRecord extends Model
{
    //
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }

    public function getMsg()
    {
        return $this->hasOne(SiteMail::class, 'id', 'site_mail_id');
    }
}
