<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupArticle extends Model
{
    protected $table = 'group_member';

    public function getUserInfo()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

}
