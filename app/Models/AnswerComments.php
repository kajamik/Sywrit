<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerComments extends Model
{
    protected $table = 'answer_comments';

    public function getUserInfo()
    {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
