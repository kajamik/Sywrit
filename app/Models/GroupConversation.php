<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupConversation extends Model
{
    protected $table = 'group_conversation';

    protected $fillable = ['group_id', 'text', 'user_id'];

    public function getUserInfo()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function permission()
    {
      return $this->belongsTo('App\Models\GroupPermission', 'user_id', 'user_id');
    }

}
