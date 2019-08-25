<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLinks extends Model
{
    protected $table = 'user_links';

    public function app()
    {
        return $this->belongsTo('App\Models\SocialService', 'service_id', 'id');
    }

}
