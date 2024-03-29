<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupConversation extends Model
{
    protected $table = 'group_conversation';

    protected $fillable = ['user_id', 'article_id', 'group_id', 'text'];

    public function getUserInfo()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

}
