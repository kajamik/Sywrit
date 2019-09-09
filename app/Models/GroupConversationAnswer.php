<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupConversationAnswer extends Model
{
    protected $table = 'group_conversation_answer';

    protected $fillable = ['conversation_id', 'user_id', 'text'];

    public function getUserInfo()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

}
